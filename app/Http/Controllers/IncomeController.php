<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionAngkringan;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        // FILTER TANGGAL (optional)
        $date = $request->date;

        // ================= BARBER =================
        $barberQuery = Transaction::query();

        if ($date) {
            $barberQuery->whereDate('created_at', $date);
        }

        $totalBarber = $barberQuery->sum('total_price');

        // ================= ANGKRINGAN =================
        $angkringanQuery = TransactionAngkringan::query();

        if ($date) {
            $angkringanQuery->whereDate('tanggal', $date);
        }

        $totalAngkringan = $angkringanQuery->sum('total');

        // ================= TOTAL =================
        $grandTotal = $totalBarber + $totalAngkringan;

        return view('admin.income', compact(
            'totalBarber',
            'totalAngkringan',
            'grandTotal',
            'date'
        ));
    }
}