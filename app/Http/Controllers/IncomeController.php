<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionAngkringan;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        // FILTER TANGGAL
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $date = $request->date;

        // ================= BARBER =================
        $barberQuery = Transaction::query();

        if ($startDate && $endDate) {
            $barberQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $barberQuery->where('created_at', '>=', $startDate . ' 00:00:00');
        } elseif ($endDate) {
            $barberQuery->where('created_at', '<=', $endDate . ' 23:59:59');
        } elseif ($date) {
            $barberQuery->whereDate('created_at', $date);
        }

        $totalBarber = $barberQuery->sum('total_price');

        // ================= ANGKRINGAN =================
        $angkringanQuery = TransactionAngkringan::query();

        if ($startDate && $endDate) {
            $angkringanQuery->whereBetween('tanggal', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $angkringanQuery->where('tanggal', '>=', $startDate . ' 00:00:00');
        } elseif ($endDate) {
            $angkringanQuery->where('tanggal', '<=', $endDate . ' 23:59:59');
        } elseif ($date) {
            $angkringanQuery->whereDate('tanggal', $date);
        }

        $totalAngkringan = $angkringanQuery->sum('total');

        // ================= TOTAL =================
        $grandTotal = $totalBarber + $totalAngkringan;

        return view('admin.income', compact(
            'totalBarber',
            'totalAngkringan',
            'grandTotal',
            'startDate',
            'endDate',
            'date'
        ));
    }
}