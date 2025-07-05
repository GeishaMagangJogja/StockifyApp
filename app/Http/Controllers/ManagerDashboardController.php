<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\StockTransaction;
use App\Models\User;
use Carbon\Carbon;

class ManagerDashboardController extends Controller
{
    /**
     * Menghitung stok saat ini untuk sebuah produk.
     * Ini adalah helper function agar tidak mengulang kode.
     */

    /**
     * Menampilkan halaman dashboard untuk Manajer Gudang.
     */
   public function index()
{
    $totalProducts = Product::count();
    $totalSuppliers = Supplier::count();

    $lowStockProducts = Product::all()->filter(function ($product) {
        return isset($product->min_stock) && $product->current_stock <= $product->min_stock;
    })->sortBy('current_stock')->take(5);

    // Chart data dengan whereBetween
    $chartData = ['categories' => [], 'incoming' => [], 'outgoing' => []];
    for ($i = 6; $i >= 0; $i--) {
        $day = now()->subDays($i);
        $chartData['categories'][] = $day->format('d M');

        $chartData['incoming'][] = StockTransaction::where('type', 'Masuk')
            ->whereBetween('date', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
            ->sum('quantity');

        $chartData['outgoing'][] = StockTransaction::where('type', 'Keluar')
            ->whereBetween('date', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
            ->sum('quantity');
    }

    // Hitungan hari ini dengan whereBetween
    $incomingTodayCount = StockTransaction::where('type', 'Masuk')
        ->whereBetween('date', [now()->startOfDay(), now()->endOfDay()])
        ->count();

    $outgoingTodayCount = StockTransaction::where('type', 'Keluar')
        ->whereBetween('date', [now()->startOfDay(), now()->endOfDay()])
        ->count();

    $recentTransactions = StockTransaction::with('product', 'user')
        ->orderBy('date', 'desc')
        ->limit(5)
        ->get();

    $recentSuppliers = Supplier::latest()->limit(5)->get();

    return view('pages.manajergudang.dashboard.index', compact(
        'totalProducts', 'totalSuppliers', 'lowStockProducts',
        'incomingTodayCount', 'outgoingTodayCount', 'recentTransactions',
        'chartData', 'recentSuppliers'
    ));
}


    public function productList(Request $request)
    {
        // [BARU] Logika untuk pengurutan
        $sortableColumns = ['name', 'category_name', 'current_stock', 'purchase_price', 'selling_price'];
        $sortBy = in_array($request->query('sort_by'), $sortableColumns) ? $request->query('sort_by') : 'name';
        $sortDirection = in_array($request->query('direction'), ['asc', 'desc']) ? $request->query('direction') : 'asc';

        $query = Product::with(['category']);

        // Filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // [BARU] Terapkan pengurutan
        if ($sortBy === 'category_name') {
            $query->select('products.*')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->orderBy('categories.name', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $products = $query->paginate(15)->withQueryString();

        // Statistik untuk kartu
        $stockStats = [
            'total' => Product::count(),
            'safe' => Product::whereColumn('current_stock', '>', 'min_stock')->count(),
            'low' => Product::where('current_stock', '>', 0)->whereColumn('current_stock', '<=', 'min_stock')->count(),
            'out_of_stock' => Product::where('current_stock', '<=', 0)->count(),
        ];

        return view('pages.manajergudang.products.index', compact('products', 'stockStats', 'sortBy', 'sortDirection'));
    }

    public function productShow(Product $product)
    {
        // Load relasi yang dibutuhkan
        $product->load(['category', 'supplier', 'stockTransactions' => function ($query) {
            $query->with('user')->latest('date')->take(10); // Ambil 10 transaksi terakhir
        }]);

        return view('pages.manajergudang.products.show', compact('product'));
    }


    // === Fungsionalitas Manajemen Stok ===

    public function stockIn()
    {
        // [PERBAIKAN KECIL] Gunakan query yang lebih efisien untuk mengambil stok
        $products = Product::orderBy('name')->get();
        // Load relasi stockTransactions untuk setiap produk jika belum di-load
        $products->load('stockTransactions');

        $suppliers = Supplier::orderBy('name')->get(['id', 'name']);
        return view('pages.manajergudang.stock.in', compact('products', 'suppliers'));
    }

    public function stockInStore(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            StockTransaction::create([
                'product_id' => $validatedData['product_id'],
                'user_id' => Auth::id(), // User yang mengajukan (Manajer)
                'type' => StockTransaction::TYPE_MASUK,
                'supplier_id' => $validatedData['supplier_id'],
                'quantity' => $validatedData['quantity'],
                'notes' => $validatedData['notes'],
                'date' => Carbon::parse($validatedData['transaction_date'])->setTimeFrom(now()),
                'status' => StockTransaction::STATUS_PENDING,

            ]);

            return redirect()->route('manajergudang.dashboard')
                ->with('success', 'Permintaan barang masuk berhasil dibuat dan menunggu konfirmasi Staff.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function stockOut()
    {
        // Sama seperti stockIn, gunakan query yang lebih efisien
        $products = Product::orderBy('name')->get();
        $products->load('stockTransactions');
        return view('pages.manajergudang.stock.out', compact('products'));
    }

    public function stockOutStore(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            StockTransaction::create([
                'product_id' => $validatedData['product_id'],
                'user_id' => Auth::id(), // User yang mengajukan (Manajer)
                'type' => StockTransaction::TYPE_KELUAR,
                'quantity' => $validatedData['quantity'],
                'notes' => $validatedData['notes'],
                'date' => Carbon::parse($validatedData['transaction_date'])->setTimeFrom(now()),
                'status' => StockTransaction::STATUS_PENDING,
            ]);

            return redirect()->route('manajergudang.dashboard')
                ->with('success', 'Permintaan barang keluar berhasil dibuat dan menunggu konfirmasi Staff.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function stockOpname(Request $request)
    {
        $query = Product::with('supplier')->orderBy('name');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(15);
        $suppliers = Supplier::orderBy('name')->get();
        $categories = Category::orderBy('name')->get(); // For the filter

        return view('pages.manajergudang.stock.opname', compact('products', 'suppliers', 'categories'));
    }

    public function stockOpnameStore(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.system_stock' => 'required|integer',
            'products.*.physical_stock' => 'required|integer|min:0',
            'products.*.supplier_id' => 'nullable|exists:suppliers,id', // [BARU] Validasi supplier_id
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['products'] as $item) {
                $systemStock = (int)$item['system_stock'];
                $physicalStock = (int)$item['physical_stock'];

                if ($physicalStock !== $systemStock) {
                    $product = Product::findOrFail($item['id']);
                    $difference = $physicalStock - $systemStock;

                    StockTransaction::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'supplier_id' => $item['supplier_id'] ?? null, // [BARU] Simpan supplier_id
                        'type' => $difference > 0 ? 'Masuk' : 'Keluar',
                        'quantity' => abs($difference),
                        'notes' => 'Penyesuaian Stock Opname',
                        'date' => now(),
                        'status' => $difference > 0 ? 'Diterima' : 'Dikeluarkan',
                    ]);

                    $product->update(['current_stock' => $physicalStock]);
                }
            }
        });

        return redirect()->route('manajergudang.stock.opname')->with('success', 'Stock opname berhasil disimpan dan stok telah disesuaikan.');
    }

    public function supplierList(Request $request)
    {
        // [BARU] Logika untuk pengurutan
        $sortableColumns = ['name', 'products_count', 'created_at'];
        $sortBy = in_array($request->query('sort_by'), $sortableColumns) ? $request->query('sort_by') : 'name';
        $sortDirection = in_array($request->query('direction'), ['asc', 'desc']) ? $request->query('direction') : 'asc';

        $query = Supplier::withCount('products'); // withCount sudah menambahkan 'products_count'

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // [BARU] Terapkan pengurutan
        $query->orderBy($sortBy, $sortDirection);

        $suppliers = $query->paginate(15)->withQueryString();

        // Statistik untuk kartu
        $totalSuppliers = Supplier::count();
        $totalProductsFromSuppliers = Product::whereNotNull('supplier_id')->count();

        $stats = [
            'total_suppliers' => $totalSuppliers,
            'total_products_from_suppliers' => $totalProductsFromSuppliers,
            // Hindari pembagian dengan nol
            'avg_products_per_supplier' => $totalSuppliers > 0 ? round($totalProductsFromSuppliers / $totalSuppliers, 1) : 0,
        ];

        // Kirim variabel sort ke view
        return view('pages.manajergudang.suppliers.index', compact('suppliers', 'stats', 'sortBy', 'sortDirection'));
    }

    public function supplierShow(Supplier $supplier)
    {
        // Load relasi produk
        $supplier->load('products.category');

        // [BARU] Hitung statistik spesifik untuk supplier ini
        $supplierStats = [
            'total_products' => $supplier->products->count(),
            'total_units_supplied' => \App\Models\StockTransaction::where('type', 'Masuk')
                                    ->where('supplier_id', $supplier->id)
                                    ->sum('quantity'),
            'last_transaction_date' => \App\Models\StockTransaction::where('supplier_id', $supplier->id)
                                    ->latest('date')
                                    ->first()?->date,
        ];

        return view('pages.manajergudang.suppliers.show', compact('supplier', 'supplierStats'));
    }

        // app/Http/Controllers/ManagerDashboardController.php

    public function reportStock(Request $request)
    {
        // Logika untuk pengurutan
        $sortableColumns = ['name', 'category_name', 'current_stock', 'stock_status', 'stock_value'];
        $sortBy = in_array($request->query('sort_by'), $sortableColumns) ? $request->query('sort_by') : 'stock_value';
        $sortDirection = in_array($request->query('direction'), ['asc', 'desc']) ? $request->query('direction') : 'desc';

        $query = Product::with('category')
            ->select('products.*')
            ->addSelect(DB::raw('(current_stock * purchase_price) as stock_value'))
            ->addSelect(DB::raw('
                CASE
                    WHEN current_stock <= 0 THEN "out_of_stock"
                    WHEN current_stock > 0 AND current_stock <= min_stock THEN "low_stock"
                    ELSE "safe"
                END as stock_status_calculated
            '));

        // Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('products.name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%"));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('stock_status')) {
            $status = $request->stock_status;
            if ($status == 'safe') $query->having('stock_status_calculated', '=', 'safe');
            elseif ($status == 'low') $query->having('stock_status_calculated', '=', 'low_stock');
            elseif ($status == 'out') $query->having('stock_status_calculated', '=', 'out_of_stock');
        }

        // Terapkan pengurutan
        if ($sortBy === 'category_name') {
            $query->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->orderBy('categories.name', $sortDirection);
        } elseif ($sortBy === 'stock_status') {
            $query->orderBy('stock_status_calculated', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $products = $query->paginate(15)->withQueryString();

        // [PERBAIKAN] Definisikan variabel $categories di sini
        $categories = \App\Models\Category::orderBy('name')->get();

        // Hitung statistik valuasi total
        $totalStockValue = Product::sum(DB::raw('current_stock * purchase_price'));

        // Statistik untuk kartu
        $stockSummary = [
            'total' => Product::count(),
            'safe' => Product::whereColumn('current_stock', '>', 'min_stock')->count(),
            'low' => Product::where('current_stock', '>', 0)->whereColumn('current_stock', '<=', 'min_stock')->count(),
            'out' => Product::where('current_stock', '<=', 0)->count(),
            'total_value' => $totalStockValue,
        ];

        return view('pages.manajergudang.reports.stock', compact('products', 'categories', 'stockSummary', 'sortBy', 'sortDirection'));
    }

    public function reportTransactions(Request $request)
    {
        // Logika untuk pengurutan
        $sortableColumns = ['date', 'product_name', 'quantity', 'user_name'];
        $sortBy = in_array($request->query('sort_by'), $sortableColumns) ? $request->query('sort_by') : 'date';
        $sortDirection = in_array($request->query('direction'), ['asc', 'desc']) ? $request->query('direction') : 'desc'; // Default descending untuk tanggal

        // [PERBAIKAN] Hapus ->latest('date') dari sini. Kita akan tambahkan orderBy di akhir.
        $query = StockTransaction::with(['product', 'user', 'supplier']);

        // [PERBAIKAN] Tambahkan select untuk menghindari ambiguitas kolom 'id' setelah join
        $query->select('stock_transactions.*');

        // Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('product', fn($pq) => $pq->where('name', 'like', "%{$search}%"))
                ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"))
                ->orWhereHas('supplier', fn($sq) => $sq->where('name', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Ambil data statistik sebelum paginasi dan sorting
        $statsQuery = clone $query;
        $stats = [
            'total' => $statsQuery->count(),
            'incoming' => (clone $statsQuery)->where('type', 'Masuk')->count(),
            'outgoing' => (clone $statsQuery)->where('type', 'Keluar')->count(),
            'total_quantity' => (clone $statsQuery)->sum('quantity')
        ];

        // [PERBAIKAN] Terapkan pengurutan di sini, setelah semua filter
        switch ($sortBy) {
            case 'product_name':
                $query->leftJoin('products', 'stock_transactions.product_id', '=', 'products.id')
                    ->orderBy('products.name', $sortDirection);
                break;
            case 'user_name':
                $query->leftJoin('users', 'stock_transactions.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sortDirection);
                break;
            default:
                // Urutkan berdasarkan kolom dari tabel stock_transactions
                $query->orderBy($sortBy, $sortDirection);
        }

        // Paginate
        $transactions = $query->paginate(15)->withQueryString();

        // Kirim data ke view
        return view('pages.manajergudang.reports.transactions', compact('transactions', 'stats', 'sortBy', 'sortDirection'));
    }

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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        return redirect()->route('manajergudang.profile')->with('success', 'Profil berhasil diperbarui.');
    }

}
