<?php

namespace App\Http\Controllers;

use App\Models\AngkringanExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AngkringanExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = AngkringanExpense::query();

        // Filter tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        } elseif ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date . ' 00:00:00');
        } elseif ($request->filled('end_date')) {
            $query->where('tanggal', '<=', $request->end_date . ' 23:59:59');
        }

        // Filter search (nama pengeluaran / kasir)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pengeluaran', 'like', "%{$search}%")
                  ->orWhere('nama_kasir', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $expenses = $query->latest('tanggal')->paginate(10)->withQueryString();

        return view('admin.angkringan-expense.index', compact('expenses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pengajuan,approved,rejected'
        ]);

        $expense = AngkringanExpense::findOrFail($id);
        $expense->update(['status' => $request->status]);

        return back()->with('success', 'Status pengeluaran berhasil diubah');
    }

    public function destroy($id)
    {
        $expense = AngkringanExpense::findOrFail($id);
        
        if ($expense->bukti_resi && Storage::disk('public')->exists($expense->bukti_resi)) {
            Storage::disk('public')->delete($expense->bukti_resi);
        }

        $expense->delete();

        return back()->with('success', 'Data pengeluaran berhasil dihapus');
    }
}
