<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use Illuminate\Http\Request;
use App\Services\StockTransactionService;

class StockTransactionController extends Controller
{
    protected $service;

    public function __construct(StockTransactionService $service)
    {
        $this->service = $service;
    }

    // ... (semua method lama Anda: index, store, show, dll. biarkan saja) ...


    // ====================================================================
    // TAMBAHKAN METHOD BARU DI BAWAH INI
    // ====================================================================
    /**
     * Membuat transaksi barang masuk baru dari API (misal: Postman)
     * dan menyimpannya sebagai tugas 'pending' untuk staff.
     */
    public function storeIncomingFromApi(Request $request)
    {
        // 1. Validasi data yang PENTING dari Postman
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        // 2. Tambahkan data yang di-hardcode oleh sistem
        $dataForService = array_merge($validated, [
            'type' => 'masuk',   // <- Hardcode: Tipe selalu 'masuk'
            'status' => 'pending', // <- Hardcode: Status selalu 'pending'
            'date' => now(),     // <- Hardcode: Tanggal adalah hari ini
            'user_id' => null,   // <- Hardcode: Belum ada user yang proses
        ]);

        // 3. Gunakan service yang sudah ada untuk membuat data
        $transaction = $this->service->create($dataForService);

        // 4. Beri respon sukses
        return response()->json([
            'success' => true,
            'message' => 'Tugas barang masuk berhasil dibuat dan menunggu konfirmasi staff.',
            'data' => $transaction
        ], 201);
    }
    // ====================================================================
    // AKHIR DARI METHOD BARU
    // ====================================================================


    // ... (method confirm Anda dan lainnya tetap di sini) ...
     public function storeOutgoingFromApi(Request $request)
    {
        // 1. Validasi data yang masuk dari Postman
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string' // Catatan bisa berisi tujuan pengiriman, dll.
        ]);

        // 2. Tambahkan data yang di-hardcode oleh sistem
        // Cek apakah Anda menggunakan Service Pattern. Jika ya, sesuaikan.
        // Asumsi kita menggunakan Model::create() secara langsung untuk contoh ini.
        $transaction = \App\Models\StockTransaction::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'notes' => $validated['notes'] ?? null,
            'type' => 'keluar',  // <- Hardcode: Tipe selalu 'keluar'
            'status' => 'pending', // <- Hardcode: Status selalu 'pending'
            'date' => now(),
            'user_id' => null,
            'supplier_id' => null, // Barang keluar tidak memiliki supplier
        ]);

        // 3. Beri respon sukses
        return response()->json([
            'success' => true,
            'message' => 'Tugas barang keluar berhasil dibuat dan menunggu persiapan.',
            'data' => $transaction
        ], 201);
}
}