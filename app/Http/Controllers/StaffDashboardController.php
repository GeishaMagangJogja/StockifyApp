<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockTransaction;

class StaffDashboardController extends Controller
{
    public function index()
    {
        // Bagian ini mengambil data...
        $incomingTransactions = StockTransaction::where('type', 'masuk')
                                                ->where('status', 'pending')
                                                ->get();

        $outgoingTransactions = StockTransaction::where('type', 'keluar')
                                                ->where('status', 'pending')
                                                ->get();

        // Bagian ini mengirimkan data ke view. INI SUDAH BENAR.
        return view('pages.staff.dashboard.index', [
            'incomingTransactions' => $incomingTransactions,
            'outgoingTransactions' => $outgoingTransactions,
        ]);
    }
}
