<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\StockTransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ====================================================================
// PUBLIC API ROUTES (Tidak Perlu Login/Token)
// ====================================================================

// Rute untuk otentikasi
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Endpoint untuk sistem eksternal membuat TUGAS BARU untuk Staff
Route::prefix('tasks')->name('api.tasks.')->group(function () {
    // URL: POST /api/tasks/incoming
    Route::post('/incoming', [StockTransactionController::class, 'storeIncomingFromApi'])->name('incoming');
    
    // URL: POST /api/tasks/outgoing
    Route::post('/outgoing', [StockTransactionController::class, 'storeOutgoingFromApi'])->name('outgoing');
});


// ====================================================================
// PROTECTED ROUTES (Wajib Login/Mengirim Token)
// ====================================================================
Route::middleware('auth:sanctum')->group(function () {

    // Profile - Berlaku untuk semua role yang login
    Route::get('profile', [ProfileController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // ================================
    // ADMIN ONLY ROUTES
    // ================================
    Route::prefix('admin')->middleware('role:Admin')->group(function () {
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('suppliers', SupplierController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('stock-transactions', StockTransactionController::class);
        Route::patch('stock-transactions/{id}/approve', [StockTransactionController::class, 'approve']);
        // ... dan route admin lainnya ...
    });


    // ================================
    // MANAJER GUDANG ROUTES
    // ================================
    Route::prefix('manager')->middleware('role:Manajer Gudang')->group(function () {
        Route::get('dashboard-summary', [StockTransactionController::class, 'dashboardSummary']);
        Route::get('products', [ProductController::class, 'index']);
        // ... dan route manajer lainnya ...
    });

    // ================================
    // STAFF GUDANG ROUTES (API)
    // ================================
    // Untuk saat ini kita biarkan kosong, karena semua fungsionalitas staff
    // sudah ditangani oleh routes/web.php.
    // Anda bisa menambahkannya nanti jika membuat aplikasi mobile untuk staff.
    Route::prefix('staff')->middleware('role:Staff Gudang')->group(function () {
        // Contoh:
        // Route::get('my-tasks', [ApiStaffController::class, 'getMyTasks']);
    });

});