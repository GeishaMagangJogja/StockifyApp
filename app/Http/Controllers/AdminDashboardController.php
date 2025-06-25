<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Jumlah produk
        $totalProducts = Product::count();

        // 2. Jumlah transaksi masuk & keluar (misal 30 hari terakhir)
        $dateFrom = now()->subDays(30);
        $incomingCount = StockTransaction::where('type', 'in')
                             ->where('created_at', '>=', $dateFrom)
                             ->count();
        $outgoingCount = StockTransaction::where('type', 'out')
                             ->where('created_at', '>=', $dateFrom)
                             ->count();

        // 3. Grafik stok — group by tanggal 7 hari terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->format('Y-m-d');
            $chartData['categories'][] = now()->subDays($i)->format('d M');
            $chartData['incoming'][] = StockTransaction::where('type','in')
                ->whereDate('created_at', $day)->count();
            $chartData['outgoing'][] = StockTransaction::where('type','out')
                ->whereDate('created_at', $day)->count();
        }

        // 4. Aktivitas user terbaru (login/update dsb) — misal 5 user terakhir
        $recentUsers = User::orderBy('updated_at', 'desc')
                          ->limit(5)
                          ->get(['name','email','updated_at']);

        return view('pages.admin.dashboard.index', compact(
            'totalProducts',
            'incomingCount',
            'outgoingCount',
            'chartData',
            'recentUsers'
        ));
    }
}
