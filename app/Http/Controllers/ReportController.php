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
    public function index()
    {
        return view('pages.admin.reports.index');
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

        return view('pages.admin.reports.stock', compact('products', 'categories'));
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