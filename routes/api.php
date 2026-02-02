<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\BarberTransactionController;
use App\Http\Controllers\Api\AngkringanTransactionController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;

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

Route::get('/menus', [MenuController::class, 'index']);

Route::get('/services', [ServiceController::class, 'index']);


});