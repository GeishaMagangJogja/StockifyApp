<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ManagerDashboardController;

/*
|--------------------------------------------------------------------------
| Public & Auth Routes
|--------------------------------------------------------------------------
*/

// Halaman utama - redirect ke login jika belum login
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return redirect(match ($user->role) {
            'Admin'          => '/admin/dashboard',
            'Manajer Gudang' => '/manajergudang/dashboard',
            'Staff Gudang'   => '/staff/dashboard',
            default          => '/login',
        });
    }
    return view('layouts.welcome');
})->name('welcome');

// Login & Register pages (hanya untuk guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
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

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        // ambil data dummy dulu, nanti bisa kamu ganti dengan controller logic
        return view('pages.admin.dashboard.index', [
            'totalProducts' => \App\Models\Product::count(),
            'incomingCount' => 10,
            'outgoingCount' => 8,
            'recentUsers' => \App\Models\User::latest()->take(5)->get(),
            'chartData' => [
                'categories' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                'incoming' => [5, 6, 4, 8, 3, 4, 6],
                'outgoing' => [2, 3, 5, 2, 4, 1, 5],
            ]
        ]);
    })->name('admin.dashboard');
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
        // Dashboard
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::get('/products', [ManagerDashboardController::class, 'productList'])->name('products.index');
        Route::get('/products/{product}', [ManagerDashboardController::class, 'productShow'])->name('products.show');

        // Stock Management
        Route::get('/stock/in', [ManagerDashboardController::class, 'stockIn'])->name('stock.in');
        Route::get('/stock/out', [ManagerDashboardController::class, 'stockOut'])->name('stock.out');
        Route::get('/stock/opname', [ManagerDashboardController::class, 'stockOpname'])->name('stock.opname');

        // Suppliers
        Route::get('/suppliers', [ManagerDashboardController::class, 'supplierList'])->name('suppliers.index');

        // Reports
        Route::get('/reports/stock', [ManagerDashboardController::class, 'reportStock'])->name('reports.stock');
        Route::get('/reports/transactions', [ManagerDashboardController::class, 'reportTransactions'])->name('reports.transactions');
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
        // Dashboard
        Route::get('/dashboard', function () {
            return view('pages.staff.dashboard.index');
        })->name('dashboard');

        // Tambahkan route lain untuk staff jika diperlukan
        Route::get('/profile', function () {
            return view('pages.staff.profile.index');
        })->name('profile');
    });

/*
|--------------------------------------------------------------------------
| API Routes (Optional)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);

    Route::middleware('auth')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
    });
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    if (auth()->check()) {
        return redirect('/');
    }
    return redirect('/login');
});
