<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\BarberTransactionController;
use App\Http\Controllers\Api\AngkringanTransactionController;

/*
|--------------------------------------------------------------------------
| API Routes (Mobile)
|--------------------------------------------------------------------------
*/

// LOGIN MOBILE
Route::post('/login', [ApiAuthController::class, 'login']);

// PROTECTED
Route::middleware('auth:sanctum')->group(function () {

// BARBER
Route::post('/barber/transaction', [BarberTransactionController::class, 'store']);

// ANGKRINGAN
Route::post('/angkringan/transaction', [AngkringanTransactionController::class, 'store']);

});