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
use App\Exports\IncomingReportExport; // Pastikan class ini sudah dibuat
use Maatwebsite\Excel\Facades\Excel; // Fasad untuk pustaka Excel

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
     * Display the transactions report.
     */
    public function transactions(Request $request)
    {
        $query = StockTransaction::with(['product', 'user'])->orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $transactions = $query->paginate(20);

        return view('pages.admin.reports.transactions', compact('transactions'));
    }

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
    // == METHOD BARU UNTUK EXPORT ADA DI SINI ==
    // ===================================================================
    
    /**
     * Handle export requests for reports.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function export(Request $request)
    {
        // Tentukan jenis laporan yang akan diekspor dari URL.
        // Anda bisa menambahkan parameter 'report_type' di URL export Anda.
        // Contoh: route('reports.export', ['report_type' => 'incoming_goods'])
        // Untuk sekarang, kita asumsikan ini untuk 'Laporan Barang Masuk'.
        
        $reportType = $request->query('report_type', 'incoming_goods'); // Default ke barang masuk
        $format = $request->query('format', 'excel');

        if ($format === 'excel') {
            // Logika untuk menentukan class export mana yang akan digunakan
            // Ini membuat controller lebih fleksibel jika nanti ada export lain
            switch ($reportType) {
                case 'incoming_goods':
                    $fileName = 'laporan-barang-masuk-' . now()->format('Y-m-d') . '.xlsx';
                    // Panggil class Export yang sesuai
                    return Excel::download(new IncomingReportExport($request), $fileName);
                
                // case 'stock_report': // Contoh jika nanti ada export laporan stok
                //     $fileName = 'laporan-stok-' . now()->format('Y-m-d') . '.xlsx';
                //     return Excel::download(new StockReportExport($request), $fileName);

                default:
                    return redirect()->back()->with('error', 'Jenis laporan untuk ekspor tidak valid.');
            }
        }

        return redirect()->back()->with('error', 'Format ekspor tidak didukung.');
    }
    public function filterTransactions(Request $request)
    {
    $query = Transaction::query();
    
    if ($request->search) {
        $query->whereHas('product', fn($q) => 
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('sku', 'like', "%{$request->search}%")
        );
    }
    
    if ($request->status) {
        $query->where('status', $request->status);
    }
    
    if ($request->start_date) {
        $query->whereDate('date', '>=', $request->start_date);
    }
    
    if ($request->end_date) {
        $query->whereDate('date', '<=', $request->end_date);
    }
    
    return response()->json($query->with(['product', 'supplier'])->paginate(10));
    }
}