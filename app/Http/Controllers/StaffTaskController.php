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

    // ==========================================================
    // == BAGIAN BARANG MASUK (INCOMING)
    // ==========================================================

    public function listIncoming(): View
    {
        $transactions = StockTransaction::with(['product', 'supplier', 'user', 'processedByUser'])
            ->where('type', 'masuk')
            ->latest()
            ->paginate(15);

        return view('pages.staff.tasks.list_incoming', compact('transactions'));
    }

    public function showIncomingConfirmationForm(StockTransaction $transaction): View|RedirectResponse
    {
        if ($transaction->type !== 'masuk') {
            return redirect()->route('staff.tasks.index')
                ->with('error', 'Transaksi ini bukan jenis barang masuk.');
        }

        if ($transaction->status !== 'pending') {
            return redirect()->route('staff.tasks.index')
                ->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        if (!$transaction->product) {
            return redirect()->route('staff.tasks.index')
                ->with('error', 'Produk untuk transaksi ini tidak ditemukan.');
        }

        return view('pages.staff.tasks.confirm_incoming', ['task' => $transaction]);
    }

    public function approveIncomingTask(Request $request, StockTransaction $transaction): RedirectResponse
    {
        $validated = $request->validate([
            'quantity_received' => 'required|integer|min:1|max:999999',
            'received_date' => 'required|date|before_or_equal:today',
            'additional_notes' => 'nullable|string|max:1000',
        ], [
            'quantity_received.required' => 'Jumlah barang diterima harus diisi',
            'quantity_received.integer' => 'Jumlah barang harus berupa angka',
            'quantity_received.min' => 'Jumlah barang minimal 1',
            'quantity_received.max' => 'Jumlah barang terlalu besar',
            'received_date.required' => 'Tanggal penerimaan harus diisi',
            'received_date.date' => 'Format tanggal tidak valid',
            'received_date.before_or_equal' => 'Tanggal penerimaan tidak boleh lebih dari hari ini',
            'additional_notes.max' => 'Catatan terlalu panjang (maksimal 1000 karakter)',
        ]);

        if ($transaction->type !== 'masuk') {
            return redirect()->route('staff.tasks.index')
                ->with('error', 'Transaksi ini bukan jenis barang masuk.');
        }

        if ($transaction->status !== 'pending') {
            return redirect()->route('staff.tasks.index')
                ->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        $product = $transaction->product;
        if (!$product) {
            return redirect()->route('staff.tasks.index')
                ->with('error', 'Produk untuk transaksi ini tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            Log::info('Processing incoming stock transaction', [
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'current_stock' => $product->current_stock,
                'quantity_received' => $validated['quantity_received'],
                'processed_by' => Auth::id(),
            ]);

            $oldStock = $product->current_stock;

            $product = Product::find($product->id);
            if (!$product) {
                throw new \Exception('Produk tidak ditemukan saat update stock');
            }

            $product->current_stock = $product->current_stock + $validated['quantity_received'];
            $product->save();
            $product->refresh();

            $notes = $transaction->notes ?: '';
            $notes .= "\n\nDISETUJUI OLEH STAFF";
            $notes .= "\nStaff: " . Auth::user()->name;
            $notes .= "\nTanggal: " . now()->format('d M Y H:i:s');
            $notes .= "\nStock Sebelum: " . $oldStock;
            $notes .= "\nJumlah Diterima: " . $validated['quantity_received'];
            $notes .= "\nStock Sesudah: " . $product->current_stock;

            if ($validated['additional_notes']) {
                $notes .= "\nCatatan Staff: " . $validated['additional_notes'];
            }

            $transaction->update([
                'status' => 'approved',
                'quantity' => $validated['quantity_received'],
                'date' => $validated['received_date'],
                'processed_by_user_id' => Auth::id(),
                'notes' => $notes,
                'processed_at' => now(),
            ]);

            DB::commit();

            Log::info('Stock transaction approved successfully', [
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'old_stock' => $oldStock,
                'new_stock' => $product->current_stock,
                'quantity_added' => $validated['quantity_received'],
            ]);

            return redirect()->route('staff.tasks.index')
                ->with('success',
                    "Barang masuk berhasil disetujui! " .
                    "Stock {$product->name} bertambah {$validated['quantity_received']} unit " .
                    "(dari {$oldStock} menjadi {$product->current_stock})"
                );

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to process incoming stock transaction', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal memproses transaksi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function rejectIncomingTask(Request $request, StockTransaction $transaction): RedirectResponse
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
                ->with('success', 'Transaksi barang masuk berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to reject incoming stock transaction', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menolak transaksi: ' . $e->getMessage());
        }
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

    public function processIncomingConfirmation(Request $request, StockTransaction $transaction): RedirectResponse
    {
        return $this->approveIncomingTask($request, $transaction);
    }

    public function processOutgoingDispatch(Request $request, StockTransaction $transaction): RedirectResponse
    {
        return $this->approveOutgoingTask($request, $transaction);
    }
}
