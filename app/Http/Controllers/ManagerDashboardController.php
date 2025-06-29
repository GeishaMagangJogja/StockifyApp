<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\StockTransaction;
use App\Models\User;

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

        // Mengambil 5 produk dengan stok terendah
        $lowStockProducts = Product::all()->filter(function ($product) {
            return $product->current_stock <= $product->min_stock;
        })->sortBy('current_stock')->take(5);

        // Menyiapkan data untuk grafik transaksi 7 hari terakhir
        $chartData = ['categories' => [], 'incoming' => [], 'outgoing' => []];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $chartData['categories'][] = $day->format('d M');
            
            // PERBAIKAN: Gunakan kolom 'date' bukan 'created_at'
            $chartData['incoming'][] = StockTransaction::where('type', 'Masuk')
                ->whereDate('date', $day->format('Y-m-d'))
                ->sum('quantity');
            
            // PERBAIKAN: Gunakan kolom 'date' bukan 'created_at'
            $chartData['outgoing'][] = StockTransaction::where('type', 'Keluar')
                ->whereDate('date', $day->format('Y-m-d'))
                ->sum('quantity');
        }

        // PERBAIKAN: Gunakan kolom 'date' bukan 'created_at'
        $incomingTodayCount = StockTransaction::where('type', 'Masuk')
            ->whereDate('date', today())
            ->count();
            
        // PERBAIKAN: Gunakan kolom 'date' bukan 'created_at'
        $outgoingTodayCount = StockTransaction::where('type', 'Keluar')
            ->whereDate('date', today())
            ->count();

        // Mengurutkan berdasarkan kolom 'date' agar lebih relevan
        $recentTransactions = StockTransaction::with('product', 'user')
            ->orderBy('date', 'desc')
            ->latest() // latest() akan mengurutkan berdasarkan created_at, kita tambahkan urutan date
            ->limit(5)
            ->get();
            
        return view('pages.manajergudang.dashboard.index', compact(
            'totalProducts', 'totalSuppliers', 'lowStockProducts', 'incomingTodayCount', 'outgoingTodayCount', 'recentTransactions', 'chartData'
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
        $products = Product::with('stockTransactions')->orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get(['id', 'name']);
        return view('pages.manajergudang.stock.in', compact('products', 'suppliers'));
    }

    public function stockInStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        StockTransaction::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(), // Mengambil ID user yang sedang login
            'supplier_id' => $request->supplier_id,
            'type' => 'Masuk', 
            'quantity' => $request->quantity,
            'notes' => $request->notes,
            'date' => $request->transaction_date, 
            'status' => 'Diterima',
        ]);

        return redirect()->route('manajergudang.dashboard')->with('success', 'Transaksi barang masuk berhasil dicatat.');
    }

    public function stockOut()
    {
        $products = Product::with('stockTransactions')->orderBy('name')->get();
        return view('pages.manajergudang.stock.out', compact('products'));
    }

    public function stockOutStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        if ($request->quantity > $product->current_stock) {
            return back()->withErrors(['quantity' => 'Jumlah barang keluar tidak boleh melebihi stok yang ada (Stok saat ini: ' . $product->current_stock . ').'])->withInput();
        }

        StockTransaction::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'type' => 'Keluar',
            'quantity' => $request->quantity,
            'notes' => $request->notes,
            'date' => $request->transaction_date, 
            'status' => 'Dikeluarkan',
        ]);

        return redirect()->route('manajergudang.dashboard')->with('success', 'Transaksi barang keluar berhasil dicatat.');
    }
    
    public function stockOpname()
    {
        $products = Product::with('stockTransactions')->orderBy('name')->paginate(20);
        return view('pages.manajergudang.stock.opname', compact('products'));
    }

    public function stockOpnameStore(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.system_stock' => 'required|integer',
            'products.*.physical_stock' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->products as $item) {
                // Konversi ke integer untuk memastikan perhitungan yang aman
                $systemStock = (int)$item['system_stock'];
                $physicalStock = (int)$item['physical_stock'];
                $difference = $physicalStock - $systemStock;

                // Hanya buat transaksi jika ada selisih
                if ($difference != 0) {
                    StockTransaction::create([
                        'product_id' => $item['id'],
                        'user_id' => Auth::id(),
                        // PERBAIKAN 1: Gunakan nilai ENUM yang benar
                        'type' => $difference > 0 ? 'Masuk' : 'Keluar', 
                        'quantity' => abs($difference),
                        'notes' => 'Penyesuaian Stok Opname', // Catatan yang lebih deskriptif
                        // PERBAIKAN 2: Tambahkan field 'date'
                        'date' => now(), // Gunakan tanggal hari ini untuk stock opname
                        // PERBAIKAN 3: Tambahkan field 'status'
                        'status' => $difference > 0 ? 'Diterima' : 'Dikeluarkan', 
                    ]);
                }
            }
        });

        return redirect()->route('manajergudang.stock.opname')->with('success', 'Stock opname berhasil disimpan dan stok telah disesuaikan.');
    }
    
    // ... (supplier dan report methods) ...
    public function supplierList()
    {
        $suppliers = Supplier::latest()->paginate(15);
        return view('pages.manajergudang.suppliers.index', compact('suppliers'));
    }
    
    public function reportStock()
    {
        $categories = Category::orderBy('name')->get();
        return view('pages.manajergudang.reports.stock', compact('categories'));
    }

    public function reportTransactions()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('pages.manajergudang.reports.transactions', compact('categories', 'suppliers'));
    }

}