<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\StockTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffTaskController extends Controller
{
    // ==========================================================
    // == PUSAT PENGERJAAN TUGAS (WORKSPACE)
    // ==========================================================
    
    /**
     * Menampilkan halaman pusat pengerjaan tugas (workspace).
     * Hanya menampilkan tugas yang berstatus 'pending'.
     */
    public function index(): View
    {
        // Ambil semua tugas barang masuk yang masih pending
        $incomingTasks = StockTransaction::with('product', 'supplier')
            ->where('type', 'masuk')
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Ambil semua tugas barang keluar yang masih pending
        $outgoingTasks = StockTransaction::with('product')
            ->where('type', 'keluar')
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Tampilkan view workspace dengan data tugas yang pending
        return view('pages.staff.tasks.index', compact('incomingTasks', 'outgoingTasks'));
    }

    // ==========================================================
    // == BAGIAN BARANG MASUK (INCOMING) - SEKARANG BERFUNGSI SEBAGAI RIWAYAT
    // ==========================================================

    /**
     * Menampilkan daftar semua transaksi barang masuk (sebagai riwayat).
     */
    public function listIncoming(): View
    {
        // Menggunakan with() untuk Eager Loading, lebih efisien
        $transactions = StockTransaction::with(['product', 'supplier', 'user'])
            ->where('type', 'masuk')
            ->latest() // Mengurutkan berdasarkan created_at (terbaru)
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
            if (!$product) {
                 DB::rollBack();
                 return redirect()->back()->with('error', 'Produk terkait transaksi ini tidak ditemukan.');
            }
            
            $product->increment('stock', $request->quantity_received);

            $transaction->status = 'completed';
            $transaction->quantity = $request->quantity_received;
            $transaction->date = $request->received_date;
            $transaction->processed_by_user_id = Auth::id();
            
            $originalNotes = $transaction->notes ? $transaction->notes . "\n" : "";
            $transaction->notes = $originalNotes . "Konfirmasi Staff: " . ($request->additional_notes ?? 'Diterima sesuai pesanan.');
            
            $transaction->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses transaksi. Error: ' . $e->getMessage())->withInput();
        }

        // Redirect ke halaman PUSAT TUGAS setelah selesai
        return redirect()->route('staff.tasks.index')->with('success', 'Barang masuk berhasil dikonfirmasi dan stok telah diperbarui.');
    }

    /**
     * Menolak tugas barang masuk yang pending.
     */
    public function rejectIncomingTask(StockTransaction $transaction): RedirectResponse
    {
        if ($transaction->status !== 'pending') {
            return redirect()->route('staff.tasks.index')->with('error', 'Tugas ini tidak bisa diproses lagi.');
        }

        $transaction->status = 'rejected';
        $transaction->notes .= "\n Ditolak oleh Staff: " . Auth::user()->name . " pada " . now()->format('d M Y H:i');
        $transaction->processed_by_user_id = Auth::id();
        $transaction->save();

        // Redirect ke halaman PUSAT TUGAS setelah menolak
        return redirect()->route('staff.tasks.index')->with('success', 'Tugas barang masuk berhasil ditolak.');
    }


    // ==========================================================
    // == BAGIAN BARANG KELUAR (OUTGOING) - SEKARANG BERFUNGSI SEBAGAI RIWAYAT
    // ==========================================================

    /**
     * Menampilkan daftar semua transaksi barang keluar (sebagai riwayat).
     */
    public function listOutgoing(): View
    {
        $transactions = StockTransaction::with(['product', 'user'])
            ->where('type', 'keluar')
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
            $product->decrement('stock', $request->quantity_dispatched);

            $transaction->status = 'completed';
            $transaction->quantity = $request->quantity_dispatched;
            $transaction->processed_by_user_id = Auth::id();
            $transaction->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses transaksi. Error: ' . $e->getMessage())->withInput();
        }

        // Redirect ke halaman PUSAT TUGAS setelah selesai
        return redirect()->route('staff.tasks.index')->with('success', 'Barang keluar berhasil dikonfirmasi dan stok telah diperbarui.');
    }

    /**
     * Menolak tugas barang keluar yang pending.
     */
    public function rejectOutgoingTask(StockTransaction $transaction): RedirectResponse
    {
        if ($transaction->status !== 'pending') {
            return redirect()->route('staff.tasks.index')->with('error', 'Tugas ini tidak bisa diproses lagi.');
        }

        $transaction->status = 'rejected';
        $transaction->notes .= "\n Ditolak oleh Staff: " . Auth::user()->name . " pada " . now()->format('d M Y H:i');
        $transaction->processed_by_user_id = Auth::id();
        $transaction->save();
        
        // Redirect ke halaman PUSAT TUGAS setelah menolak
        return redirect()->route('staff.tasks.index')->with('success', 'Tugas barang keluar berhasil ditolak.');
    }
}