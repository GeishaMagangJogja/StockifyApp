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
    public function index(Request $request)
{
    // untuk stok
    $queryProducts = Product::with('category')->withCount('stockTransactions');
    if ($request->has('category_id') && $request->category_id != '') {
        $queryProducts->where('category_id', $request->category_id);
    }
    $products = $queryProducts->paginate(10);
    $categories = Category::all();

    // untuk transaksi
    $queryTransactions = StockTransaction::with(['product', 'user'])->orderBy('created_at', 'desc');
    if ($request->filled('type')) {
        $queryTransactions->where('type', $request->type);
    }
    if ($request->filled('from') && $request->filled('to')) {
        $queryTransactions->whereBetween('created_at', [$request->from, $request->to]);
    }
    $transactions = $queryTransactions->paginate(10);

    return view('pages.admin.reports.index', compact('products', 'categories', 'transactions'));
}
  public function stock(Request $request)
{
    $query = Product::with('category')->withCount('stockTransactions');

    if ($request->has('category_id') && $request->category_id != '') {
        $query->where('category_id', $request->category_id);
    }

    $products = $query->paginate(20);
    $categories = Category::all();

    return view('pages.admin.reports.stock', compact('products', 'categories'));
}
    public function transactions(Request $request)
    {
        $query = StockTransaction::with(['product', 'user'])->orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Optional: filter tanggal
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $transactions = $query->paginate(20);

        return view('pages.admin.reports.transactions', compact('transactions'));
    }

    public function users()
    {
         $users = User::paginate(15);
        return view('pages.admin.reports.users', compact('users'));
    }

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
