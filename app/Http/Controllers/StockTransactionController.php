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

    /**
     * Menampilkan semua transaksi (biasanya untuk API admin).
     */
    public function index()
    {
        return response()->json($this->service->getAll());
    }

    /**
     * Menyimpan transaksi baru (biasanya dari API).
     * Ini berbeda dari form di web.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'status' => 'required|in:Pending,Diterima,Ditolak,Dikeluarkan',
            'notes' => 'nullable|string'
        ]);

        return response()->json($this->service->create($validated), 201);
    }

    /**
     * Menampilkan detail satu transaksi.
     */
    public function show($id)
    {
        return response()->json($this->service->findById($id));
    }

    /**
     * Memperbarui transaksi yang ada.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_id' => 'sometimes|exists:products,id',
            'user_id' => 'sometimes|exists:users,id',
            'type' => 'sometimes|in:Masuk,Keluar',
            'quantity' => 'sometimes|integer|min:1',
            'date' => 'sometimes|date',
            'status' => 'sometimes|in:Pending,Diterima,Ditolak,Dikeluarkan',
            'notes' => 'nullable|string'
        ]);

        return response()->json($this->service->update($id, $validated));
    }

    /**
     * Menghapus transaksi.
     */
    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Transaksi berhasil dihapus.']);
    }

    /**
     * Memfilter transaksi berdasarkan tipe (Masuk/Keluar).
     */
    public function filterByType($type)
    {
        // Validasi tipe untuk keamanan
        $validTypes = ['Masuk', 'Keluar'];
        if (!in_array($type, $validTypes)) {
            return response()->json(['message' => 'Tipe tidak valid.'], 400);
        }
        return response()->json($this->service->getByType($type));
    }

    /**
     * Menyetujui atau menolak transaksi (biasanya oleh Manajer).
     */
    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Diterima,Ditolak,Dikeluarkan',
            'notes' => 'nullable|string'
        ]);

        return response()->json(
            $this->service->approve($id, $validated['status'], $validated['notes'] ?? null)
        );
    }
    
    /**
     * Mengkonfirmasi penerimaan barang (biasanya oleh Staff).
     */
    public function confirm($id)
    {
        $transaction = StockTransaction::findOrFail($id);

        // Contoh otorisasi sederhana, bisa disempurnakan
        if ($transaction->status !== 'Pending' || $transaction->type !== 'Masuk') {
             return response()->json(['message' => 'Transaksi ini tidak dapat dikonfirmasi.'], 400);
        }

        $transaction->status = 'Diterima';
        $transaction->user_id = auth()->id(); // Tetapkan staff yang mengkonfirmasi
        $transaction->save();

        return response()->json([
            'message' => 'Transaksi berhasil dikonfirmasi.',
            'data' => $transaction
        ]);
    }
    
    // Anda mungkin tidak memerlukan method storeIncomingFromApi dan storeOutgoingFromApi
    // jika Anda tidak berencana membuat tugas dari API eksternal.
    // Jika ya, mereka bisa ditambahkan kembali di sini.
}