<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ManagerDashboardController extends Controller
{
    // Dashboard Overview
    public function index()
    {
        try {
            $totalProducts = Product::count();
            $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')->count();

            $dateFrom = Carbon::now()->subDays(30);

            $incomingTransactions = StockTransaction::where('type', 'Masuk')
                ->where('created_at', '>=', $dateFrom)
                ->count();

            $outgoingTransactions = StockTransaction::where('type', 'Keluar')
                ->where('created_at', '>=', $dateFrom)
                ->count();

            // Stock movement chart data
            $chartData = [
                'categories' => [],
                'incoming' => [],
                'outgoing' => []
            ];

            for ($i = 6; $i >= 0; $i--) {
                $day = Carbon::now()->subDays($i);
                $dayFormatted = $day->format('Y-m-d');

                $chartData['categories'][] = $day->format('d M');
                $chartData['incoming'][] = StockTransaction::where('type', 'Masuk')
                    ->whereDate('created_at', $dayFormatted)
                    ->count();
                $chartData['outgoing'][] = StockTransaction::where('type', 'Keluar')
                    ->whereDate('created_at', $dayFormatted)
                    ->count();
            }

            // Recent stock transactions
            $recentTransactions = StockTransaction::with(['product', 'user'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('pages.manager.dashboard.index', compact(
                'totalProducts',
                'lowStockProducts',
                'incomingTransactions',
                'outgoingTransactions',
                'chartData',
                'recentTransactions'
            ));

        } catch (\Exception $e) {
            return view('pages.manager.dashboard.index', [
                'totalProducts' => 0,
                'lowStockProducts' => 0,
                'incomingTransactions' => 0,
                'outgoingTransactions' => 0,
                'chartData' => ['categories' => [], 'incoming' => [], 'outgoing' => []],
                'recentTransactions' => collect([])
            ]);
        }
    }

    // Stock Management
    public function stockIndex(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        }

        if ($request->has('stock_status')) {
            if ($request->stock_status == 'low') {
                $query->whereColumn('stock', '<=', 'minimum_stock');
            } elseif ($request->stock_status == 'out') {
                $query->where('stock', 0);
            }
        }

        $products = $query->paginate(10);

        return view('pages.manager.stock.index', compact('products'));
    }

    // Incoming Stock
    public function stockIn(Request $request)
    {
        $query = StockTransaction::with(['product', 'user'])
            ->where('type', 'Masuk')
            ->orderBy('date', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $transactions = $query->paginate(10);
        $products = Product::active()->get();

        return view('pages.manager.stock.incoming', compact('transactions', 'products'));
    }

    public function stockInStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $transaction = StockTransaction::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->id(),
            'type' => 'Masuk',
            'quantity' => $request->quantity,
            'date' => $request->date,
            'status' => 'Diterima',
            'notes' => $request->notes,
        ]);

        // Update product stock
        $product = Product::find($request->product_id);
        $product->increment('stock', $request->quantity);

        return redirect()->route('manajergudang.stock.in')->with('success', 'Barang masuk berhasil dicatat');
    }

    // Outgoing Stock
    public function stockOut(Request $request)
    {
        $query = StockTransaction::with(['product', 'user'])
            ->where('type', 'Keluar')
            ->orderBy('date', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $transactions = $query->paginate(10);
        $products = Product::active()->get();

        return view('pages.manager.stock.outgoing', compact('transactions', 'products'));
    }

    public function stockOutStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $product = Product::find($request->product_id);

        // Check stock availability
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $transaction = StockTransaction::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->id(),
            'type' => 'Keluar',
            'quantity' => $request->quantity,
            'date' => $request->date,
            'status' => 'Dikeluarkan',
            'notes' => $request->notes,
        ]);

        // Update product stock
        $product->decrement('stock', $request->quantity);

        return redirect()->route('manajergudang.stock.out')->with('success', 'Barang keluar berhasil dicatat');
    }


    public function productList(Request $request)
{
    $query = Product::with(['category', 'supplier']);

    if ($request->has('search')) {
        $query->where('name', 'like', "%{$request->search}%")
              ->orWhere('sku', 'like', "%{$request->search}%");
    }

    $products = $query->paginate(10);

    return view('pages.manager.products.index', compact('products'));
}

public function productShow(Product $product)
{
    return view('pages.manager.products.show', compact('product'));
}
    // Stock Opname
    public function stockOpname(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        }

        $products = $query->paginate(10);

        return view('pages.manager.stock.opname', compact('products'));
    }

    public function stockOpnameStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'actual_stock' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $product = Product::find($request->product_id);
        $difference = $request->actual_stock - $product->stock;

        // Record the adjustment
        StockTransaction::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => $difference > 0 ? 'Masuk' : 'Keluar',
            'quantity' => abs($difference),
            'date' => now(),
            'status' => 'Diterima',
            'notes' => 'Stock Opname: ' . $request->notes,
        ]);

        // Update product stock
        $product->update(['stock' => $request->actual_stock]);

        return redirect()->route('manajergudang.stock.opname')->with('success', 'Stock opname berhasil dicatat');
    }

    // Stock History
    public function stockHistory(Request $request)
    {
        $query = StockTransaction::with(['product', 'user'])
            ->orderBy('date', 'desc');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $transactions = $query->paginate(15);
        $products = Product::all();

        return view('pages.manager.stock.history', compact('transactions', 'products'));
    }

    // Reports
    public function reportIndex()
    {
        return view('pages.manager.reports.index');
    }

    public function reportStock()
    {
        $products = Product::with(['category', 'supplier'])
            ->orderBy('stock', 'asc')
            ->get();

        return view('pages.manager.reports.stock', compact('products'));
    }

    public function reportTransactions()
    {
        $incoming = StockTransaction::where('type', 'Masuk')
            ->whereMonth('date', now()->month)
            ->sum('quantity');

        $outgoing = StockTransaction::where('type', 'Keluar')
            ->whereMonth('date', now()->month)
            ->sum('quantity');

        $transactions = StockTransaction::with(['product', 'user'])
            ->whereMonth('date', now()->month)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('pages.manager.reports.transactions', compact('incoming', 'outgoing', 'transactions'));
    }

    public function reportInventory()
    {
        $categories = Category::withCount('products')->get();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $outOfStockProducts = Product::where('stock', 0)->count();

        return view('pages.manager.reports.inventory', compact('categories', 'lowStockProducts', 'outOfStockProducts'));
    }

    public function reportExport(Request $request)
    {
        // Implement export logic (PDF, Excel, etc.)
        // This would typically return a downloadable file
        return back()->with('success', 'Laporan berhasil diekspor');
    }
}
