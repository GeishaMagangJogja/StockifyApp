<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockTransaction;
use Illuminate\Contracts\View\View;

class StaffReportController extends Controller
{
    /**
     * Menampilkan laporan riwayat semua transaksi barang masuk.
     */
    public function showIncomingReport(): View
    {
        // Logikanya sama persis dengan 'listIncoming', tapi mengarah ke view laporan.
        // Kita juga bisa menambahkan filter tanggal di sini nanti.
        $transactions = StockTransaction::with(['product', 'supplier', 'user'])
            ->where('type', 'masuk')
            ->latest('date')
            ->latest()
            ->paginate(25); // Laporan bisa menampilkan lebih banyak data per halaman

        return view('pages.staff.reports.incoming_report', [
            'transactions' => $transactions,
            'reportTitle' => 'Laporan Riwayat Barang Masuk'
        ]);
    }

    /**
     * Menampilkan laporan riwayat semua transaksi barang keluar.
     */
    public function showOutgoingReport(): View
    {
        // Logikanya sama persis dengan 'listOutgoing'.
        $transactions = StockTransaction::with(['product', 'user'])
            ->where('type', 'keluar')
            ->latest('date')
            ->latest()
            ->paginate(25);

        return view('pages.staff.reports.outgoing_report', [
            'transactions' => $transactions,
            'reportTitle' => 'Laporan Riwayat Barang Keluar'
        ]);
    }
}