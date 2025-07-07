<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\StockTransaction; // PERUBAHAN: Pastikan ini ada
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockTransactionController extends Controller
{
    // ... method createIncoming() dan createOutgoing() tidak berubah ...

    public function storeIncoming(Request $request)
    {
        $validated = $this->validateIncomingRequest($request);

        DB::transaction(function () use ($validated) {
            $product = Product::findOrFail($validated['product_id']);
            $previousStock = $product->current_stock;

            StockTransaction::create([
                'product_id' => $product->id,
                'supplier_id' => $validated['supplier_id'],
                'user_id' => Auth::id(),
                'type' => StockTransaction::TYPE_MASUK, // PERUBAHAN: Menggunakan konstanta
                'quantity' => $validated['quantity'],
                'date' => $validated['transaction_date'],
                'status' => 'Diterima',
                'notes' => $validated['notes'] ?? null,
                'previous_stock' => $previousStock,
                'current_stock' => $previousStock + $validated['quantity']
            ]);

            $product->increment('current_stock', $validated['quantity']);
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi masuk berhasil dicatat');
    }

    public function storeOutgoing(Request $request)
    {
        $validated = $this->validateOutgoingRequest($request);
        $product = Product::findOrFail($validated['product_id']);

        if ($product->current_stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stok tidak mencukupi! Stok tersedia: '.$product->current_stock]);
        }

        DB::transaction(function () use ($validated, $product) {
            $previousStock = $product->current_stock;

            StockTransaction::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => StockTransaction::TYPE_KELUAR, // PERUBAHAN: Menggunakan konstanta
                'quantity' => $validated['quantity'],
                'date' => $validated['transaction_date'],
                'status' => 'Dikeluarkan',
                'notes' => $validated['notes'] ?? null,
                'previous_stock' => $previousStock,
                'current_stock' => $previousStock - $validated['quantity']
            ]);

            $product->decrement('current_stock', $validated['quantity']);
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi keluar berhasil dicatat');
    }
    
    // ... method index(), validateIncomingRequest(), validateOutgoingRequest(), apiIndex() tidak berubah ...

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'nullable|required_if:type,Masuk|exists:suppliers,id',
            // PERUBAHAN: Validasi bisa menggunakan konstanta juga untuk konsistensi
            'type' => 'required|in:' . StockTransaction::TYPE_MASUK . ',' . StockTransaction::TYPE_KELUAR,
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // PERUBAHAN: Menggunakan konstanta
        if ($validated['type'] === StockTransaction::TYPE_KELUAR && $product->current_stock < $validated['quantity']) {
            return response()->json([
                'message' => 'Stok tidak mencukupi',
                'available_stock' => $product->current_stock
            ], 400);
        }

        $transaction = DB::transaction(function () use ($validated, $product) {
            $previousStock = $product->current_stock;
            // PERUBAHAN: Menggunakan konstanta
            $newStock = $validated['type'] === StockTransaction::TYPE_MASUK
                ? $previousStock + $validated['quantity']
                : $previousStock - $validated['quantity'];

            $transaction = StockTransaction::create([
                'product_id' => $product->id,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'user_id' => Auth::id(),
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'date' => $validated['date'],
                // PERUBAHAN: Menggunakan konstanta
                'status' => $validated['type'] === StockTransaction::TYPE_MASUK ? 'Diterima' : 'Dikeluarkan',
                'notes' => $validated['notes'] ?? null,
                'previous_stock' => $previousStock,
                'current_stock' => $newStock
            ]);
            
            // PERUBAHAN: Menggunakan konstanta
            if ($validated['type'] === StockTransaction::TYPE_MASUK) {
                $product->increment('current_stock', $validated['quantity']);
            } else {
                $product->decrement('current_stock', $validated['quantity']);
            }

            return $transaction;
        });

        return response()->json($transaction, 201);
    }
}