<?php

namespace App\Http\Controllers;

use App\Models\AngkringanExpense;
use App\Models\PengeluaranGaji;
use App\Models\Transaction;
use App\Models\TransactionAngkringan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * Helper untuk menghitung metrik laba rugi berdasarkan filter tanggal.
     */
    private function calculateFinancialData(Request $request): array
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $periodType = $request->period ?? 'custom';

        // Shortcut filter periode
        if ($periodType === 'today') {
            $startDate = Carbon::today()->format('Y-m-d');
            $endDate = Carbon::today()->format('Y-m-d');
        } elseif ($periodType === 'this_month') {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($periodType === 'this_year') {
            $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
            $endDate = Carbon::now()->endOfYear()->format('Y-m-d');
        }

        // ================= 1. PEMASUKAN BARBER =================
        $barberQuery = Transaction::query();
        if ($startDate && $endDate) {
            $barberQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $barberQuery->where('created_at', '>=', $startDate . ' 00:00:00');
        } elseif ($endDate) {
            $barberQuery->where('created_at', '<=', $endDate . ' 23:59:59');
        }

        $totalIncomeBarber = (float)$barberQuery->sum('total_price');
        $countTransactionBarber = (int)$barberQuery->count();

        // ================= 2. PEMASUKAN ANGKRINGAN =================
        $angkringanQuery = TransactionAngkringan::query();
        if ($startDate && $endDate) {
            $angkringanQuery->whereBetween('tanggal', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $angkringanQuery->where('tanggal', '>=', $startDate . ' 00:00:00');
        } elseif ($endDate) {
            $angkringanQuery->where('tanggal', '<=', $endDate . ' 23:59:59');
        }

        $totalIncomeAngkringan = (float)$angkringanQuery->sum('total');
        $countTransactionAngkringan = (int)$angkringanQuery->count();

        // Total Pemasukan
        $totalIncome = $totalIncomeBarber + $totalIncomeAngkringan;

        // ================= 3. PENGELUARAN GAJI KARYAWAN =================
        $gajiQuery = PengeluaranGaji::with('karyawan')->where('status', 'dibayar');
        if ($startDate && $endDate) {
            $gajiQuery->whereBetween('tanggal_bayar', [$startDate, $endDate]);
        } elseif ($startDate) {
            $gajiQuery->where('tanggal_bayar', '>=', $startDate);
        } elseif ($endDate) {
            $gajiQuery->where('tanggal_bayar', '<=', $endDate);
        }

        $allPaidGajis = $gajiQuery->get();
        $gajiBarber = (float)$allPaidGajis->filter(function ($g) {
            return $g->karyawan && $g->karyawan->divisi === 'barber';
        })->sum('total_gaji');

        $gajiAngkringan = (float)$allPaidGajis->filter(function ($g) {
            return $g->karyawan && $g->karyawan->divisi === 'angkringan';
        })->sum('total_gaji');

        $gajiLainnya = (float)$allPaidGajis->filter(function ($g) {
            return !$g->karyawan || !in_array($g->karyawan->divisi, ['barber', 'angkringan']);
        })->sum('total_gaji');

        $totalGaji = (float)$allPaidGajis->sum('total_gaji');
        $countSlipGaji = $allPaidGajis->count();

        // ================= 4. PENGELUARAN BAHAN BAKU ANGKRINGAN =================
        $expenseQuery = AngkringanExpense::where('status', 'approved');
        if ($startDate && $endDate) {
            $expenseQuery->whereBetween('tanggal', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $expenseQuery->where('tanggal', '>=', $startDate . ' 00:00:00');
        } elseif ($endDate) {
            $expenseQuery->where('tanggal', '<=', $endDate . ' 23:59:59');
        }

        $totalBahanBaku = (float)$expenseQuery->sum('jumlah');
        $countExpenseItems = (int)$expenseQuery->count();

        // Total Pengeluaran
        $totalExpense = $totalGaji + $totalBahanBaku;

        // ================= 5. LABA / RUGI BERSIH (NET PROFIT) =================
        $netProfit = $totalIncome - $totalExpense;
        $profitMargin = $totalIncome > 0 ? round(($netProfit / $totalIncome) * 100, 1) : 0;

        $financialStatus = 'bep';
        if ($netProfit > 0) {
            $financialStatus = 'profit';
        } elseif ($netProfit < 0) {
            $financialStatus = 'loss';
        }

        return [
            'startDate'                  => $startDate,
            'endDate'                    => $endDate,
            'periodType'                 => $periodType,
            // Incomes
            'totalIncomeBarber'          => $totalIncomeBarber,
            'countTransactionBarber'     => $countTransactionBarber,
            'totalIncomeAngkringan'      => $totalIncomeAngkringan,
            'countTransactionAngkringan' => $countTransactionAngkringan,
            'totalIncome'                => $totalIncome,
            // Expenses
            'gajiBarber'                 => $gajiBarber,
            'gajiAngkringan'             => $gajiAngkringan,
            'gajiLainnya'                => $gajiLainnya,
            'totalGaji'                  => $totalGaji,
            'countSlipGaji'              => $countSlipGaji,
            'totalBahanBaku'             => $totalBahanBaku,
            'countExpenseItems'          => $countExpenseItems,
            'totalExpense'               => $totalExpense,
            // Net Profit / Loss
            'netProfit'                  => $netProfit,
            'profitMargin'               => $profitMargin,
            'financialStatus'            => $financialStatus,
        ];
    }

    public function index(Request $request)
    {
        $data = $this->calculateFinancialData($request);
        return view('admin.income', $data);
    }

    public function downloadPdf(Request $request)
    {
        $data = $this->calculateFinancialData($request);
        $pdf = Pdf::loadView('admin.income-pdf', $data)
                  ->setPaper('a4', 'portrait');

        $filename = 'Laporan-Laba-Rugi-' . ($data['startDate'] ? $data['startDate'] . '-to-' . ($data['endDate'] ?? date('Y-m-d')) : 'All-Time') . '.pdf';
        return $pdf->stream($filename);
    }
}