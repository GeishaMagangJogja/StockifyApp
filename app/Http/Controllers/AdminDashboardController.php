<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends Controller
{
    // Dashboard
    public function index()
    {
        try {
            $totalProducts = Product::count();
            $totalSuppliers = Supplier::count();
            $totalUsers = User::count();
            $totalCategories = Category::count();

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

    // Filter pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Filter role - perbaikan disini
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    $users = $query->latest()->paginate(10);
    $roles = ['Admin', 'Manajer Gudang', 'Staff Gudang']; // Daftar role yang tersedia

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

   // Add this method for showing delete confirmation
public function confirmDeleteUser(User $user)
{
    return view('pages.admin.users.delete', compact('user'));
}

// Update the destroy method
public function userDestroy(User $user)
{
    DB::beginTransaction();
    try {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        // Check if user has any related data (transactions, etc.)
        $hasTransactions = StockTransaction::where('user_id', $user->id)->exists();

        if ($hasTransactions) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus pengguna karena memiliki riwayat transaksi terkait');
        }

        $userName = $user->name;
        $user->delete();

        DB::commit();

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna '{$userName}' berhasil dihapus");
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('admin.users.index')
            ->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
    }
}

    // Products Management
  public function productList(Request $request)
{
    $query = Product::query()
        ->with(['category', 'supplier'])
        ->withCount('stockTransactions');

    // Calculate current stock from transactions
    $query->select('products.*')
        ->addSelect([
            'current_stock' => StockTransaction::selectRaw('COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)')
                ->whereColumn('product_id', 'products.id')
        ]);

    // Search filter
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Category filter
    if ($request->filled('category')) {
        $query->where('category_id', $request->input('category'));
    }

    // Stock status filter
    if ($request->filled('stock_status')) {
        $status = $request->input('stock_status');
        $query->where(function($q) use ($status) {
            switch ($status) {
                case 'out_of_stock':
                    $q->whereRaw('(SELECT COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)
                       FROM stock_transactions
                       WHERE product_id = products.id) <= 0');
                    break;
                case 'low_stock':
                    $q->whereRaw('(SELECT COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)
                       FROM stock_transactions
                       WHERE product_id = products.id) > 0')
                       ->whereRaw('(SELECT COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)
                       FROM stock_transactions
                       WHERE product_id = products.id) <= products.min_stock');
                    break;
                case 'in_stock':
                    $q->whereRaw('(SELECT COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)
                       FROM stock_transactions
                       WHERE product_id = products.id) > products.min_stock');
                    break;
            }
        });
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

    // Handle sorting for calculated fields
    if ($sortColumn === 'current_stock') {
        $query->orderByRaw('(SELECT COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)
                            FROM stock_transactions
                            WHERE product_id = products.id) ' . $sortDirection);
    } else {
        $query->orderBy($sortColumn, $sortDirection);
    }

    $products = $query->paginate(15);

    $categories = Category::orderBy('name')->get(); // Tambahkan baris ini

    $stockStats = [
        'in_stock' => DB::table('products')
            ->selectRaw('COUNT(*) as count')
            ->whereRaw('(SELECT COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)
                       FROM stock_transactions
                       WHERE product_id = products.id) > min_stock')
            ->first()->count,

        'low_stock' => DB::table('products')
            ->selectRaw('COUNT(*) as count')
            ->whereRaw('(SELECT COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)
                       FROM stock_transactions
                       WHERE product_id = products.id) > 0')
            ->whereRaw('(SELECT COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)
                       FROM stock_transactions
                       WHERE product_id = products.id) <= min_stock')
            ->first()->count,

        'out_of_stock' => DB::table('products')
            ->selectRaw('COUNT(*) as count')
            ->whereRaw('(SELECT COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)
                       FROM stock_transactions
                       WHERE product_id = products.id) <= 0')
            ->first()->count,
    ];

    return view('pages.admin.products.index', compact('products', 'categories', 'stockStats'));
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
            'current_stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('product_images', 'public');
        }

        $product = Product::create($validated);

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
    $product->load(['category', 'supplier']);
    $product->load(['stockTransactions' => function($q) {
        $q->orderBy('date', 'desc')->with('user')->paginate(10);
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
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0|gte:purchase_price',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        try {
            $validatedData['purchase_price'] = str_replace('.', '', $validatedData['purchase_price']);
            $validatedData['selling_price'] = str_replace('.', '', $validatedData['selling_price']);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $validatedData['image'] = $request->file('image')->store('product_images', 'public');
            } elseif ($request->input('remove_image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $validatedData['image'] = null;
            } else {
                unset($validatedData['image']);
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

    public function confirmDeleteProduct(Product $product)
    {
        $product->load(['category', 'supplier']);
        $product->stockTransactionsCount = $product->stockTransactions()->count();
        return view('pages.admin.products.delete', compact('product'));
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            Log::info("Attempting to delete product: {$product->id} - {$product->name}");

            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
                Log::info("Deleted product image: {$product->image}");
            }

            $deletedTransactions = $product->stockTransactions()->delete();
            Log::info("Deleted {$deletedTransactions} stock transactions for product: {$product->id}");

            if (method_exists($product, 'attributes')) {
                $deletedAttributes = $product->attributes()->delete();
                Log::info("Deleted {$deletedAttributes} product attributes for product: {$product->id}");
            }

            $productName = $product->name;
            $productId = $product->id;

            $deleted = $product->delete();

            if (!$deleted) {
                throw new \Exception("Failed to delete product from database");
            }

            Log::info("Successfully deleted product: {$productId} - {$productName}");

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', "Produk '{$productName}' berhasil dihapus beserta semua data terkait");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete product {$product->id}: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            return redirect()->route('admin.products.index')
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function forceDestroy(Product $product)
    {
        DB::beginTransaction();
        try {
            $productId = $product->id;
            $productName = $product->name;

            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('stock_transactions')->where('product_id', $productId)->delete();

            if (Schema::hasTable('product_attributes')) {
                DB::table('product_attributes')->where('product_id', $productId)->delete();
            }

            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            DB::table('products')->where('id', $productId)->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', "Produk '{$productName}' berhasil dihapus");
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            return redirect()->route('admin.products.index')
                ->with('error', 'Gagal menghapus produk secara paksa: ' . $e->getMessage());
        }
    }

    // Categories Management
    public function categoryList(Request $request)
    {
        $query = Category::withCount('products');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->paginate(10);

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
        $category->load(['products' => function($query) {
            $query->select('id', 'name', 'sku', 'current_stock', 'min_stock', 'unit', 'category_id');
        }]);

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


    public function confirmDeleteCategory(Category $category)
{
    $category->loadCount('products');
    return view('pages.admin.categories.delete', compact('category'));
}
  public function categoryDestroy(Category $category)
{
    DB::beginTransaction();
    try {
        // Check if category has products
        if ($category->products()->exists()) {
            // Move products to uncategorized (assuming you have a default category)
            $uncategorized = Category::firstOrCreate(
                ['name' => 'Tidak Berkategori'],
                ['description' => 'Produk tanpa kategori']
            );

            $category->products()->update(['category_id' => $uncategorized->id]);
        }

        $categoryName = $category->name;
        $category->delete();

        DB::commit();

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$categoryName}' berhasil dihapus" .
                   ($category->products_count > 0 ? ' dan produk terkait dipindahkan ke Tidak Berkategori' : ''));
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('admin.categories.index')
            ->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
    }
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

    // Add this line to calculate total products from all suppliers
    $totalProductsFromSuppliers = Supplier::withCount('products')->get()->sum('products_count');

    return view('pages.admin.suppliers.index', compact('suppliers', 'totalProductsFromSuppliers'));
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

    public function confirmDeleteSupplier(Supplier $supplier)
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
