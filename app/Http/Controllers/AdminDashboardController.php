<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\StockTransaction;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    // Dashboard
    public function index()
    {
        // Menggunakan try-catch untuk penanganan error yang lebih baik
        try {
            // Data untuk Kartu Info
            $totalProducts = Product::count();
            $totalSuppliers = Supplier::count();
            $totalUsers = User::count(); // Data khusus Admin
            $totalCategories = Category::count(); // Data tambahan untuk Admin

            // Menyiapkan data untuk grafik transaksi 7 hari terakhir
            $chartData = ['categories' => [], 'incoming' => [], 'outgoing' => []];
            for ($i = 6; $i >= 0; $i--) {
                $day = now()->subDays($i);
                $chartData['categories'][] = $day->format('d M');
                
                // Gunakan kolom 'date' dan nilai ENUM yang benar
                $chartData['incoming'][] = StockTransaction::where('type', 'Masuk')
                    ->whereDate('date', $day->format('Y-m-d'))
                    ->sum('quantity');
                
                $chartData['outgoing'][] = StockTransaction::where('type', 'Keluar')
                    ->whereDate('date', $day->format('Y-m-d'))
                    ->sum('quantity');
            }

            $lowStockProducts = Product::all()->filter(function ($product) {
                // Pastikan min_stock ada nilainya sebelum membandingkan
                return isset($product->min_stock) && $product->current_stock <= $product->min_stock;
            })->take(5); // Ambil 5 produk teratas


            $recentTransactions = StockTransaction::with('product', 'user')->orderBy('date', 'desc')->latest()->limit(5)->get();
            $recentUsers = User::latest()->limit(5)->get();

            return view('pages.admin.dashboard.index', compact(
                'totalProducts',
                'totalSuppliers',
                'totalUsers',
                'totalCategories',
                'chartData',
                'recentTransactions',
                'recentUsers',
                'lowStockProducts' // <-- Kirim data baru ke view
            ));

        } catch (\Exception $e) {
            // ... (fallback tidak berubah, tapi tambahkan lowStockProducts) ...
             return view('pages.admin.dashboard.index', [
                // ...
                'recentUsers' => collect([]),
                'lowStockProducts' => collect([]) // <-- Tambahkan ini
            ])->with('error', 'Gagal memuat data dashboard: ' . $e->getMessage());
        }
    }


    // Users Management
    public function userList(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(10);
        $roles = ['Admin', 'Manajer Gudang', 'Staff Gudang'];

        return view('pages.admin.users.index', compact('users', 'roles'));
    }

    public function userCreate()
    {
        $roles = ['Admin', 'Manajer Gudang', 'Staff Gudang'];
        return view('pages.admin.users.create', compact('roles'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:Admin,Manajer Gudang,Staff Gudang',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function userShow(User $user)
    {
        return view('pages.admin.users.show', compact('user'));
    }

    public function userEdit(User $user)
    {
        $roles = ['Admin', 'Manajer Gudang', 'Staff Gudang'];
        return view('pages.admin.users.edit', compact('user', 'roles'));
    }

    public function userUpdate(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:Admin,Manajer Gudang,Staff Gudang',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate');
    }

    public function userDestroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }

    // Products Management
    public function productList(Request $request)
    {
        $query = Product::with(['category', 'supplier']); // Eager load relasi

        // Kalkulasi Stok yang Efisien menggunakan subquery
        $query->addSelect(['*',
            'stock_in_sum' => StockTransaction::select(DB::raw('COALESCE(sum(quantity), 0)'))
                ->whereColumn('product_id', 'products.id')
                ->where('type', 'Masuk'),
            'stock_out_sum' => StockTransaction::select(DB::raw('COALESCE(sum(quantity), 0)'))
                ->whereColumn('product_id', 'products.id')
                ->where('type', 'Keluar')
        ]);

        if ($request->has('search') && $request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        // Tambahkan filter berdasarkan kategori jika ada
        if ($request->has('category') && $request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // TAMBAHKAN BARIS INI: Ambil semua kategori untuk filter
        $categories = Category::orderBy('name')->get();

        $products = $query->latest()->paginate(10);

        // TAMBAHKAN 'categories' KE COMPACT
        return view('pages.admin.products.index', compact('products', 'categories'));
    }


    public function productCreate()
    {
        $categories = Category::all();
        $suppliers = Supplier::all(); 
        return view('pages.admin.products.create', compact('categories', 'suppliers'));
    }

    public function productStore(Request $request)
    {
        // 1. Validasi semua input yang berasal dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id', // Ganti jadi nullable jika supplier boleh kosong
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'initial_stock' => 'required|integer|min:0', // Validasi stok awal
            'min_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // <-- Validate this
            'is_active' => 'nullable|boolean', // <-- Validate this
            // We will add the 'unit' field to the form next
        ]);

        DB::beginTransaction();
        try {
            // 2. Handle the 'is_active' checkbox
            // If the checkbox is not ticked, it won't be in the request.
            $validatedData['is_active'] = $request->has('is_active');

            // 3. Handle image upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('product_images', 'public');
                $validatedData['image'] = $path;
            }
            
            // 4. Create the product using ONLY the validated data
            $product = Product::create([
                'name' => $validatedData['name'],
                'sku' => $validatedData['sku'],
                'description' => $validatedData['description'],
                'purchase_price' => $validatedData['purchase_price'],
                'selling_price' => $validatedData['selling_price'],
                'current_stock' => $validatedData['initial_stock'], // Set initial stock as current stock
                'min_stock' => $validatedData['min_stock'],
                'image' => $validatedData['image'] ?? null,
                'is_active' => $validatedData['is_active'],
                'category_id' => $validatedData['category_id'],
                'supplier_id' => $validatedData['supplier_id'],
                // 'current_stock' tidak di-set di sini, karena akan dihitung dari transaksi
            ]);

            // 4. Buat transaksi stok awal jika ada
            if ($validatedData['initial_stock'] > 0) {
                StockTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'Masuk',
                    'quantity' => $validatedData['initial_stock'],
                    'notes' => 'Stok awal produk baru.',
                    'date' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Opsional: Log error untuk debugging nanti
            // \Log::error('Gagal menyimpan produk: ' . $e->getMessage());

            // 6. Jika gagal, kembali ke form dengan notifikasi error dan input sebelumnya
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat menyimpan produk. Error: ' . $e->getMessage())
                            ->withInput();
        }
    }

    public function productShow(Product $product)
    {
        $product->load('category', 'supplier', 'stockTransactions');
        return view('pages.admin.products.show', compact('product'));
    }

    public function productEdit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('pages.admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function productUpdate(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diupdate');
    }

    public function productDestroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');
    }

    // Categories Management
    public function categoryList()
    {
        $categories = Category::withCount('products')->paginate(10);
        return view('pages.admin.categories.index', compact('categories'));
    }

    public function categoryCreate()
    {
        return view('pages.admin.categories.create');
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function categoryShow(Category $category)
    {
        $category->load('products');
        return view('pages.admin.categories.show', compact('category'));
    }

    public function categoryEdit(Category $category)
    {
        return view('pages.admin.categories.edit', compact('category'));
    }

    public function categoryUpdate(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function categoryDestroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus');
    }

    // Suppliers Management
    public function supplierList(Request $request) // <-- Tambahkan Request $request
    {
        // Tambahkan logika pencarian
        $query = Supplier::query();
        if ($request->has('search') && $request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $suppliers = $query->latest()->paginate(10);
        return view('pages.admin.suppliers.index', compact('suppliers'));
    }

    public function supplierCreate()
    {
        $title = 'Tambah Supplier Baru';
        $action = route('admin.suppliers.store');
        
        return view('pages.admin.suppliers.create', compact('title', 'action'));
    }

    public function supplierStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:suppliers',
            'address' => 'nullable|string',
        ]);

        Supplier::create($request->all());

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function supplierShow(Supplier $supplier)
    {
        return view('pages.admin.suppliers.show', compact('supplier'));
    }

    public function supplierEdit(Supplier $supplier)
    {
        $title = 'Edit Supplier';
        $action = route('admin.suppliers.update', $supplier->id);
        
        return view('pages.admin.suppliers.edit', compact('supplier', 'title', 'action'));
    }

    public function supplierUpdate(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:suppliers,email,' . $supplier->id,
            'address' => 'nullable|string',
        ]);

        $supplier->update($request->all());

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil diupdate.');
    }

    public function supplierDestroy(Supplier $supplier)
    {
        // Tambahkan pengecekan jika supplier masih memiliki produk
        if ($supplier->products()->count() > 0) {
            return redirect()->route('admin.suppliers.index')->with('error', 'Gagal menghapus! Supplier ini masih memiliki produk terkait.');
        }
        
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
    public function attributeList(Request $request)
    {
        $query = ProductAttribute::with('product'); // Eager load relasi produk

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('value', 'like', "%{$search}%");
        }

        $attributes = $query->paginate(15);

        return view('pages.admin.attributes.index', compact('attributes'));
    }

    // Stock Management
    public function stockHistory(Request $request)
    {
        // Mengambil semua transaksi stok dengan relasi produk dan user
        $query = StockTransaction::with(['product', 'user'])->orderBy('created_at', 'desc');

        // Contoh jika ingin ada filter
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(20);

        return view('pages.admin.stock.history', compact('transactions'));
    }

    public function stockOpname()
    {
        // Untuk saat ini, kita hanya akan menampilkan halaman
        // Logika untuk proses stock opname bisa ditambahkan di sini nanti
        $products = Product::orderBy('name')->get();
        return view('pages.admin.stock.opname', compact('products'));
    }

    // Reports
    public function reportIndex(Request $request)
    {
        // --- Data for Stock Report ---
        $stockQuery = Product::with('category')->withCount('stockTransactions');

        if ($request->has('category_id') && $request->filled('category_id')) {
            $stockQuery->where('category_id', $request->category_id);
        }

        $products = $stockQuery->paginate(10, ['*'], 'productsPage'); // Use a custom page name
        $categories = Category::orderBy('name')->get();


        // --- Data for Transaction Report ---
        $transactionQuery = StockTransaction::with(['product', 'user'])->latest('created_at');

        if ($request->has('type') && $request->filled('type')) {
            $transactionQuery->where('type', $request->type);
        }

        if ($request->has('from') && $request->filled('from')) {
            $transactionQuery->whereDate('created_at', '>=', $request->from);
        }

        if ($request->has('to') && $request->filled('to')) {
            $transactionQuery->whereDate('created_at', '<=', $request->to);
        }
        
        $transactions = $transactionQuery->paginate(10, ['*'], 'transactionsPage'); // Use a custom page name

        // Return the correct view with ALL the necessary data
        return view('pages.admin.reports.index', compact(
            'products', 
            'categories', 
            'transactions'
        ));
    }

    public function reportStock(Request $request)
    {
        // Logika untuk mengambil data laporan stok
        // Bisa sangat mirip dengan halaman daftar produk, tapi dengan lebih banyak detail stok
        $query = Product::with('category')->withCount('transactions');

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(20);
        $categories = Category::all();

        return view('pages.admin.reports.stock', compact('products', 'categories'));
    }

    public function reportTransactions(Request $request)
    {
        // Ini bisa menggunakan method yang sama dengan stockHistory
        // atau versi yang lebih detail khusus untuk laporan
        $query = StockTransaction::with(['product', 'user'])->orderBy('created_at', 'desc');

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        // Anda bisa menambahkan filter lain seperti rentang tanggal

        $transactions = $query->paginate(20);

        return view('pages.admin.reports.transactions', compact('transactions'));
    }

    public function reportUsers()
    {
        $users = User::all();
        return view('pages.admin.reports.users', compact('users'));
    }

    public function reportSystem()
    {
        $systemData = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_suppliers' => Supplier::count(),
            'total_transactions' => StockTransaction::count(),
        ];

        return view('pages.admin.reports.system', compact('systemData'));
    }

    // Settings
    public function settings()
    {
        return view('pages.admin.settings.index');
    }

    public function settingsUpdate(Request $request)
    {
        // Implementasi update settings
        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil diupdate');
    }

    // --- Profile Management ---
    public function profile()
    {
        return view('pages.profile.edit', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
