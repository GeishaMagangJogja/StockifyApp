<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        try {
            // 1. Jumlah produk dengan fallback
            $totalProducts = Product::count() ?? 0;

            // 2. Jumlah transaksi masuk & keluar (30 hari terakhir)
            $dateFrom = Carbon::now()->subDays(30);

            $incomingCount = StockTransaction::where('type', 'in')
                                 ->where('created_at', '>=', $dateFrom)
                                 ->count() ?? 0;

            $outgoingCount = StockTransaction::where('type', 'out')
                                 ->where('created_at', '>=', $dateFrom)
                                 ->count() ?? 0;

            // 3. Grafik stok — group by tanggal 7 hari terakhir
            $chartData = [
                'categories' => [],
                'incoming' => [],
                'outgoing' => []
            ];

            for ($i = 6; $i >= 0; $i--) {
                $day = Carbon::now()->subDays($i);
                $dayFormatted = $day->format('Y-m-d');

                $chartData['categories'][] = $day->format('d M');

                $chartData['incoming'][] = StockTransaction::where('type', 'in')
                    ->whereDate('created_at', $dayFormatted)
                    ->count() ?? 0;

                $chartData['outgoing'][] = StockTransaction::where('type', 'out')
                    ->whereDate('created_at', $dayFormatted)
                    ->count() ?? 0;
            }

            // 4. Aktivitas user terbaru (5 user terakhir)
            $recentUsers = User::orderBy('updated_at', 'desc')
                              ->limit(5)
                              ->select(['name', 'email', 'updated_at'])
                              ->get();

            // Debug untuk melihat data
            // dd($chartData); // Uncomment untuk debug

            return view('pages.admin.dashboard.index', compact(
                'totalProducts',
                'incomingCount',
                'outgoingCount',
                'chartData',
                'recentUsers'
            ));

        } catch (\Exception $e) {

            // Return view dengan data kosong jika terjadi error
            return view('pages.admin.dashboard.index', [
                'totalProducts' => 0,
                'incomingCount' => 0,
                'outgoingCount' => 0,
                'chartData' => [
                    'categories' => [],
                    'incoming' => [],
                    'outgoing' => []
                ],
                'recentUsers' => collect([])
            ]);
        }
    }
}
