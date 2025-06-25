<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\SupplierController;

Route::name('index-practice')->get('/', function () {
    return view('pages.practice.index');
});

// --- GRUP UNTUK ADMIN ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('pages.admin.dashboard.index');
    })->name('dashboard');

    // Produk
    Route::get('/products', function () { /* Logika Controller */ return view('pages.admin.products.index'); })->name('products.index');
    Route::get('/products/create', function () { /* Logika Controller */ return view('pages.admin.products.create'); })->name('products.create');
    // ... rute CRUD produk lainnya (store, edit, update, destroy)

    // Kategori
    Route::get('/categories', function () { /* Logika Controller */ return view('pages.admin.categories.index'); })->name('categories.index');
    // ... rute CRUD kategori lainnya

    // Atribut
    Route::get('/attributes', function () { /* Logika Controller */ return view('pages.admin.attributes.index'); })->name('attributes.index');
    // ... rute CRUD atribut lainnya

    // Stok
    Route::get('/stock/history', function () { /* Logika Controller */ return view('pages.admin.stock.history'); })->name('stock.history');
    Route::get('/stock/opname', function () { /* Logika Controller */ return view('pages.admin.stock.opname'); })->name('stock.opname');

    // Supplier
    Route::get('/suppliers', function () { /* Logika Controller */ return view('pages.admin.suppliers.index'); })->name('suppliers.index');
    // ... rute CRUD supplier lainnya

    // Pengguna
    Route::get('/users', function () { /* Logika Controller */ return view('pages.admin.users.index'); })->name('users.index');
    // ... rute CRUD pengguna lainnya

    // Laporan
    Route::get('/reports/stock', function () { /* Logika Controller */ return view('pages.admin.reports.stock'); })->name('reports.stock');
    Route::get('/reports/transactions', function () { /* Logika Controller */ return view('pages.admin.reports.transactions'); })->name('reports.transactions');

    // Pengaturan
    Route::get('/settings', function () { /* Logika Controller */ return view('pages.admin.settings.index'); })->name('settings.index');


});


// Auth UI Pages
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('layouts.dashboard');
});


// --- GRUP UNTUK MANAJER GUDANG ---
// ... (buat rute serupa dengan prefix 'manager' dan role 'manager')
Route::middleware(['auth', 'role:manajergudang'])
    ->prefix('manajergudang')
    ->name('manajergudang.')
    ->group(function () {

    // Dashboard (gunakan view langsung)
    Route::get('/dashboard', function () {
        return view('manajergudang.dashboard');
    })->name('dashboard');

    // Produk
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    // Stok
    Route::get('/stock/in', [StockTransactionController::class, 'in'])->name('stock.in');
    Route::get('/stock/out', [StockTransactionController::class, 'out'])->name('stock.out');
    Route::get('/stock/opname', [StockTransactionController::class, 'opname'])->name('stock.opname');


    // Laporan
    Route::get('/reports/stock', [StockTransactionController::class, 'reportStock'])->name('reports.stock');
    Route::get('/reports/transactions', [StockTransactionController::class, 'reportTransactions'])->name('reports.transactions');
});
// --- GRUP UNTUK STAFF GUDANG ---
// ... (buat rute serupa dengan prefix 'staff' dan role 'staff')
