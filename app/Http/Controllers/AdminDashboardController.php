<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    // Dashboard
    public function index()
    {
        try {
            $totalProducts = Product::count() ?? 0;
            $dateFrom = Carbon::now()->subDays(30);

            $incomingCount = StockTransaction::where('type', 'in')
                                 ->where('created_at', '>=', $dateFrom)
                                 ->count() ?? 0;

            $outgoingCount = StockTransaction::where('type', 'out')
                                 ->where('created_at', '>=', $dateFrom)
                                 ->count() ?? 0;

            $chartData = [
                'categories' => [],
                'incoming' => [],
                'outgoing' => []
            ];

            for ($i = 6; $i >= 0; $i--) {
                $day = Carbon::now()->subDays($i);
                $dayFormatted = $day->format('Y-m-d');

                $chartData['categories'][] = $day->format('d M');
                $chartData['incoming'][] = StockTransaction::where('type', 'in')
                    ->whereDate('created_at', $dayFormatted)
                    ->count() ?? 0;
                $chartData['outgoing'][] = StockTransaction::where('type', 'out')
                    ->whereDate('created_at', $dayFormatted)
                    ->count() ?? 0;
            }

            $recentUsers = User::orderBy('updated_at', 'desc')
                              ->limit(5)
                              ->select(['id', 'name', 'email', 'updated_at'])
                              ->get();

            return view('pages.admin.dashboard.index', compact(
                'totalProducts',
                'incomingCount',
                'outgoingCount',
                'chartData',
                'recentUsers'
            ));

        } catch (\Exception $e) {
            return view('pages.admin.dashboard.index', [
                'totalProducts' => 0,
                'incomingCount' => 0,
                'outgoingCount' => 0,
                'chartData' => ['categories' => [], 'incoming' => [], 'outgoing' => []],
                'recentUsers' => collect([])
            ]);
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
        $query = Product::with(['category', 'supplier']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'low_stock') {
                $query->whereRaw('stock <= min_stock');
            } elseif ($request->status == 'out_of_stock') {
                $query->where('stock', 0);
            } elseif ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->paginate(10);
        $categories = Category::all();

        return view('pages.admin.products.index', compact('products', 'categories'));
    }

    public function productCreate()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('pages.admin.products.create', compact('categories', 'suppliers'));
    }

    /**
     * Generate SKU based on product name
     */
    private function generateSKU($productName)
    {
        // Ambil 3 karakter pertama dari nama produk
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $productName), 0, 3));

        // Jika prefix kurang dari 3 karakter, tambahkan 'X'
        $prefix = str_pad($prefix, 3, 'X');

        // Generate nomor random 4 digit
        $number = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);

        $sku = $prefix . '-' . $number;

        // Pastikan SKU unik
        while (Product::where('sku', $sku)->exists()) {
            $number = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $sku = $prefix . '-' . $number;
        }

        return $sku;
    }

    /**
     * API endpoint untuk generate SKU
     */
    public function generateSkuApi(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $sku = $this->generateSKU($request->name);

        return response()->json(['sku' => $sku]);
    }

    // Add this method to check SKU uniqueness
    public function checkSku(Request $request)
    {
        $request->validate([
            'sku' => 'required|string'
        ]);

        $exists = Product::where('sku', $request->sku)->exists();

        return response()->json(['exists' => $exists]);
    }

    // Modify the productStore method to handle the numeric fields and auto-generate SKU
    public function productStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products', // SKU sekarang optional
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string',
            'purchase_price' => 'nullable|string',
            'selling_price' => 'nullable|string',
            'min_stock' => 'required|string',
            'initial_stock' => 'nullable|string',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Clean numeric inputs
        $purchase_price = (int) str_replace('.', '', $request->purchase_price);
        $selling_price = (int) str_replace('.', '', $request->selling_price);
        $min_stock = (int) $request->min_stock;
        $initial_stock = (int) $request->initial_stock;

        // Generate SKU if not provided
        $sku = $request->sku ?: $this->generateSKU($request->name);

        $data = [
            'name' => $request->name,
            'sku' => $sku,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'description' => $request->description,
            'purchase_price' => $purchase_price,
            'selling_price' => $selling_price,
            'min_stock' => $min_stock,
            'stock' => $initial_stock,
            'is_active' => $request->boolean('is_active'),
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function productShow(Product $product)
    {
        $product->load(['category', 'supplier']);
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
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string',
            'purchase_price' => 'nullable|string',
            'selling_price' => 'nullable|string',
            'min_stock' => 'required|string',
            'stock' => 'nullable|string',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        // Clean numeric inputs
        $purchase_price = (int) str_replace('.', '', $request->purchase_price ?? '0');
        $selling_price = (int) str_replace('.', '', $request->selling_price ?? '0');
        $min_stock = (int) str_replace('.', '', $request->min_stock ?? '0');
        $stock = (int) str_replace('.', '', $request->stock ?? '0');

        $data = [
            'name' => $request->name,
            'sku' => $request->sku,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'description' => $request->description,
            'purchase_price' => $purchase_price,
            'selling_price' => $selling_price,
            'min_stock' => $min_stock,
            'stock' => $stock,
            'is_active' => $request->boolean('is_active'),
        ];

        // Handle image removal
        if ($request->remove_image && $product->image) {
            Storage::disk('public')->delete($product->image);
            $data['image'] = null;
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    public function productDestroy(Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

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
    public function supplierList()
    {
        $suppliers = Supplier::paginate(10);
        return view('pages.admin.suppliers.index', compact('suppliers'));
    }

    public function supplierCreate()
    {
        $title = 'Tambah Supplier';
        $action = route('admin.suppliers.store');
        return view('pages.admin.suppliers.create', compact('title', 'action'));
    }

    public function supplierStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
        ]);

        Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')
               ->with('success', 'Supplier berhasil ditambahkan');
    }

    public function supplierEdit(Supplier $supplier)
    {
        $title = 'Edit Supplier';
        $action = route('admin.suppliers.update', $supplier->id);
        return view('pages.admin.suppliers.form', compact('title', 'action', 'supplier'));
    }

    public function supplierUpdate(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
        ]);

        $supplier->update($request->all());

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil diupdate');
    }

    public function supplierDestroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus');
    }

    public function supplierShow(Supplier $supplier)
    {
        return view('pages.admin.suppliers.show', compact('supplier'));
    }

    // Reports
    public function reportIndex()
    {
        return view('pages.admin.reports.index');
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
}
