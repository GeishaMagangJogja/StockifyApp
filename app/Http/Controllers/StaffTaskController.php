<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\StockTransaction;
use Illuminate\Contracts\View\View; // Menggunakan contract untuk return type
use Illuminate\Http\RedirectResponse; // Return type untuk redirect
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffTaskController extends Controller
{
    // ==========================================================
    // == BAGIAN BARANG MASUK (INCOMING)
    // ==========================================================

    /**
     * Menampilkan daftar semua transaksi barang masuk.
     */
    public function listIncoming(): View
    {
        $transactions = StockTransaction::with(['product', 'supplier', 'user'])
            ->where('type', 'masuk')
            ->latest('date')
            ->latest() // Default ke 'created_at'
            ->paginate(15);

        return view('pages.staff.tasks.list_incoming', compact('transactions'));
    }

    /**
     * Menampilkan formulir untuk mengkonfirmasi penerimaan barang masuk.
     */
    public function showIncomingConfirmationForm(StockTransaction $transaction): View|RedirectResponse
    {
        if ($transaction->type !== 'masuk' || $transaction->status !== 'pending') {
            return redirect()->route('staff.dashboard')->with('error', 'Tugas tidak valid atau sudah diproses.');
        }

        return view('pages.staff.tasks.confirm_incoming', ['task' => $transaction]);
    }
    
    /**
     * Memproses data dari formulir konfirmasi barang masuk.
     */
    public function processIncomingConfirmation(Request $request, StockTransaction $transaction): RedirectResponse
    {
        $request->validate([
            'quantity_received' => 'required|integer|min:0',
            'received_date' => 'required|date',
            'additional_notes' => 'nullable|string',
        ]);

        if ($transaction->type !== 'masuk' || $transaction->status !== 'pending') {
            return redirect()->route('staff.dashboard')->with('error', 'Tugas ini tidak bisa diproses lagi.');
        }

        DB::beginTransaction();
        try {
            $product = $transaction->product;
            if ($product) {
                $product->stock += $request->quantity_received;
                $product->save();
            }

            $transaction->status = 'completed';
            $transaction->quantity = $request->quantity_received;
            $transaction->date = $request->received_date;
            $transaction->user_id = Auth::id();
            
            $originalNotes = $transaction->notes ? $transaction->notes . "\n" : "";
            $transaction->notes = $originalNotes . "Konfirmasi: " . ($request->additional_notes ?? 'Diterima sesuai pesanan.');
            
            $transaction->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses transaksi. Error: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('staff.stock.incoming.list')->with('success', 'Barang masuk berhasil dikonfirmasi dan stok telah diperbarui.');
    }


    // ==========================================================
    // == BAGIAN BARANG KELUAR (OUTGOING)
    // ==========================================================

    /**
     * Menampilkan daftar semua transaksi barang keluar.
     */
    public function listOutgoing(): View
    {
        $transactions = StockTransaction::with(['product', 'user'])
            ->where('type', 'keluar')
            ->latest('date')
            ->latest()
            ->paginate(15);

        return view('pages.staff.tasks.list_outgoing', compact('transactions'));
    }

    /**
     * Menampilkan formulir untuk persiapan barang keluar.
     */
    public function showOutgoingPreparationForm(StockTransaction $transaction): View|RedirectResponse
    {
        if ($transaction->type !== 'keluar' || $transaction->status !== 'pending') {
            return redirect()->route('staff.dashboard')->with('error', 'Tugas tidak valid atau sudah diproses.');
        }

        $product = $transaction->product;
        if ($product && $product->stock < $transaction->quantity) {
            session()->flash('warning', "Perhatian: Stok saat ini ({$product->stock}) lebih sedikit dari yang diminta ({$transaction->quantity}).");
        }

        return view('pages.staff.tasks.prepare_outgoing', ['task' => $transaction]);
    }

    /**
     * Memproses data dari formulir persiapan barang keluar.
     */
    public function processOutgoingDispatch(Request $request, StockTransaction $transaction): RedirectResponse
    {
        $product = $transaction->product;

        if (!$product) {
            return redirect()->route('staff.dashboard')->with('error', 'Produk untuk tugas ini tidak ditemukan.');
        }

        $request->validate([
            'quantity_dispatched' => "required|integer|min:1|max:{$product->stock}",
        ]);

        if ($transaction->type !== 'keluar' || $transaction->status !== 'pending') {
            return redirect()->route('staff.dashboard')->with('error', 'Tugas ini tidak bisa diproses lagi.');
        }

        DB::beginTransaction();
        try {
            $product->stock -= $request->quantity_dispatched;
            $product->save();

            $transaction->status = 'completed';
            $transaction->quantity = $request->quantity_dispatched;
            $transaction->user_id = Auth::id();
            $transaction->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses transaksi. Error: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('staff.stock.outgoing.list')->with('success', 'Barang keluar berhasil dikonfirmasi dan stok telah diperbarui.');
    }
}