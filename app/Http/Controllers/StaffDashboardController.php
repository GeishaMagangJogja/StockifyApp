<?php

namespace App\Http\Controllers;

use App\Models\Product; // Tambahkan ini
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Illuminate\Support\Facades\Hash; // Tambahkan ini
use Illuminate\Support\Facades\Storage; // Tambahkan ini

class StaffDashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama untuk Staff Gudang.
     * [MODIFIKASI] Dashboard kini diperkaya dengan statistik dan widget.
     */
    public function index(): View
    {
        // 1. Ambil TUGAS UTAMA (status 'pending')
        $incomingTasks = StockTransaction::with('product', 'supplier')
            ->where('type', 'masuk')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $outgoingTasks = StockTransaction::with('product')
            ->where('type', 'keluar')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. [BARU] Ambil data untuk kartu statistik
        $incomingTodayCount = $incomingTasks->where('created_at', '>=', today())->count();
        $outgoingTodayCount = $outgoingTasks->where('created_at', '>=', today())->count();
        $totalPendingTasks = $incomingTasks->count() + $outgoingTasks->count();

        // 3. [BARU] Ambil data untuk widget samping (seperti di dashboard lain)
        $lowStockProducts = Product::all()->filter(function ($product) {
            return isset($product->min_stock) && $product->current_stock <= $product->min_stock;
        })->sortBy('current_stock')->take(5);
        
        $recentTransactions = StockTransaction::with('product', 'user')
            ->where('status', '!=', 'pending') // Tampilkan yang sudah selesai
            ->latest('updated_at')
            ->limit(5)
            ->get();

        // Kirim semua data ke view
        return view('pages.staff.dashboard.index', compact(
            'incomingTasks',
            'outgoingTasks',
            'incomingTodayCount',
            'outgoingTodayCount',
            'totalPendingTasks',
            'lowStockProducts',
            'recentTransactions'
        ));
    }

    /**
     * [BARU] Menampilkan halaman edit profil untuk user yang sedang login.
     */
    public function profile()
    {
        return view('pages.profile.edit', ['user' => Auth::user()]);
    }

    /**
     * [BARU] Memproses update profil untuk user yang sedang login.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        
        // Redirect ke halaman profil yang sama dengan notifikasi sukses
        return redirect()->route('staff.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}