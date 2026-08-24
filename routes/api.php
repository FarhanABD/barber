<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\BarberController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\BarberTransactionController;
use App\Http\Controllers\Api\AngkringanTransactionController;
use App\Http\Controllers\Api\AngkringanExpenseController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;

/*
|--------------------------------------------------------------------------
| API Routes (Mobile)
|--------------------------------------------------------------------------
*/

// =======================
// PUBLIC API (NO TOKEN)
// =======================
Route::post('/login', [ApiAuthController::class, 'login']);
Route::get('/transactions', [TransactionController::class, 'index']);

Route::get('/transactions/barber', [BarberTransactionController::class, 'index']);
Route::get('/transactions/angkringan', [AngkringanTransactionController::class, 'index']);

Route::post('/transaction/barber', [TransactionController::class, 'storeBarber']);
Route::post('/transaction/angkringan', [TransactionController::class, 'storeAngkringan']);

// Angkringan Expenses APIs
Route::get('/angkringan-expenses', [AngkringanExpenseController::class, 'index']);
Route::post('/angkringan-expenses', [AngkringanExpenseController::class, 'store']);
Route::post('/angkringan-expenses/{id}/upload-receipt', [AngkringanExpenseController::class, 'uploadReceipt']);

Route::get('/menus', [MenuController::class, 'index']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/barbers', [BarberController::class, 'index']);

// transaksi POS boleh public dulu



// =======================
// PROTECTED API (SANCTUM)
// =======================
Route::middleware('auth:sanctum')->group(function () {

    // nanti kalau auth sudah bener
    // Route::post('/logout', [ApiAuthController::class, 'logout']);
    // Route::get('/profile', [ApiAuthController::class, 'me']);

});