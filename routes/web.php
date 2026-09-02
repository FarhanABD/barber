<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    AuthController,
    AdminDashboardController,
    ServiceController,
    KaryawanController,
    TransactionController,
    BookingController,
    CategoryController,
    MenuController,
    TransactionAngkringanController,
    MitraController,
    IncomeController,
    AngkringanExpenseController,
    MasterKaryawanController,
    PengeluaranGajiController,
};

/* ================= PUBLIC ================= */
// =================== ROUTE ANGKRINGAN ======================//
Route::get('/angkringan', [DashboardController::class, 'dashboardAngkringan'])->name('dashboard-angkringan');
Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/hairstyle', [DashboardController::class, 'hairstyle'])->name('hairstyle');
Route::get('/about', [DashboardController::class, 'about'])->name('about');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');

Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/bookings', [BookingController::class, 'store'])->name('booking.store');


/* ================= ADMIN ================= */

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');
    Route::post('/logout', [AdminDashboardController::class, 'logout'])
        ->name('logout');
    Route::resource('categories', CategoryController::class);
    Route::resource('menus', MenuController::class);

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

        Route::get('/transactions/{transaction}/pdf',[TransactionController::class, 'downloadPdf'])->name('transactions.pdf');

        Route::get('/transactions/{transaction}/print',[TransactionController::class, 'print'])->name('transactions.print');

        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

    Route::patch('/bookings/{id}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{id}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    Route::patch(
    'categories/{category}/toggle-status',[CategoryController::class, 'toggleStatus'])->name('admin.categories.toggle-status');

    Route::get('/transaction-angkringan/{id}/json', 
    [TransactionAngkringanController::class,'printData'])
    ->name('transaction-angkringan.json');


    Route::get('transaction-angkringan/{id}/print',[TransactionAngkringanController::class, 'print'])->name('transaction-angkringan.print');

    Route::get('/transaction-angkringan/export',[TransactionAngkringanController::class, 'export'])->name('transaction-angkringan.export');


    Route::resource(
        'transaction-angkringan',
        TransactionAngkringanController::class
    );
    Route::get('karyawan/{id}', [KaryawanController::class, 'show'])->name('karyawan.show');

    /* ----- KHUSUS ROLE ADMIN ----- */
    Route::middleware('admin')->group(function () {
        // Pendapatan
        Route::get('/income', [IncomeController::class, 'index'])->name('income');

        // Bahan Baku Angkringan (Expense)
        Route::resource('angkringan-expense', AngkringanExpenseController::class)->only(['index', 'destroy']);
        Route::patch('angkringan-expense/{id}/status', [AngkringanExpenseController::class, 'updateStatus'])->name('angkringan-expense.status');

        // Mitra Angkringan
        Route::resource('mitras', MitraController::class)->names('mitras');
        Route::get('mitras/{id}/show', [MitraController::class, 'show'])->name('mitras.show');

        // Master Karyawan
        Route::resource('master-karyawan', MasterKaryawanController::class);

        // Pengeluaran Gaji
        Route::resource('pengeluaran-gaji', PengeluaranGajiController::class);
        Route::get('pengeluaran-gaji/{id}/print', [PengeluaranGajiController::class, 'print'])->name('pengeluaran-gaji.print');
        Route::get('pengeluaran-gaji/{id}/pdf', [PengeluaranGajiController::class, 'downloadPdf'])->name('pengeluaran-gaji.pdf');
    });
});