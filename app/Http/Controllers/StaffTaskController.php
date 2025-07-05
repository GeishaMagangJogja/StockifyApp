<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\StockTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffTaskController extends Controller
{
    // ==========================================================
    // == PUSAT PENGERJAAN TUGAS (WORKSPACE)
    // ==========================================================

     public function index(): View
    {
        $incomingTasks = StockTransaction::with('product', 'supplier')
            ->where('type', 'masuk')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $outgoingTasks = StockTransaction::with('product')
            ->where('type', 'keluar')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('pages.staff.tasks.index', compact('incomingTasks', 'outgoingTasks'));
    }

    public function listIncoming()
{
    $transactions = StockTransaction::with(['product', 'supplier', 'user'])
        ->where('type', StockTransaction::TYPE_MASUK)
        ->latest()
        ->paginate(15);

    return view('pages.staff.tasks.list_incoming', compact('transactions'));
}

    // ... method listIncoming dan listOutgoing tetap sama

    public function showIncomingConfirmationForm(StockTransaction $transaction): View|RedirectResponse
    {
        if (!$transaction->exists ||
            !$transaction->isTypeMasuk() ||
            !$transaction->isPending()) {
            return redirect()->route('staff.tasks.index')
                ->with('error', 'Transaksi tidak valid atau sudah diproses.');
        }

        return view('pages.staff.tasks.confirm_incoming', ['task' => $transaction]);
    }
 public function processIncomingConfirmation(Request $request, StockTransaction $transaction): RedirectResponse
{
    $request->validate([
        'quantity_received' => 'required|integer|min:1|max:'.$transaction->quantity,
        'received_date' => 'required|date',
        'additional_notes' => 'nullable|string|max:500',
    ]);

    // Validasi yang lebih robust
    if (!$transaction->exists || !$transaction->isTypeMasuk() || !$transaction->isPending()) {
        return redirect()->route('staff.tasks.index')
               ->with('error', 'Transaksi tidak valid atau sudah diproses.');
    }

    DB::beginTransaction();
    try {
        $product = $transaction->product;
        if (!$product) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Produk terkait transaksi ini tidak ditemukan.');
        }

        // Update stok
        $product->increment('current_stock', $request->quantity_received);

        // Update transaksi
        $transaction->update([
            'status' => StockTransaction::STATUS_COMPLETED,
            'quantity' => $request->quantity_received,
            'date' => $request->received_date,
            'processed_by_user_id' => Auth::id(),
            'notes' => ($transaction->notes ? $transaction->notes . "\n" : "") .
                      "Konfirmasi Staff: " . ($request->additional_notes ?? 'Diterima sesuai pesanan.'),
        ]);

        DB::commit();
        return redirect()->route('staff.tasks.index')
               ->with('success', 'Barang masuk berhasil dikonfirmasi dan stok telah diperbarui.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
               ->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
    }
}

    public function rejectIncomingTask(StockTransaction $transaction): RedirectResponse
    {
        if ($transaction->status !== 'pending') {
            return redirect()->route('staff.tasks.index')->with('error', 'Transaksi tidak valid atau sudah diproses.');
        }

        $transaction->update([
            'status' => 'rejected',
            'processed_by_user_id' => Auth::id(),
            'notes' => ($transaction->notes ? $transaction->notes . "\n" : "") .
                     "Ditolak oleh Staff: " . Auth::user()->name . " pada " . now()->format('d M Y H:i'),
        ]);

        return redirect()->route('staff.tasks.index')->with('success', 'Tugas barang masuk berhasil ditolak.');
    }

    // ==========================================================
    // == BAGIAN BARANG KELUAR (OUTGOING)
    // ==========================================================

    public function listOutgoing(): View
    {
        $transactions = StockTransaction::with(['product', 'user', 'processedByUser'])
            ->where('type', 'keluar')
            ->latest()
            ->paginate(15);

        return view('pages.staff.tasks.list_outgoing', compact('transactions'));
    }

    public function showOutgoingPreparationForm(StockTransaction $transaction): View|RedirectResponse
    {
        if ($transaction->type !== 'keluar' || $transaction->status !== 'pending') {
            return redirect()->route('staff.tasks.index')
                ->with('error', 'Tugas tidak valid atau sudah diproses.');
        }

        $product = $transaction->product;
        if (!$product) {
            return redirect()->route('staff.tasks.index')
                ->with('error', 'Produk untuk transaksi ini tidak ditemukan.');
        }

        if ($product->current_stock < $transaction->quantity) {
            session()->flash('warning',
                "Perhatian: Stock saat ini ({$product->current_stock}) kurang dari yang diminta ({$transaction->quantity})."
            );
        }

        return view('pages.staff.tasks.prepare_outgoing', ['task' => $transaction]);
    }

    public function approveOutgoingTask(Request $request, StockTransaction $transaction): RedirectResponse
    {
        $product = $transaction->product;

        if (!$product) {
            return redirect()->route('staff.tasks.index')
                ->with('error', 'Produk untuk transaksi ini tidak ditemukan.');
        }

        $validated = $request->validate([
            'quantity_dispatched' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($product) {
                    if ($value > $product->current_stock) {
                        $fail("Jumlah barang melebihi stock yang tersedia ({$product->current_stock})");
                    }
                }
            ],
            'dispatch_notes' => 'nullable|string|max:1000',
        ], [
            'quantity_dispatched.required' => 'Jumlah barang keluar harus diisi',
            'quantity_dispatched.integer' => 'Jumlah barang harus berupa angka',
            'quantity_dispatched.min' => 'Jumlah barang minimal 1',
            'dispatch_notes.max' => 'Catatan terlalu panjang (maksimal 1000 karakter)',
        ]);

        if ($transaction->type !== 'keluar' || $transaction->status !== 'pending') {
            return redirect()->route('staff.tasks.index')
                ->with('error', 'Transaksi ini tidak bisa diproses lagi.');
        }

        DB::beginTransaction();
        try {
            Log::info('Processing outgoing stock transaction', [
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'current_stock' => $product->current_stock,
                'quantity_dispatched' => $validated['quantity_dispatched'],
                'processed_by' => Auth::id(),
            ]);

            $oldStock = $product->current_stock;

            $product = Product::find($product->id);
            if (!$product) {
                throw new \Exception('Produk tidak ditemukan saat update stock');
            }

            $product->current_stock = $product->current_stock - $validated['quantity_dispatched'];
            $product->save();
            $product->refresh();

            $notes = $transaction->notes ?: '';
            $notes .= "\n\nDISETUJUI OLEH STAFF";
            $notes .= "\nStaff: " . Auth::user()->name;
            $notes .= "\nTanggal: " . now()->format('d M Y H:i:s');
            $notes .= "\nStock Sebelum: " . $oldStock;
            $notes .= "\nJumlah Keluar: " . $validated['quantity_dispatched'];
            $notes .= "\nStock Sesudah: " . $product->current_stock;

            if ($validated['dispatch_notes']) {
                $notes .= "\nCatatan Staff: " . $validated['dispatch_notes'];
            }

            $transaction->update([
                'status' => 'approved',
                'quantity' => $validated['quantity_dispatched'],
                'processed_by_user_id' => Auth::id(),
                'notes' => $notes,
                'processed_at' => now(),
            ]);

            DB::commit();

            Log::info('Outgoing stock transaction approved successfully', [
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'old_stock' => $oldStock,
                'new_stock' => $product->current_stock,
                'quantity_removed' => $validated['quantity_dispatched'],
            ]);

            return redirect()->route('staff.tasks.index')
                ->with('success',
                    "Barang keluar berhasil disetujui! " .
                    "Stock {$product->name} berkurang {$validated['quantity_dispatched']} unit " .
                    "(dari {$oldStock} menjadi {$product->current_stock})"
                );

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to process outgoing stock transaction', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal memproses transaksi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function rejectOutgoingTask(Request $request, StockTransaction $transaction): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ], [
            'rejection_reason.required' => 'Alasan penolakan harus diisi',
            'rejection_reason.max' => 'Alasan penolakan terlalu panjang',
        ]);

        if ($transaction->status !== 'pending') {
            return redirect()->route('staff.tasks.index')
                ->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $notes = $transaction->notes ?: '';
            $notes .= "\n\nDITOLAK OLEH STAFF";
            $notes .= "\nStaff: " . Auth::user()->name;
            $notes .= "\nTanggal: " . now()->format('d M Y H:i:s');
            $notes .= "\nAlasan: " . $validated['rejection_reason'];

            $transaction->update([
                'status' => 'rejected',
                'processed_by_user_id' => Auth::id(),
                'notes' => $notes,
                'processed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('staff.tasks.index')
                ->with('success', 'Transaksi barang keluar berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to reject outgoing stock transaction', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menolak transaksi: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // == BACKWARD COMPATIBILITY METHODS
    // ==========================================================

   public function processOutgoingDispatch(Request $request, StockTransaction $transaction): RedirectResponse
{
    $product = $transaction->product;

    // Validasi dasar
    if (!$product) {
        return redirect()->route('staff.tasks.index')
            ->with('error', 'Produk untuk transaksi ini tidak ditemukan.');
    }

    // Validasi request
    $validated = $request->validate([
        'quantity_dispatched' => [
            'required',
            'integer',
            'min:1',
            'max:' . $product->current_stock,
            function ($attribute, $value, $fail) use ($product) {
                if ($value > $product->current_stock) {
                    $fail("Jumlah barang melebihi stock yang tersedia ({$product->current_stock})");
                }
            }
        ],
        'dispatch_notes' => 'nullable|string|max:1000',
    ]);

    // Validasi transaksi
    if ($transaction->type !== 'keluar' || $transaction->status !== 'pending') {
        return redirect()->route('staff.tasks.index')
            ->with('error', 'Transaksi ini tidak bisa diproses lagi.');
    }

    DB::beginTransaction();
    try {
        $oldStock = $product->current_stock;

        // Update stok produk
        $product->decrement('current_stock', $validated['quantity_dispatched']);

        // Update transaksi
        $notes = $transaction->notes ?: '';
        $notes .= "\n\nDIPROSES OLEH STAFF";
        $notes .= "\nStaff: " . Auth::user()->name;
        $notes .= "\nTanggal: " . now()->format('d M Y H:i:s');
        $notes .= "\nStock Sebelum: " . $oldStock;
        $notes .= "\nJumlah Keluar: " . $validated['quantity_dispatched'];
        $notes .= "\nStock Sesudah: " . $product->current_stock;

        if (!empty($validated['dispatch_notes'])) {
            $notes .= "\nCatatan Staff: " . $validated['dispatch_notes'];
        }

        $transaction->update([
            'status' => 'completed',
            'quantity' => $validated['quantity_dispatched'],
            'processed_by_user_id' => Auth::id(),
            'notes' => $notes,
            'processed_at' => now(),
        ]);

        DB::commit();

        return redirect()->route('staff.tasks.index')
            ->with('success',
                "Barang keluar berhasil diproses! " .
                "Stock {$product->name} berkurang {$validated['quantity_dispatched']} unit " .
                "(dari {$oldStock} menjadi {$product->current_stock})"
            );

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->back()
            ->with('error', 'Gagal memproses transaksi: ' . $e->getMessage())
            ->withInput();
    }
}
}
