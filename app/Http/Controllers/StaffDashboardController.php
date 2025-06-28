<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    /**
     * Display staff dashboard with summary data
     */
    public function index()
    {
        $user = Auth::user();

        // Data summary untuk dashboard staff
        $todayTransactions = StockTransaction::whereDate('date', today())
            ->where('user_id', $user->id)
            ->count();

        $pendingIncoming = StockTransaction::where('type', 'Masuk')
            ->where('status', 'Pending')
            ->count();

        $pendingOutgoing = StockTransaction::where('type', 'Keluar')
            ->where('status', 'Pending')
            ->count();

        $lowStockProducts = Product::whereRaw('(
            SELECT COALESCE(SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END), 0)
            FROM stock_transactions
            WHERE product_id = products.id AND status = "Diterima"
        ) <= minimum_stock')->count();

        // Recent transactions yang dikerjakan staff ini
        $recentTransactions = StockTransaction::with(['product', 'user'])
            ->where('user_id', $user->id)
            ->latest('date')
            ->take(5)
            ->get();

        // Task summary - transaksi yang perlu dikerjakan
        $myTasks = StockTransaction::with(['product'])
            ->where('user_id', $user->id)
            ->where('status', 'Pending')
            ->take(10)
            ->get();

        return view('pages.staff.dashboard.index', compact(
            'todayTransactions',
            'pendingIncoming',
            'pendingOutgoing',
            'lowStockProducts',
            'recentTransactions',
            'myTasks'
        ));
    }

    /**
     * Display products list (read-only for staff)
     */
    public function productList(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(15);
        $categories = Category::all();

        return view('pages.staff.products.index', compact('products', 'categories'));
    }

    /**
     * Display specific product details
     */
    public function productShow(Product $product)
    {
        $product->load(['category', 'supplier', 'attributes']);

        // Calculate current stock
        $currentStock = StockTransaction::where('product_id', $product->id)
            ->where('status', 'Diterima')
            ->sum(\DB::raw('CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END'));

        // Recent transactions for this product
        $recentTransactions = StockTransaction::with(['user'])
            ->where('product_id', $product->id)
            ->latest('date')
            ->take(10)
            ->get();

        return view('pages.staff.products.show', compact('product', 'currentStock', 'recentTransactions'));
    }

    /**
     * Display stock overview
     */
    public function stockIndex(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->get()->map(function($product) {
            $currentStock = StockTransaction::where('product_id', $product->id)
                ->where('status', 'Diterima')
                ->sum(\DB::raw('CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END'));

            $product->current_stock = $currentStock;
            $product->stock_status = $currentStock <= $product->minimum_stock ? 'low' : 'normal';

            return $product;
        });

        return view('pages.staff.stock.index', compact('products'));
    }

    /**
     * Quick stock check for specific products
     */
    public function stockCheck(Request $request)
    {
        if ($request->has('product_id')) {
            $product = Product::findOrFail($request->product_id);
            $currentStock = StockTransaction::where('product_id', $product->id)
                ->where('status', 'Diterima')
                ->sum(\DB::raw('CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END'));

            return response()->json([
                'product' => $product,
                'current_stock' => $currentStock,
                'minimum_stock' => $product->minimum_stock,
                'status' => $currentStock <= $product->minimum_stock ? 'low' : 'normal'
            ]);
        }

        $products = Product::select('id', 'name', 'sku')->get();
        return view('pages.staff.stock.check', compact('products'));
    }

    /**
     * Update stock transaction status (for staff processing)
     */
    public function stockUpdate(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:stock_transactions,id',
            'status' => 'required|in:Diterima,Ditolak',
            'notes' => 'nullable|string|max:500'
        ]);

        $transaction = StockTransaction::findOrFail($request->transaction_id);

        // Pastikan staff hanya bisa update transaksi yang assigned ke mereka
        if ($transaction->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengupdate transaksi ini.');
        }

        $transaction->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Status transaksi berhasil diupdate.');
    }

    /**
     * Display tasks assigned to staff
     */
    public function taskList(Request $request)
    {
        $query = StockTransaction::with(['product', 'user'])
            ->where('user_id', Auth::id());

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('date', $request->date);
        }

        $tasks = $query->latest('date')->paginate(15);

        return view('pages.staff.tasks.index', compact('tasks'));
    }

    /**
     * Display specific task details
     */
    public function taskShow(StockTransaction $task)
    {
        // Pastikan staff hanya bisa lihat task yang assigned ke mereka
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat task ini.');
        }

        $task->load(['product.category', 'product.supplier', 'user']);

        return view('pages.staff.tasks.show', compact('task'));
    }

    /**
     * Mark task as completed
     */
    public function taskComplete(StockTransaction $task)
    {
        // Pastikan staff hanya bisa complete task yang assigned ke mereka
        if ($task->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menyelesaikan task ini.');
        }

        if ($task->status !== 'Pending') {
            return back()->with('error', 'Task ini sudah diproses sebelumnya.');
        }

        $task->update([
            'status' => 'Diterima'
        ]);

        return back()->with('success', 'Task berhasil diselesaikan.');
    }

    /**
     * Update task status with notes
     */
    public function taskUpdateStatus(Request $request, StockTransaction $task)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Ditolak,Dikeluarkan',
            'notes' => 'nullable|string|max:500'
        ]);

        // Pastikan staff hanya bisa update task yang assigned ke mereka
        if ($task->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengupdate task ini.');
        }

        $task->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Status task berhasil diupdate.');
    }

    /**
     * Display transactions assigned to staff
     */
    public function transactionList(Request $request)
    {
        $query = StockTransaction::with(['product', 'user'])
            ->where('user_id', Auth::id());

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest('date')->paginate(15);

        return view('pages.staff.transactions.index', compact('transactions'));
    }

    /**
     * Display specific transaction details
     */
    public function transactionShow(StockTransaction $transaction)
    {
        // Pastikan staff hanya bisa lihat transaksi yang assigned ke mereka
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat transaksi ini.');
        }

        $transaction->load(['product.category', 'product.supplier', 'user']);

        return view('pages.staff.transactions.show', compact('transaction'));
    }

    /**
     * Process transaction (receive/dispatch goods)
     */
    public function transactionProcess(Request $request, StockTransaction $transaction)
    {
        $request->validate([
            'action' => 'required|in:receive,dispatch,reject',
            'notes' => 'nullable|string|max:500'
        ]);

        // Pastikan staff hanya bisa process transaksi yang assigned ke mereka
        if ($transaction->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk memproses transaksi ini.');
        }

        $status = match($request->action) {
            'receive' => 'Diterima',
            'dispatch' => 'Dikeluarkan',
            'reject' => 'Ditolak'
        };

        $transaction->update([
            'status' => $status,
            'notes' => $request->notes
        ]);

        $message = match($request->action) {
            'receive' => 'Barang berhasil diterima.',
            'dispatch' => 'Barang berhasil dikeluarkan.',
            'reject' => 'Transaksi berhasil ditolak.'
        };

        return back()->with('success', $message);
    }

    /**
     * Generate personal work report
     */
    public function reportMyWork(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $user = Auth::user();

        // Summary data
        $totalTransactions = StockTransaction::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        $completedTransactions = StockTransaction::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('status', ['Diterima', 'Dikeluarkan'])
            ->count();

        $pendingTransactions = StockTransaction::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'Pending')
            ->count();

        // Detailed transactions
        $transactions = StockTransaction::with(['product'])
            ->where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->latest('date')
            ->get();

        // Group by type
        $incomingTransactions = $transactions->where('type', 'Masuk');
        $outgoingTransactions = $transactions->where('type', 'Keluar');

        return view('pages.staff.reports.my-work', compact(
            'startDate',
            'endDate',
            'totalTransactions',
            'completedTransactions',
            'pendingTransactions',
            'incomingTransactions',
            'outgoingTransactions'
        ));
    }

    /**
     * Display and update staff profile
     */
    public function profile()
    {
        $user = Auth::user();

        // Statistics for profile page
        $totalTasks = StockTransaction::where('user_id', $user->id)->count();
        $completedTasks = StockTransaction::where('user_id', $user->id)
            ->whereIn('status', ['Diterima', 'Dikeluarkan'])
            ->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

        return view('pages.staff.profile.index', compact('user', 'totalTasks', 'completedTasks', 'completionRate'));
    }

    /**
     * Update staff profile
     */
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email
        ];

        if ($request->password) {
            $updateData['password'] = bcrypt($request->password);
        }

        $user->update($updateData);

        return back()->with('success', 'Profile berhasil diupdate.');
    }
}
