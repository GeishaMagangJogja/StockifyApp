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
        $reportType = $request->query('report_type'); // Hapus default agar lebih eksplisit
        $format = $request->query('format', 'excel');

        if ($format === 'excel') {
            switch ($reportType) {
                case 'incoming_goods':
                    $fileName = 'laporan-barang-masuk-' . now()->format('Y-m-d') . '.xlsx';
                    return Excel::download(new IncomingReportExport($request), $fileName);

                // <-- PERUBAHAN 2: Tambahkan case untuk outgoing_goods -->
                case 'outgoing_goods':
                    $fileName = 'laporan-barang-keluar-' . now()->format('Y-m-d') . '.xlsx';
                    return Excel::download(new OutgoingReportExport($request), $fileName);

                default:
                    // Memberikan pesan error yang lebih jelas
                    return redirect()->back()->with('error', "Jenis laporan '{$reportType}' untuk ekspor tidak valid.");
            }
        }

        return redirect()->back()->with('error', 'Format ekspor tidak didukung.');
    }
}