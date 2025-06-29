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

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'status' => 'required|in:Pending,Diterima,Ditolak,Dikeluarkan',
            'notes' => 'nullable|string'
        ]);

        return response()->json($this->service->create($validated), 201);
    }

    public function show($id)
    {
        return response()->json($this->service->findById($id));
    }

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

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Transaksi berhasil dihapus.']);
    }

    public function filterByType($type)
    {
        return response()->json($this->service->getByType($type));
    }

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
    public function confirm($id)
{
    $transaction = StockTransaction::findOrFail($id);

    if ($transaction->user_id !== auth()->id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $transaction->status = 'Diterima';
    $transaction->save();

    return response()->json([
        'message' => 'Transaksi berhasil dikonfirmasi.',
        'data' => $transaction
    ]);
}

}

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