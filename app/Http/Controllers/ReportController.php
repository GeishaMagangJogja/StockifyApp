<?php

namespace App\Http\Controllers;

// MODEL & UTILITIES
use App\Models\Product;
use App\Models\Category;
use App\Models\StockTransaction;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;

// DEPENDENSI UNTUK EXPORT
use App\Exports\IncomingReportExport;
use App\Exports\OutgoingReportExport; // <-- PERUBAHAN 1: Tambahkan ini
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display the main report navigation hub.
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        $products = Product::with('category')
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->paginate(10);

        // Asumsi path view untuk index utama
        return view('pages.admin.reports.index', compact('categories', 'products'));
    }

    /**
     * Display the stock report.
     */
    public function stock(Request $request)
    {
        $query = Product::with('category')->withCount('stockTransactions');

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by stock status
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

        $products = $query->latest()->paginate(20);
        $categories = Category::orderBy('name')->get();

        // Stock summary
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

        return view('pages.admin.reports.stock', compact('products', 'categories', 'stockSummary'));
    }

    /**
     * Display the transactions report (Umum).
     */
 public function transactions(Request $request)
{
    // Get products for the filter dropdown
    $products = Product::orderBy('name')->get(['id', 'name', 'sku']);

    $query = StockTransaction::with(['product', 'user'])
        ->orderBy('date', 'desc');

    // Filter by transaction type - perbaikan konsistensi penamaan
    if ($request->filled('type')) {
        $query->where('type', ucfirst($request->type)); // Pastikan format konsisten
    }

    // Filter by product
    if ($request->filled('product_id')) {
        $query->where('product_id', $request->product_id);
    }

    // Filter by date range
    if ($request->filled('from')) {
        $query->whereDate('date', '>=', $request->from);
    }

    if ($request->filled('to')) {
        $query->whereDate('date', '<=', $request->to);
    }

    // Calculate totals for summary
    $totalIncoming = (clone $query)->where('type', 'Masuk')->sum('quantity');
    $totalOutgoing = (clone $query)->where('type', 'Keluar')->sum('quantity');

    $transactions = $query->paginate(20);

    return view('pages.admin.reports.transactions', compact(
        'transactions',
        'totalIncoming',
        'totalOutgoing',
        'products'
    ));
}

    // ===================================================================
    // == METHOD BARU: LAPORAN BARANG KELUAR ==
    // ===================================================================

    /**
     * Display the outgoing goods transaction report.
     * Menampilkan laporan transaksi barang keluar.
     */
    public function outgoingReport(Request $request)
    {
        // 1. Query Dasar: Hanya ambil transaksi tipe 'outgoing'
        // Eager load relasi 'product' dan 'user' untuk performa
        $query = StockTransaction::with(['product', 'user'])
                                  ->where('type', 'outgoing');

        // 2. Terapkan Filter dari Request

        // Filter Pencarian (Cari berdasarkan nama produk, SKU, atau catatan/tujuan)
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($sub) use ($search) {
                $sub->where('notes', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($prod) use ($search) {
                        $prod->where('name', 'like', "%{$search}%")
                             ->orWhere('sku', 'like', "%{$search}%");
                    });
            });
        });

        // Filter Status (PENTING: Ini akan mengambil semua status jika filter kosong)
        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        // Filter Rentang Tanggal
        $query->when($request->filled('date_start'), function ($q) use ($request) {
            $q->whereDate('date', '>=', $request->date_start);
        });

        $query->when($request->filled('date_end'), function ($q) use ($request) {
            $q->whereDate('date', '<=', $request->date_end);
        });

        // 3. Urutkan & Paginasi
        // Urutkan berdasarkan tanggal terbaru dan lakukan paginasi
        $transactions = $query->latest('date')->paginate(20)->withQueryString();

        // 4. Kirim data ke View
        // Ganti path ini jika lokasi view blade Anda berbeda
        // Jika Anda menggunakan struktur view yang Anda berikan sebelumnya, Anda mungkin perlu menyesuaikan pathnya.
        return view('pages.admin.reports.outgoing.index', compact('transactions'));
    }

    // ===================================================================
    // == AKHIR METHOD BARU ==
    // ===================================================================


    /**
     * Display the users report.
     */
    public function users()
    {
        $users = User::latest()->paginate(15);
        return view('pages.admin.reports.users', compact('users'));
    }

    /**
     * Display the system statistics report.
     */
    public function system()
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

    // ===================================================================
    // == METHOD EXPORT YANG SUDAH DIPERBARUI ==
    // ===================================================================

    /**
     * Handle export requests for reports.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
public function export(Request $request)
{
    $reportType = $request->query('report_type');
    $format = $request->query('format', 'excel');

    if ($format === 'excel') {
        switch ($reportType) {
            case 'incoming_goods':
                $fileName = 'laporan-barang-masuk-' . now()->format('Y-m-d') . '.xlsx';
                return Excel::download(new IncomingReportExport($request), $fileName);

            case 'outgoing_goods':
                $fileName = 'laporan-barang-keluar-' . now()->format('Y-m-d') . '.xlsx';
                return Excel::download(new OutgoingReportExport($request), $fileName);

            default:
                return redirect()->back()->with('error', "Jenis laporan '{$reportType}' untuk ekspor tidak valid.");
        }
    }

    return redirect()->back()->with('error', 'Format ekspor tidak didukung.');
}
}
