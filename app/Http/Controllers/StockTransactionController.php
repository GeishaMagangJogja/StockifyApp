<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockTransactionController extends Controller
{
    /**
     * Menampilkan form untuk transaksi masuk
     */
    public function createIncoming()
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('transactions.incoming.create', compact('products', 'suppliers'));
    }

    /**
     * Menampilkan form untuk transaksi keluar
     */
    public function createOutgoing()
    {
        $products = Product::where('current_stock', '>', 0)
                         ->orderBy('name')
                         ->get();

        return view('transactions.outgoing.create', compact('products'));
    }

    /**
     * Menyimpan transaksi masuk
     */
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
                'type' => 'Masuk',
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

    /**
     * Menyimpan transaksi keluar
     */
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
                'type' => 'Keluar',
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

    /**
     * Menampilkan riwayat transaksi
     */
    public function index()
    {
        $transactions = StockTransaction::with(['product', 'supplier', 'user'])
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Validasi request untuk transaksi masuk
     */
    protected function validateIncomingRequest(Request $request)
    {
        return $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:255'
        ]);
    }

    /**
     * Validasi request untuk transaksi keluar
     */
    protected function validateOutgoingRequest(Request $request)
    {
        return $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:255'
        ]);
    }

    /**
     * API Endpoint untuk mendapatkan semua transaksi
     */
    public function apiIndex()
    {
        $transactions = StockTransaction::with(['product', 'supplier', 'user'])
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($transactions);
    }

    /**
     * API Endpoint untuk menyimpan transaksi
     */
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'nullable|required_if:type,Masuk|exists:suppliers,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($validated['type'] === 'Keluar' && $product->current_stock < $validated['quantity']) {
            return response()->json([
                'message' => 'Stok tidak mencukupi',
                'available_stock' => $product->current_stock
            ], 400);
        }

        $transaction = DB::transaction(function () use ($validated, $product) {
            $previousStock = $product->current_stock;
            $newStock = $validated['type'] === 'Masuk'
                ? $previousStock + $validated['quantity']
                : $previousStock - $validated['quantity'];

            $transaction = StockTransaction::create([
                'product_id' => $product->id,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'user_id' => Auth::id(),
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'date' => $validated['date'],
                'status' => $validated['type'] === 'Masuk' ? 'Diterima' : 'Dikeluarkan',
                'notes' => $validated['notes'] ?? null,
                'previous_stock' => $previousStock,
                'current_stock' => $newStock
            ]);

            if ($validated['type'] === 'Masuk') {
                $product->increment('current_stock', $validated['quantity']);
            } else {
                $product->decrement('current_stock', $validated['quantity']);
            }

            return $transaction;
        });

        return response()->json($transaction, 201);
    }
}