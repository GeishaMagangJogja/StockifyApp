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

class AdminDashboardController extends Controller
{
    // Dashboard
    public function index()
{
    try {
        // Data untuk Kartu Info
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();

        // Menyiapkan data untuk grafik transaksi 7 hari terakhir
        $chartData = ['categories' => [], 'incoming' => [], 'outgoing' => []];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $chartData['categories'][] = $day->format('d M');

            $chartData['incoming'][] = StockTransaction::where('type', 'Masuk')
                ->whereDate('date', $day->format('Y-m-d'))
                ->sum('quantity');

            $chartData['outgoing'][] = StockTransaction::where('type', 'Keluar')
                ->whereDate('date', $day->format('Y-m-d'))
                ->sum('quantity');
        }

        $lowStockProducts = Product::all()->filter(function ($product) {
            return isset($product->min_stock) && $product->current_stock <= $product->min_stock;
        })->take(5);

        $recentTransactions = StockTransaction::with('product', 'user')
            ->orderBy('date', 'desc')
            ->latest()
            ->limit(5)
            ->get();

        $recentUsers = User::latest()->limit(5)->get();

        return view('pages.admin.dashboard.index', compact(
            'totalProducts',
            'totalSuppliers',
            'totalUsers',
            'totalCategories',
            'chartData',
            'recentTransactions',
            'recentUsers',
            'lowStockProducts'
        ));

    } catch (\Exception $e) {
        // Fallback data when there's an error
        return view('pages.admin.dashboard.index', [
            'totalProducts' => 0,
            'totalSuppliers' => 0,
            'totalUsers' => 0,
            'totalCategories' => 0,
            'chartData' => [
                'categories' => [],
                'incoming' => [],
                'outgoing' => []
            ],
            'recentTransactions' => collect([]), // Empty collection
            'recentUsers' => collect([]),
            'lowStockProducts' => collect([])
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
    $query = Product::with(['category', 'supplier'])
        ->withCount('stockTransactions')
        ->select('products.*');

    // Calculate current stock using subqueries
    $query->addSelect([
        'current_stock' => StockTransaction::selectRaw('COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)')
            ->whereColumn('product_id', 'products.id')
    ]);

    // Search functionality
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    // Category filter
    if ($request->filled('category')) {
        $query->where('category_id', $request->input('category'));
    }

    // Sorting
    $query->orderBy('name');

    $products = $query->paginate(10);
    $categories = Category::orderBy('name')->get();

    return view('pages.admin.products.index', compact('products', 'categories'));
}

public function productCreate()
{
    $categories = Category::orderBy('name')->get();
    $suppliers = Supplier::orderBy('name')->get();
    return view('pages.admin.products.create', compact('categories', 'suppliers'));
}

public function productstore(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:100|unique:products',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'description' => 'nullable|string',
        'purchase_price' => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
        'current_stock' => 'required|integer|min:0',  // was initial_stock
        'minimum_stock' => 'required|integer|min:0',  // was min_stock
        'unit' => 'required|string|max:20',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_active' => 'nullable|boolean',
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('product_images', 'public');
    }

    // Create product
    $product = Product::create($validated);

    // Record initial stock transaction
    if ($validated['current_stock'] > 0) {
        StockTransaction::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => 'Masuk',
            'quantity' => $validated['current_stock'],
            'notes' => 'Stok awal produk',
            'date' => now(),
        ]);
    }

    return redirect()->route('admin.products.index')
           ->with('success', 'Produk berhasil ditambahkan');
}

public function productShow(Product $product)
{
    $product->load(['category', 'supplier', 'stockTransactions' => function($query) {
        $query->orderBy('date', 'desc')->limit(10);
    }]);

    return view('pages.admin.products.show', compact('product'));
}

public function productEdit(Product $product)
{
    $categories = Category::orderBy('name')->get();
    $suppliers = Supplier::orderBy('name')->get();
    return view('pages.admin.products.edit', compact('product', 'categories', 'suppliers'));
}

public function productUpdate(Request $request, Product $product)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:100|unique:products,sku,'.$product->id,
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'description' => 'nullable|string',
        'purchase_price' => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
        'min_stock' => 'required|integer|min:0',
        'unit' => 'required|string|max:20',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    try {
        $validatedData['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $path = $request->file('image')->store('product_images', 'public');
            $validatedData['image'] = $path;
        }

        $product->update($validatedData);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())
            ->withInput();
    }
}

public function productDestroy(Product $product)
{
    DB::beginTransaction();
    try {
        // Delete associated image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete stock transactions
        $product->stockTransactions()->delete();

        // Delete the product
        $product->delete();

        DB::commit();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
    }
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
