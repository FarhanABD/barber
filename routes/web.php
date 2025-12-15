<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    AuthController,
    AdminDashboardController,
    ServiceController,
    KaryawanController,
    TransactionController
};

/* ================= PUBLIC ================= */

Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/hairstyle', [DashboardController::class, 'hairstyle'])->name('hairstyle');
Route::get('/about', [DashboardController::class, 'about'])->name('about');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');

/* ================= ADMIN ================= */

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [AdminDashboardController::class, 'logout'])
        ->name('logout');

    Route::resource('services', ServiceController::class);
    Route::resource('karyawan', KaryawanController::class);
    // TRANSAKSI
        Route::get('/transactions', [TransactionController::class, 'index'])
            ->name('transactions.index');

        Route::get('/transactions/export', [TransactionController::class, 'export'])
            ->name('transactions.export');

        Route::get('/transactions/create', [TransactionController::class, 'create'])
            ->name('transactions.create');

        Route::post('/transactions', [TransactionController::class, 'store'])
            ->name('transactions.store');

        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
            ->name('transactions.show');

        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])
            ->name('transactions.destroy');
});