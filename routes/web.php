<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StaffTaskController;
use App\Http\Controllers\StaffReportController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// ===================================
// HALAMAN UTAMA & AUTENTIKASI
// ===================================

// Halaman utama, akan mengarahkan berdasarkan role jika sudah login
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return redirect(match ($user->role) {
            'Admin'          => route('admin.dashboard'),
            'Manajer Gudang' => route('manajergudang.dashboard'),
            'Staff Gudang'   => route('staff.dashboard'),
            default          => '/login',
        });
    }
    // Jika belum login, tampilkan halaman welcome
    return view('layouts.welcome');
})->name('welcome');

// Route untuk pengguna yang belum login (guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

// Route logout hanya untuk yang sudah login
Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');


// ===================================
// PROTECTED ROUTES BERDASARKAN ROLE
// ===================================

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // ... (Tambahkan semua route spesifik untuk Admin di sini)
});


/*
|--------------------------------------------------------------------------
| Manajer Gudang Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Manajer Gudang'])->prefix('manajergudang')->name('manajergudang.')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    // ... (Tambahkan semua route spesifik untuk Manajer di sini)
});


/*
|--------------------------------------------------------------------------
| Staff Gudang Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Staff Gudang'])->prefix('staff')->name('staff.')->group(function () {
    
    // DASHBOARD
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    // MANAJEMEN STOK
    Route::prefix('stock')->name('stock.')->group(function () {
        // Barang Masuk (Incoming)
        Route::prefix('incoming')->name('incoming.')->group(function () {
            Route::get('/', [StaffTaskController::class, 'listIncoming'])->name('list');
            Route::get('/{transaction}/confirm', [StaffTaskController::class, 'showIncomingConfirmationForm'])->name('confirm');
            Route::post('/{transaction}/complete', [StaffTaskController::class, 'processIncomingConfirmation'])->name('complete');
        });
        // Barang Keluar (Outgoing)
        Route::prefix('outgoing')->name('outgoing.')->group(function () {
            Route::get('/', [StaffTaskController::class, 'listOutgoing'])->name('list');
            Route::get('/{transaction}/prepare', [StaffTaskController::class, 'showOutgoingPreparationForm'])->name('prepare');
            Route::post('/{transaction}/dispatch', [StaffTaskController::class, 'processOutgoingDispatch'])->name('dispatch');
        });
    });

    // LAPORAN
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/incoming', [StaffReportController::class, 'showIncomingReport'])->name('incoming');
        Route::get('/outgoing', [StaffReportController::class, 'showOutgoingReport'])->name('outgoing');
    });

});


/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect(auth()->check() ? '/' : '/login');
});