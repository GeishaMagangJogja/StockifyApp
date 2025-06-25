<?php

namespace App\Http\Controllers;

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
}
