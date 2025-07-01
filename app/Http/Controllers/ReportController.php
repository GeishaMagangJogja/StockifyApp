<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockTransaction;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;

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

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(20);
        $categories = Category::orderBy('name')->get();

        // Tambahkan summary stok
        $stockSummary = [
            // ===================================================================
            // PERBAIKAN DI BAWAH INI
            // Mengubah 'minimum_stock' menjadi 'min_stock'
            // ===================================================================
            'safe' => Product::whereColumn('current_stock', '>', 'min_stock') // <-- DIUBAH
                ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
                ->count(),
            'low' => Product::where('current_stock', '>', 0)
                ->whereColumn('current_stock', '<=', 'min_stock') // <-- DIUBAH
                ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
                ->count(),
            // ===================================================================
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
}