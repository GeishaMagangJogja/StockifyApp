<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Public & Auth Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', fn() => view('pages.practice.index'))
    ->name('index-practice');

// Tampilkan form login & register
Route::get('/login', fn() => view('auth.login'))
    ->name('login');
Route::get('/register', fn() => view('auth.register'))
    ->name('register');

// Proses login, register, logout
Route::post('/login',    [AuthController::class, 'login'])
     ->name('login.process');
Route::post('/register', [AuthController::class, 'register'])
     ->name('register.process');
Route::post('/logout',   [AuthController::class, 'logout'])
     ->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard via controller
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
             ->name('dashboard');

        // Produk
        Route::get('/products', fn() => view('pages.admin.products.index'))
             ->name('products.index');
        Route::get('/products/create', fn() => view('pages.admin.products.create'))
             ->name('products.create');
        // ... store, edit, update, destroy

        // Kategori
        Route::get('/categories', fn() => view('pages.admin.categories.index'))
             ->name('categories.index');
        // ... CRUD kategori

        // Atribut
        Route::get('/attributes', fn() => view('pages.admin.attributes.index'))
             ->name('attributes.index');
        // ... CRUD atribut

        // Stok
        Route::get('/stock/history', fn() => view('pages.admin.stock.history'))
             ->name('stock.history');
        Route::get('/stock/opname', fn() => view('pages.admin.stock.opname'))
             ->name('stock.opname');

        // Supplier
        Route::get('/suppliers', fn() => view('pages.admin.suppliers.index'))
             ->name('suppliers.index');
        // ... CRUD supplier

        // Pengguna
        Route::get('/users', fn() => view('pages.admin.users.index'))
             ->name('users.index');
        // ... CRUD pengguna

        // Laporan
        Route::get('/reports/stock', fn() => view('pages.admin.reports.stock'))
             ->name('reports.stock');
        Route::get('/reports/transactions', fn() => view('pages.admin.reports.transactions'))
             ->name('reports.transactions');

        // Pengaturan
        Route::get('/settings', fn() => view('pages.admin.settings.index'))
             ->name('settings.index');
    });

/*
|--------------------------------------------------------------------------
| Manajer Gudang Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Manajer Gudang'])
    ->prefix('manajergudang')
    ->name('manajergudang.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('pages.manajergudang.dashboard.index'))
             ->name('dashboard');

        Route::get('/products', [ProductController::class, 'index'])
             ->name('products.index');
        Route::get('/products/{id}', [ProductController::class, 'show'])
             ->name('products.show');

        Route::get('/stock/in', [StockTransactionController::class, 'in'])
             ->name('stock.in');
        Route::get('/stock/out', [StockTransactionController::class, 'out'])
             ->name('stock.out');
        Route::get('/stock/opname', [StockTransactionController::class, 'opname'])
             ->name('stock.opname');

        Route::get('/reports/stock', [StockTransactionController::class, 'reportStock'])
             ->name('reports.stock');
        Route::get('/reports/transactions', [StockTransactionController::class, 'reportTransactions'])
             ->name('reports.transactions');
    });

/*
|--------------------------------------------------------------------------
| Staff Gudang Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Staff Gudang'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('pages.staff.dashboard.index'))
             ->name('dashboard');
        // ... routes lain untuk staff
    });
