<?php

// routes/web.php
use Illuminate\Support\Facades\Route;

Route::name('index-practice')->get('/', function () {
    return view('pages.practice.index');
});

// --- GRUP UNTUK ADMIN ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('pages.admin.dashboard.index'); // Sesuaikan path jika berbeda
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

// --- GRUP UNTUK MANAJER GUDANG ---
// ... (buat rute serupa dengan prefix 'manager' dan role 'manager')

// --- GRUP UNTUK STAFF GUDANG ---
// ... (buat rute serupa dengan prefix 'staff' dan role 'staff')
