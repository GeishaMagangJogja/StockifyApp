<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\StockTransaction;

class ManagerDashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk Manajer Gudang.
     * Logika stok direvisi untuk menghitung stok secara dinamis.
     */
    public function index()
    {
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        // 1. Ambil semua produk beserta stok minimumnya.
        $allProducts = Product::all();

        // 2. Hitung stok saat ini untuk setiap produk dan filter yang menipis.
        $lowStockProducts = $allProducts->filter(function ($product) {
            // Hitung total barang masuk
            $totalIn = StockTransaction::where('product_id', $product->id)->where('type', 'in')->sum('quantity');
            // Hitung total barang keluar
            $totalOut = StockTransaction::where('product_id', $product->id)->where('type', 'out')->sum('quantity');
            
            // Stok saat ini adalah selisihnya
            $currentStock = $totalIn - $totalOut;

            // Kembalikan true jika stok saat ini <= stok minimum
            return $currentStock <= $product->minimum_stock;
        })->take(5); // Ambil 5 produk teratas yang stoknya menipis


        // 2. Jumlah barang masuk hari ini (Ini tidak berubah)
        $incomingTodayCount = StockTransaction::where('type', 'in')
            ->whereDate('created_at', today())
            ->count();
            
        // 3. Jumlah barang keluar hari ini (Ini tidak berubah)
        $outgoingTodayCount = StockTransaction::where('type', 'out')
            ->whereDate('created_at', today())
            ->count();

        // 4. Transaksi terakhir untuk ditampilkan di dashboard (Ini tidak berubah)
        $recentTransactions = StockTransaction::with('product', 'user')
            ->latest()
            ->limit(5)
            ->get();
            
        return view('pages.manajergudang.dashboard.index', compact(
            'totalProducts',
            'totalSuppliers',
            'lowStockProducts',
            'incomingTodayCount',
            'outgoingTodayCount',
            'recentTransactions'
        ));
    }

    // ... method-method lain (productList, productShow, dll) tidak perlu diubah ...
    // ... karena mereka tidak menggunakan 'current_stock' secara langsung.  ...
    // ... Pastikan Anda menyesuaikan view-nya nanti agar tidak menampilkan 'current_stock' ...
    // ... tapi menampilkan stok yang dihitung secara dinamis jika diperlukan.   ...
    
    // Sisa method dari controller sebelumnya tetap sama...
    
    public function productList()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('pages.manajergudang.products.index', compact('products'));
    }

    public function productShow(Product $product)
    {
        $transactions = StockTransaction::where('product_id', $product->id)
            ->latest()
            ->paginate(10);
        return view('pages.manajergudang.products.show', compact('product', 'transactions'));
    }

    public function stockIn()
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        $suppliers = Supplier::orderBy('name')->get(['id', 'name']);
        return view('pages.manajergudang.stock.in', compact('products', 'suppliers'));
    }

    public function stockOut()
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        return view('pages.manajergudang.stock.out', compact('products'));
    }
    
    public function stockOpname()
    {
        $products = Product::orderBy('name')->paginate(20);
        return view('pages.manajergudang.stock.opname', compact('products'));
    }

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