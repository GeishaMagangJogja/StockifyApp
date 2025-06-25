<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\SupplierController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ================================
// PUBLIC ROUTES (No Authentication)
// ================================
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// ================================
// PROTECTED ROUTES (Authentication Required)
// ================================
Route::middleware('auth:sanctum')->group(function () {

    // Profile Routes
    Route::get('profile', [ProfileController::class, 'me']);

    // Resource Routes
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('product_attributes', ProductAttributeController::class);
    Route::apiResource('stock-transactions', StockTransactionController::class);
    Route::apiResource('suppliers', SupplierController::class);
     Route::apiResource('stock-transactions', StockTransactionController::class);
    Route::get('stock-transactions/type/{type}', [StockTransactionController::class, 'filterByType']);
    Route::patch('stock-transactions/{id}/approve', [StockTransactionController::class, 'approve']);

    Route::get('dashboard-summary', [StockTransactionController::class, 'dashboardSummary']);
    Route::middleware(['auth:sanctum', 'role:manajergudang'])
    ->prefix('manajergudang')
    ->group(function () {

    // Dashboard summary data (jika diperlukan)
    Route::get('/dashboard', [\App\Http\Controllers\StockTransactionController::class, 'dashboardSummary']);

    // Produk (readonly)
    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index']);
    Route::get('/products/{id}', [\App\Http\Controllers\ProductController::class, 'show']);

    // Stok
    Route::get('/stock/in', [\App\Http\Controllers\StockTransactionController::class, 'in']);
    Route::get('/stock/out', [\App\Http\Controllers\StockTransactionController::class, 'out']);
    Route::get('/stock/opname', [\App\Http\Controllers\StockTransactionController::class, 'opname']);
    Route::post('/stock/{id}/confirm', [\App\Http\Controllers\StockTransactionController::class, 'confirm']);

    // Supplier (readonly)
    Route::get('/suppliers', [\App\Http\Controllers\SupplierController::class, 'index']);

    // Laporan (via POST jika filter digunakan, atau GET jika hanya view)
    Route::post('/reports/stock', [\App\Http\Controllers\StockTransactionController::class, 'reportStock']);
    Route::post('/reports/transactions', [\App\Http\Controllers\StockTransactionController::class, 'reportTransactions']);
});
});
