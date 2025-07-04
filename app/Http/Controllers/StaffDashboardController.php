<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StaffDashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama untuk Staff Gudang.
     */
    public function index(): View
    {
        // Ambil TUGAS Barang Masuk (status 'pending')
        $incomingTasks = StockTransaction::with('product', 'supplier')
            ->where('type', 'masuk')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        // PENYESUAIAN SEMENTARA: Tampilkan juga yang statusnya 'dikeluarkan' sebagai tugas
        $outgoingTasks = StockTransaction::with('product')
            ->where('type', 'keluar')
            ->whereIn('status', ['pending', 'dikeluarkan']) // <-- KITA KEMBALIKAN INI SEMENTARA
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Hitung statistik
        $totalPendingTasks = $incomingTasks->count() + $outgoingTasks->where('status', 'pending')->count();
        $incomingTodayCount = $incomingTasks->filter(fn($task) => $task->created_at->isToday())->count();
        $outgoingTodayCount = $outgoingTasks->filter(fn($task) => $task->created_at->isToday())->count();
        
        // Widget samping
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'min_stock')->where('current_stock', '>', 0)->orderBy('current_stock', 'asc')->take(5)->get();
        $recentTransactions = StockTransaction::with('product', 'user')->where('status', '!=', 'pending')->latest('updated_at')->limit(5)->get();

        return view('pages.staff.dashboard.index', compact(
            'incomingTasks', 'outgoingTasks', 'incomingTodayCount', 'outgoingTodayCount',
            'totalPendingTasks', 'lowStockProducts', 'recentTransactions'
        ));
    }

    // ... sisa method profile dan updateProfile biarkan seperti semula ...
    public function profile()
    {
        return view('pages.profile.edit', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        // ... (kode ini tidak diubah)
    }
}