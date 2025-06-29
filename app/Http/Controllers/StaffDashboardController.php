<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffDashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama untuk Staff Gudang,
     * yang berisi daftar tugas yang perlu dikerjakan.
     */
    public function index(): View
    {
        // Ambil TUGAS (status 'pending') untuk barang masuk
        $incomingTasks = StockTransaction::with('product', 'supplier')
            ->where('type', 'masuk')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        // Ambil TUGAS (status 'pending') untuk barang keluar
        $outgoingTasks = StockTransaction::with('product')
            ->where('type', 'keluar')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        // Kirim data ini ke view
        // Variabel yang dikirim adalah $incomingTasks dan $outgoingTasks
        return view('pages.staff.dashboard.index', [
            'incomingTasks' => $incomingTasks,
            'outgoingTasks' => $outgoingTasks,
        ]);
    }
}
