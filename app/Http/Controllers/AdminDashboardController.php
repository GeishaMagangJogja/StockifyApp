<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Models\ProductAttribute;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    // Dashboard
    public function index()
    {
        try {
            // Card data
            $totalProducts = Product::count();
            $totalSuppliers = Supplier::count();
            $totalUsers = User::count();
            $totalCategories = Category::count();

            // Chart data for last 7 days
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

            $lowStockProducts = Product::all()
                ->filter(fn($product) => isset($product->min_stock) && $product->current_stock <= $product->min_stock)
                ->take(5);

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
                'recentTransactions' => collect([]),
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
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
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
    $query = Product::query()
        ->with(['category', 'supplier'])
        ->withCount('stockTransactions')
        ->select('products.*');

    // Hitung stok saat ini
    $query->addSelect([
        'current_stock' => StockTransaction::selectRaw('COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)')
            ->whereColumn('product_id', 'products.id')
    ]);

    // Filter pencarian
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Filter kategori
    if ($request->filled('category')) {
        $query->where('category_id', $request->input('category'));
    }

    // Filter status stok
    if ($request->filled('stock_status')) {
        switch ($request->input('stock_status')) {
            case 'out_of_stock':
                $query->having('current_stock', '<=', 0);
                break;
            case 'low_stock':
                $query->having('current_stock', '>', 0)
                      ->having('current_stock', '<=', DB::raw('minimum_stock'));
                break;
            case 'in_stock':
                $query->having('current_stock', '>', DB::raw('minimum_stock'));
                break;
        }
    }

    // Sorting
    $sortOptions = [
        'name_asc' => ['name', 'asc'],
        'name_desc' => ['name', 'desc'],
        'stock_asc' => ['current_stock', 'asc'],
        'stock_desc' => ['current_stock', 'desc'],
        'price_asc' => ['selling_price', 'asc'],
        'price_desc' => ['selling_price', 'desc'],
    ];

    $sort = $request->input('sort', 'name_asc');
    [$sortColumn, $sortDirection] = $sortOptions[$sort] ?? ['name', 'asc'];

    $query->orderBy($sortColumn, $sortDirection);

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

    public function productStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
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
        $product->load(['category', 'supplier', 'stockTransactions' => fn($q) => $q->orderBy('date', 'desc')->limit(10)]);
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
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $validatedData['image'] = $request->file('image')->store('product_images', 'public');
            }

            $product->update($validatedData);

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui produk: '.$e->getMessage())
                ->withInput();
        }
    }

    public function productDestroy(Product $product)
    {
        DB::beginTransaction();
        try {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->stockTransactions()->delete();
            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: '.$e->getMessage());
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
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
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

public function supplierList(Request $request)
{
    $query = Supplier::query()
        ->withCount('products')
        ->latest();

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('contact_person', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $suppliers = $query->paginate(10);

    return view('pages.admin.suppliers.index', compact('suppliers'));
}

public function supplierCreate()
{
    return view('pages.admin.suppliers.create', [
        'title' => 'Tambah Supplier Baru',
        'header' => 'Form Tambah Supplier'
    ]);
}

public function supplierStore(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:suppliers',
        'contact_person' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255|unique:suppliers',
        'address' => 'nullable|string',
    ]);

    try {
        Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan');

    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', 'Gagal menambahkan supplier: '.$e->getMessage());
    }
}

public function supplierShow(Supplier $supplier)
{
    $supplier->load(['products' => function($query) {
        $query->with(['category'])
             ->select('id', 'name', 'sku', 'category_id', 'supplier_id')
             ->withCount('stockTransactions');
    }]);

    // Hitung stok saat ini untuk setiap produk
    $supplier->products->each(function($product) {
        $product->current_stock = $product->stockTransactions()
            ->selectRaw('SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END) as stock')
            ->value('stock') ?? 0;
    });

    return view('pages.admin.suppliers.show', compact('supplier'));
}

public function supplierEdit(Supplier $supplier)
{
    return view('pages.admin.suppliers.edit', compact('supplier'));
}

public function supplierUpdate(Request $request, Supplier $supplier)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:suppliers,name,'.$supplier->id,
        'contact_person' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255|unique:suppliers,email,'.$supplier->id,
        'address' => 'nullable|string',
    ]);

    try {
        $supplier->update($validated);

        return redirect()->route('admin.suppliers.show', $supplier->id)
            ->with('success', 'Data supplier berhasil diperbarui');

    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', 'Gagal memperbarui supplier: '.$e->getMessage());
    }
}

public function supplierDestroy(Supplier $supplier)
{
    DB::beginTransaction();

    try {
        if ($supplier->products()->exists()) {
            return redirect()->route('admin.suppliers.index')
                ->with('error', 'Tidak dapat menghapus supplier karena memiliki produk terkait');
        }

        $supplier->delete();
        DB::commit();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil dihapus');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('admin.suppliers.index')
            ->with('error', 'Gagal menghapus supplier: '.$e->getMessage());
    }
}

public function confirmDelete(Supplier $supplier)
{
    $supplier->loadCount('products');
    return view('pages.admin.suppliers.delete', compact('supplier'));
}
    public function attributeList(Request $request)
    {
        $query = ProductAttribute::with('product');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('value', 'like', "%{$search}%");
        }

        $attributes = $query->paginate(15);
        return view('pages.admin.attributes.index', compact('attributes'));
    }

    // Stock Management
    public function stockHistory(Request $request)
    {
        $query = StockTransaction::with(['product', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('product', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $transactions = $query->paginate(20);
        return view('pages.admin.stock.history', compact('transactions'));
    }

    public function stockOpname()
    {
        $products = Product::orderBy('name')->get();
        return view('pages.admin.stock.opname', compact('products'));
    }

    // Reports
    public function reportIndex(Request $request)
    {
        $stockQuery = Product::with('category')->withCount('stockTransactions');

        if ($request->filled('category_id')) {
            $stockQuery->where('category_id', $request->category_id);
        }

        $products = $stockQuery->paginate(10, ['*'], 'productsPage');
        $categories = Category::orderBy('name')->get();

        $transactionQuery = StockTransaction::with(['product', 'user'])
            ->latest('created_at');

        if ($request->filled('type')) {
            $transactionQuery->where('type', $request->type);
        }

        if ($request->filled('from')) {
            $transactionQuery->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $transactionQuery->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $transactionQuery->paginate(10, ['*'], 'transactionsPage');

        return view('pages.admin.reports.index', compact(
            'products',
            'categories',
            'transactions'
        ));
    }

    public function reportStock(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->where('current_stock', '>', 0)
                        ->whereColumn('current_stock', '<=', 'min_stock');
                    break;
                case 'out':
                    $query->where('current_stock', '<=', 0);
                    break;
                case 'safe':
                    $query->whereColumn('current_stock', '>', 'min_stock');
                    break;
            }
        }

        $stockSummary = [
            'safe' => Product::whereColumn('current_stock', '>', 'min_stock')
                ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
                ->count(),
            'low' => Product::where('current_stock', '>', 0)
                ->whereColumn('current_stock', '<=', 'min_stock')
                ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
                ->count(),
            'out' => Product::where('current_stock', '<=', 0)
                ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
                ->count(),
        ];

        $products = $query->paginate(20);
        $categories = Category::all();

        return view('pages.admin.reports.stock', compact('products', 'categories', 'stockSummary'));
    }

    public function reportTransactions(Request $request)
    {
        $query = StockTransaction::with(['product', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

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
        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil diupdate');
    }

    // Profile Management
    public function profile()
    {
        return view('pages.profile.edit', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
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
