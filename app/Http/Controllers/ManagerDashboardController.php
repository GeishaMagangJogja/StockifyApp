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


    // ... (productList dan productShow tidak perlu diubah) ...
    public function productList(Request $request)
    {
        $query = Product::with('category');

        // Salin query efisien dari AdminDashboardController
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

        $products = $query->latest()->paginate(15);
        return view('pages.manajergudang.products.index', compact('products'));
    }

    public function productShow(Product $product)
    {
        $product->load('category', 'supplier', 'stockTransactions');
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
            DB::transaction(function () use ($validatedData) {
                // 1. Cari produk yang akan diupdate
                $product = Product::findOrFail($validatedData['product_id']);

                // 2. Buat catatan transaksi
                StockTransaction::create([
                    'product_id' => $validatedData['product_id'],
                    'user_id' => Auth::id(),
                    'supplier_id' => $validatedData['supplier_id'],
                    'type' => 'Masuk',
                    'quantity' => $validatedData['quantity'],
                    'notes' => $validatedData['notes'],
                    'date' => Carbon::parse($validatedData['transaction_date'])->setTimeFrom(now()),
                    'status' => 'Diterima',
                ]);

                // 3. [FIX] Update stok di tabel products menggunakan increment
                $product->increment('current_stock', $validatedData['quantity']);
            });

            return redirect()->route('manajergudang.dashboard')->with('success', 'Transaksi barang masuk berhasil dicatat.');

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
            DB::transaction(function () use ($validatedData) {
                // 1. Cari produk
                $product = Product::findOrFail($validatedData['product_id']);

                // 2. Validasi stok sebelum melanjutkan
                if ($validatedData['quantity'] > $product->current_stock) {
                    // Melemparkan exception akan otomatis me-rollback transaksi DB
                    throw new \Exception('Stok tidak mencukupi. Stok tersedia: ' . $product->current_stock);
                }

                // 3. Buat catatan transaksi keluar
                StockTransaction::create([
                    'product_id' => $validatedData['product_id'],
                    'user_id' => Auth::id(),
                    'type' => 'Keluar',
                    'quantity' => $validatedData['quantity'],
                    'notes' => $validatedData['notes'],
                    'date' => Carbon::parse($validatedData['transaction_date'])->setTimeFrom(now()),
                    'status' => 'Dikeluarkan',
                ]);

                // 4. [FIX] Update stok di tabel products menggunakan decrement
                $product->decrement('current_stock', $validatedData['quantity']);
            });

            return redirect()->route('manajergudang.dashboard')->with('success', 'Transaksi barang keluar berhasil dicatat.');
        
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function stockOpname()
    {
        $products = Product::with('stockTransactions')->orderBy('name')->paginate(20);
        return view('pages.manajergudang.stock.opname', compact('products'));
    }

    public function stockOpnameStore(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.system_stock' => 'required|integer',
            'products.*.physical_stock' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['products'] as $item) {
                $systemStock = (int)$item['system_stock'];
                $physicalStock = (int)$item['physical_stock'];
                
                // Lanjutkan hanya jika ada perbedaan stok
                if ($physicalStock !== $systemStock) {
                    $product = Product::findOrFail($item['id']);
                    $difference = $physicalStock - $systemStock;

                    // Buat SATU transaksi penyesuaian
                    StockTransaction::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        // Tipe transaksi tergantung selisih
                        'type' => $difference > 0 ? 'Masuk' : 'Keluar', 
                        // Quantity adalah nilai absolut dari selisih
                        'quantity' => abs($difference), 
                        'notes' => 'Penyesuaian Stock Opname',
                        'date' => now(),
                        'status' => $difference > 0 ? 'Diterima' : 'Dikeluarkan',
                    ]);
                    
                    // [FIX UTAMA] Update kolom current_stock di tabel products
                    $product->update(['current_stock' => $physicalStock]);
                }
            }
        });

        return redirect()->route('manajergudang.stock.opname')->with('success', 'Stock opname berhasil disimpan dan stok telah disesuaikan.');
    }

    // ... (supplier dan report methods) ...
    public function supplierList(Request $request)
    {
        $query = Supplier::withCount('products');

        if ($request->has('search') && $request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        }

        $suppliers = $query->latest()->paginate(15);
        return view('pages.manajergudang.suppliers.index', compact('suppliers'));
    }

    public function supplierShow(Supplier $supplier)
    {
        // Load relasi produk untuk menampilkan statistik
        $supplier->load('products');
        return view('pages.manajergudang.suppliers.show', compact('supplier'));
    }

    public function reportStock(Request $request)
    {
        // Query dasar untuk mengambil produk dengan kalkulasi stok
        $query = Product::with('category');

        $query->addSelect(['*',
            'stock_in_sum' => StockTransaction::select(DB::raw('COALESCE(sum(quantity), 0)'))
                ->whereColumn('product_id', 'products.id')
                ->where('type', 'Masuk'),
            'stock_out_sum' => StockTransaction::select(DB::raw('COALESCE(sum(quantity), 0)'))
                ->whereColumn('product_id', 'products.id')
                ->where('type', 'Keluar')
        ]);

        // Filter berdasarkan kategori jika ada
        if ($request->has('category_id') && $request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(20);
        $categories = Category::orderBy('name')->get();

        return view('pages.manajergudang.reports.stock', compact('products', 'categories'));
    }

    public function reportTransactions(Request $request)
    {
        $query = StockTransaction::with(['product', 'user', 'supplier'])->latest('date');

        // Filter berdasarkan tipe transaksi
        if ($request->has('type') && $request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->has('start_date') && $request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $transactions = $query->paginate(20);

        return view('pages.manajergudang.reports.transactions', compact('transactions'));
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