<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockTransactionController;

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
    
    Route::get('dashboard-summary', [StockTransactionController::class, 'dashboardSummary']);
    Route::post('stock-transactions/{id}/confirm', [StockTransactionController::class, 'confirm']);
    Route::post('stock-transactions/report', [StockTransactionController::class, 'report']);
});
