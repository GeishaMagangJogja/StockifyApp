<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StaffTaskController;
use App\Http\Controllers\StaffReportController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\ManagerDashboardController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// ===================================
// HALAMAN UTAMA & AUTENTIKASI
// ===================================

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
    return view('layouts.welcome');
})->name('welcome');

// GUEST ROUTES
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::get('/register', fn() => view('auth.register'))->name('register');
});

// Auth actions
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ===================================
// ADMIN ROUTES (DIRAPIKAN)
// ===================================
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Menggunakan Route::resource yang lebih bersih
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);

    // Route tambahan untuk Products
    Route::get('/products/{product}/confirm-delete', [ProductController::class, 'confirmDelete'])->name('products.confirm-delete');
    Route::post('/products/generate-sku', [ProductController::class, 'generateSku'])->name('products.generate-sku');
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
        Route::get('/outgoing', [ReportController::class, 'outgoingReport'])->name('outgoing.index');
        Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
    });

    // Settings & Profile
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminDashboardController::class, 'updateProfile'])->name('profile.update');
});


// ===================================
// MANAJER GUDANG ROUTES
// ===================================
Route::middleware(['auth', 'role:Manajer Gudang'])->prefix('manajergudang')->name('manajergudang.')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [ManagerDashboardController::class, 'productList'])->name('products.index');
    Route::get('/stock/history', [ManagerDashboardController::class, 'stockHistory'])->name('stock.history');
    Route::get('/transactions', [ManagerDashboardController::class, 'transactionList'])->name('transactions.index');
    Route::get('/transactions/create/in', [ManagerDashboardController::class, 'transactionCreateIn'])->name('transactions.create.in');
    Route::get('/transactions/create/out', [ManagerDashboardController::class, 'transactionCreateOut'])->name('transactions.create.out');
    Route::post('/transactions', [ManagerDashboardController::class, 'transactionStore'])->name('transactions.store');
    Route::put('/transactions/{transaction}/approve', [ManagerDashboardController::class, 'transactionApprove'])->name('transactions.approve');
    Route::put('/transactions/{transaction}/reject', [ManagerDashboardController::class, 'transactionReject'])->name('transactions.reject');
    Route::get('/reports', [ManagerDashboardController::class, 'reportIndex'])->name('reports.index');
    Route::get('/profile', [ManagerDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [ManagerDashboardController::class, 'updateProfile'])->name('profile.update');
});


// ===================================================================
// == STAFF GUDANG ROUTES (STRUKTUR FINAL) ==
// ===================================================================
Route::middleware(['auth', 'role:Staff Gudang'])->prefix('staff')->name('staff.')->group(function () {
    
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    // GRUP 1: UNTUK MENU "MANAJEMEN STOK" (Sebagai Riwayat)
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/incoming', [StaffTaskController::class, 'listIncoming'])->name('incoming.list');
        Route::get('/outgoing', [StaffTaskController::class, 'listOutgoing'])->name('outgoing.list');
    });

    // GRUP 2: UNTUK MENU "MANAJEMEN TUGAS" DAN SEMUA AKSI
    Route::prefix('tasks')->name('tasks.')->group(function () {
        
        // Route untuk halaman Pusat Tugas (Workspace)
        Route::get('/', [StaffTaskController::class, 'index'])->name('index');
        
        // ======================================================================
        // == PERBAIKAN: MENAMBAHKAN KEMBALI ROUTE .list AGAR TIDAK ERROR ==
        // ======================================================================
        Route::get('/incoming', [StaffTaskController::class, 'listIncoming'])->name('incoming.list'); 
        Route::get('/outgoing', [StaffTaskController::class, 'listOutgoing'])->name('outgoing.list');
        // ======================================================================

        // Halaman form dan proses untuk BARANG MASUK
        Route::get('/incoming/{transaction}/confirm', [StaffTaskController::class, 'showIncomingConfirmationForm'])->name('incoming.confirm');
        Route::post('/incoming/{transaction}/complete', [StaffTaskController::class, 'processIncomingConfirmation'])->name('incoming.complete');
        Route::post('/incoming/{transaction}/reject', [StaffTaskController::class, 'rejectIncomingTask'])->name('incoming.reject');

        // Halaman form dan proses untuk BARANG KELUAR
        Route::get('/outgoing/{transaction}/prepare', [StaffTaskController::class, 'showOutgoingPreparationForm'])->name('outgoing.prepare');
        Route::post('/outgoing/{transaction}/dispatch', [StaffTaskController::class, 'processOutgoingDispatch'])->name('outgoing.dispatch');
        Route::post('/outgoing/{transaction}/reject', [StaffTaskController::class, 'rejectOutgoingTask'])->name('outgoing.reject');
    });

    // Grup Rute untuk Laporan
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/incoming', [StaffReportController::class, 'showIncomingReport'])->name('incoming');
        Route::get('/outgoing', [StaffReportController::class, 'showOutgoingReport'])->name('outgoing');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
    });

    // Rute profil
    Route::get('/profile', [StaffDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [StaffDashboardController::class, 'updateProfile'])->name('profile.update');
});


// ===================================
// FALLBACK
// ===================================
Route::fallback(function () {
    if (auth()->check()) {
        return redirect('/');
    }
    return redirect('/login');
});