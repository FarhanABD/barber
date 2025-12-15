<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
   public function index()
{
    // COUNT (untuk statistik)
    $totalBarber      = Barber::count();
    $totalService     = Service::count();
    $totalTransaction = Transaction::count();

    // DATA (untuk dropdown & form)
    $barbers  = Barber::all();
    $services = Service::all();

    return view('admin.dashboard', compact(
        'totalBarber',
        'totalService',
        'totalTransaction',
        'barbers',
        'services'
    ));
}



    public function logout(Request $request)
    {
        // Logout user
        auth()->logout();
        // Invalidasi session
        $request->session()->invalidate();
        // Regenerate token untuk keamanan
        $request->session()->regenerateToken();
        // Arahkan ke halaman login setelah logout
        return redirect('/login');
    }
}