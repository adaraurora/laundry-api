<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Import semua Controller yang dibutuhkan
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Endpoint Autentikasi & Profil (Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// --- API USERS ---
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

// --- API SERVICES (Ini yang tadi kurang) ---
Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);      // Menampilkan semua layanan (untuk Home Android)
    Route::get('/{id}', [ServiceController::class, 'show']);   // Detail satu layanan
    Route::post('/', [ServiceController::class, 'store']);     // Tambah layanan (untuk Admin/Postman)
    Route::put('/{id}', [ServiceController::class, 'update']); // Edit layanan
    Route::delete('/{id}', [ServiceController::class, 'destroy']); // Hapus layanan
});

// --- API ORDERS ---
Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::put('/{id}', [OrderController::class, 'update']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
    Route::put('/{id}/status', [OrderController::class, 'updateStatus']); // Update status laundry
});