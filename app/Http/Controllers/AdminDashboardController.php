<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\TransactionAngkringan;

class AdminDashboardController extends Controller
{
   public function index()
{
    $today = Carbon::today();

    // =========================
    // 🔹 STATISTIK DASHBOARD
    // =========================

    $totalBarber  = Barber::count();
    $totalService = Service::count();

    // =========================
    // 🔹 TOTAL TRANSAKSI (BARBER + ANGKRINGAN)
    // =========================

    $totalTransactionBarber = Transaction::whereDate('created_at', $today)->count();

    $totalTransactionAngkringan = TransactionAngkringan::whereDate('tanggal', $today)->count();

    $totalTransaction = $totalTransactionBarber + $totalTransactionAngkringan;

    // =========================
    // 🔹 TOTAL OMSET HARI INI
    // =========================

    $omsetBarber = Transaction::whereDate('created_at', $today)
        ->sum('total_price'); // pastikan field ini benar

    $omsetAngkringan = TransactionAngkringan::whereDate('tanggal', $today)
        ->sum('total');

    $totalOmset = $omsetBarber + $omsetAngkringan;

    // =========================
    // 🔹 ANTRIAN AKTIF (KASIR)
    // =========================

    $currentBooking = Booking::with(['barber', 'service'])
        ->where('status', 'confirmed')
        ->whereDate('created_at', $today)
        ->orderBy('no_antrian', 'asc')
        ->first();

    // =========================
    // 🔹 DATA MASTER
    // =========================

    $barbers  = Barber::all();
    $services = Service::all();
    $menus    = Menu::with('categoryData')->orderBy('id_menu', 'desc')->get();

    return view('admin.dashboard', compact(
        'totalBarber',
        'totalService',
        'totalTransaction',
        'totalOmset',
        'currentBooking',
        'barbers',
        'services',
        'menus'
    ));
}


    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}