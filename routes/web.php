<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StaffTaskController;
use App\Http\Controllers\StaffReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;

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

// Auth actions (available for all)
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/login/simple', [AuthController::class, 'simpleLogin'])->name('login.simple'); // Fallback
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

// Auth actions (require authentication)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
});

// ===================================
// ADMIN ROUTES
// ===================================
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Resource Routes
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('reports', ReportController::class);
    Route::resource('users', UserController::class);

    // Users Management (alternative routes)
    Route::get('/users', [AdminDashboardController::class, 'userList'])->name('users.index');
    Route::get('/users/create', [AdminDashboardController::class, 'userCreate'])->name('users.create');
    Route::post('/users', [AdminDashboardController::class, 'userStore'])->name('users.store');
    Route::get('/users/{user}', [AdminDashboardController::class, 'userShow'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminDashboardController::class, 'userEdit'])->name('users.edit');
    Route::put('/users/{user}', [AdminDashboardController::class, 'userUpdate'])->name('users.update');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'userDestroy'])->name('users.destroy');

    // Products Management (alternative routes)
    Route::get('/products', [AdminDashboardController::class, 'productList'])->name('products.index');
    Route::get('/products/create', [AdminDashboardController::class, 'productCreate'])->name('products.create');
    Route::post('/products', [AdminDashboardController::class, 'productStore'])->name('products.store');
    Route::get('/products/{product}', [AdminDashboardController::class, 'productShow'])->name('products.show');
    Route::get('/products/{product}/edit', [AdminDashboardController::class, 'productEdit'])->name('products.edit');
    Route::put('/products/{product}', [AdminDashboardController::class, 'productUpdate'])->name('products.update');
    Route::delete('/products/{product}', [AdminDashboardController::class, 'productDestroy'])->name('products.destroy');

    // Categories Management (alternative routes)
    Route::get('/categories', [AdminDashboardController::class, 'categoryList'])->name('categories.index');
    Route::get('/categories/create', [AdminDashboardController::class, 'categoryCreate'])->name('categories.create');
    Route::post('/categories', [AdminDashboardController::class, 'categoryStore'])->name('categories.store');
    Route::get('/categories/{category}', [AdminDashboardController::class, 'categoryShow'])->name('categories.show');
    Route::get('/categories/{category}/edit', [AdminDashboardController::class, 'categoryEdit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminDashboardController::class, 'categoryUpdate'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminDashboardController::class, 'categoryDestroy'])->name('categories.destroy');

    // Suppliers Management (alternative routes)
    Route::get('/suppliers', [AdminDashboardController::class, 'supplierList'])->name('suppliers.index');
    Route::get('/suppliers/create', [AdminDashboardController::class, 'supplierCreate'])->name('suppliers.create');
    Route::post('/suppliers', [AdminDashboardController::class, 'supplierStore'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [AdminDashboardController::class, 'supplierShow'])->name('suppliers.show');
    Route::get('/suppliers/{supplier}/edit', [AdminDashboardController::class, 'supplierEdit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [AdminDashboardController::class, 'supplierUpdate'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [AdminDashboardController::class, 'supplierDestroy'])->name('suppliers.destroy');

    // System Reports
    Route::get('/reports', [AdminDashboardController::class, 'reportIndex'])->name('reports.index');
    Route::get('/reports/users', [AdminDashboardController::class, 'reportUsers'])->name('reports.users');
    Route::get('/reports/system', [AdminDashboardController::class, 'reportSystem'])->name('reports.system');
    Route::get('/reports/system', [ReportController::class, 'system'])->name('reports.system');
    Route::get('/reports/users', [ReportController::class, 'users'])->name('reports.users');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

// ===================================
// MANAJER GUDANG ROUTES
// ===================================
Route::middleware(['auth', 'role:Manajer Gudang'])->prefix('manajergudang')->name('manajergudang.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');

    // Products Management (Read Only)
    Route::resource('products', ProductController::class);
    Route::get('/products', [ManagerDashboardController::class, 'productList'])->name('products.index');
    Route::get('/products/{product}', [ManagerDashboardController::class, 'productShow'])->name('products.show');

    // Stock Management
    Route::get('/stock', [ManagerDashboardController::class, 'stockIndex'])->name('stock.index');
    Route::get('/stock/in', [ManagerDashboardController::class, 'stockIn'])->name('stock.in');
    Route::post('/stock/in', [ManagerDashboardController::class, 'stockInStore'])->name('stock.in.store');
    Route::get('/stock/out', [ManagerDashboardController::class, 'stockOut'])->name('stock.out');
    Route::post('/stock/out', [ManagerDashboardController::class, 'stockOutStore'])->name('stock.out.store');
    Route::get('/stock/opname', [ManagerDashboardController::class, 'stockOpname'])->name('stock.opname');
    Route::post('/stock/opname', [ManagerDashboardController::class, 'stockOpnameStore'])->name('stock.opname.store');
    Route::get('/stock/history', [ManagerDashboardController::class, 'stockHistory'])->name('stock.history');

    // Transactions
    Route::get('/transactions', [ManagerDashboardController::class, 'transactionList'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [ManagerDashboardController::class, 'transactionShow'])->name('transactions.show');
    Route::get('/transactions/create/in', [ManagerDashboardController::class, 'transactionCreateIn'])->name('transactions.create.in');
    Route::get('/transactions/create/out', [ManagerDashboardController::class, 'transactionCreateOut'])->name('transactions.create.out');
    Route::post('/transactions', [ManagerDashboardController::class, 'transactionStore'])->name('transactions.store');
    Route::put('/transactions/{transaction}/approve', [ManagerDashboardController::class, 'transactionApprove'])->name('transactions.approve');
    Route::put('/transactions/{transaction}/reject', [ManagerDashboardController::class, 'transactionReject'])->name('transactions.reject');
    Route::post('/{transaction}/complete', [StaffTaskController::class, 'processIncomingConfirmation'])->name('complete');

    // Suppliers (Read Only)
    Route::get('/suppliers', [ManagerDashboardController::class, 'supplierList'])->name('suppliers.index');
    Route::get('/suppliers/{supplier}', [ManagerDashboardController::class, 'supplierShow'])->name('suppliers.show');

    // Reports
    Route::get('/reports', [ManagerDashboardController::class, 'reportIndex'])->name('reports.index');
    Route::get('/reports/stock', [ManagerDashboardController::class, 'reportStock'])->name('reports.stock');
    Route::get('/reports/transactions', [ManagerDashboardController::class, 'reportTransactions'])->name('reports.transactions');
    Route::get('/reports/inventory', [ManagerDashboardController::class, 'reportInventory'])->name('reports.inventory');
    Route::post('/reports/export', [ManagerDashboardController::class, 'reportExport'])->name('reports.export');

    // Staff Management
    Route::get('/staff', [ManagerDashboardController::class, 'staffList'])->name('staff.index');
    Route::get('/staff/{user}', [ManagerDashboardController::class, 'staffShow'])->name('staff.show');
    Route::get('/staff/{user}/tasks', [ManagerDashboardController::class, 'staffTasks'])->name('staff.tasks');
    Route::post('/staff/{user}/assign-task', [ManagerDashboardController::class, 'staffAssignTask'])->name('staff.assign-task');

    // Profile
    Route::get('/profile', [ManagerDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [ManagerDashboardController::class, 'profileUpdate'])->name('profile.update');
});

// ===================================
// STAFF GUDANG ROUTES
// ===================================
Route::middleware(['auth', 'role:Staff Gudang'])->prefix('staff')->name('staff.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    // MANAJEMEN STOK
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::prefix('incoming')->name('incoming.')->group(function () {
            Route::get('/', [StaffTaskController::class, 'listIncoming'])->name('list');
            Route::get('/{transaction}/confirm', [StaffTaskController::class, 'showIncomingConfirmationForm'])->name('confirm');
            Route::post('/{transaction}/complete', [StaffTaskController::class, 'processIncomingConfirmation'])->name('complete');
        });

        Route::prefix('outgoing')->name('outgoing.')->group(function () {
            Route::get('/', [StaffTaskController::class, 'listOutgoing'])->name('list');
            Route::get('/{transaction}/prepare', [StaffTaskController::class, 'showOutgoingPreparationForm'])->name('prepare');
            Route::post('/{transaction}/dispatch', [StaffTaskController::class, 'processOutgoingDispatch'])->name('dispatch');
        });

        // Stock Operations (Limited)
        Route::get('/', [StaffDashboardController::class, 'stockIndex'])->name('index');
        Route::get('/check', [StaffDashboardController::class, 'stockCheck'])->name('check');
        Route::post('/update', [StaffDashboardController::class, 'stockUpdate'])->name('update');
    });

    // LAPORAN
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/incoming', [StaffReportController::class, 'showIncomingReport'])->name('incoming');
        Route::get('/outgoing', [StaffReportController::class, 'showOutgoingReport'])->name('outgoing');
        Route::get('/my-work', [StaffDashboardController::class, 'reportMyWork'])->name('my-work');
    });

    // Products (Read Only)
    Route::get('/products', [StaffDashboardController::class, 'productList'])->name('products.index');
    Route::get('/products/{product}', [StaffDashboardController::class, 'productShow'])->name('products.show');

    // Tasks
    Route::get('/tasks', [StaffDashboardController::class, 'taskList'])->name('tasks.index');
    Route::get('/tasks/{task}', [StaffDashboardController::class, 'taskShow'])->name('tasks.show');
    Route::put('/tasks/{task}/complete', [StaffDashboardController::class, 'taskComplete'])->name('tasks.complete');
    Route::put('/tasks/{task}/update-status', [StaffDashboardController::class, 'taskUpdateStatus'])->name('tasks.update-status');

    // Transactions (View assigned only)
    Route::get('/transactions', [StaffDashboardController::class, 'transactionList'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [StaffDashboardController::class, 'transactionShow'])->name('transactions.show');
    Route::put('/transactions/{transaction}/process', [StaffDashboardController::class, 'transactionProcess'])->name('transactions.process');

    // Profile
    Route::get('/profile', [StaffDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [StaffDashboardController::class, 'profileUpdate'])->name('profile.update');
});

// Tambahkan route ini di dalam group admin routes
Route::post('/admin/products/generate-sku', [AdminDashboardController::class, 'generateSkuApi'])->name('admin.products.generate-sku');

// ===================================
// FALLBACK
// ===================================
Route::fallback(function () {
    if (auth()->check()) {
        return redirect('/');
    }
    return redirect('/login');
});
