<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Barber::latest()->get();
        return view('admin.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('admin.karyawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Barber::create([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.karyawan.index')
            ->with('success', 'Barber berhasil ditambahkan');
    }

    public function show(Request $request, $id)
    {
        $barber = Barber::findOrFail($id);

        // FILTER TANGGAL
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->endOfMonth()->toDateString();

        // =========================
        // QUERY DARI TRANSACTIONS
        // =========================
        $transactions = $barber->transactions()
            ->whereBetween('created_at', [
                $from . ' 00:00:00',
                $to   . ' 23:59:59'
            ])
            ->selectRaw('
                COUNT(id) as total_orang,
                SUM(total_price) as total_omset
            ')
            ->first();

        return view('admin.karyawan.show', [
            'barber'     => $barber,
            'from'       => $from,
            'to'         => $to,
            'totalOrang' => $transactions->total_orang ?? 0,
            'totalOmset' => $transactions->total_omset ?? 0,
        ]);
    }


    public function edit($id)
    {
        $karyawan = Barber::findOrFail($id);
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $karyawan = Barber::findOrFail($id);
        $karyawan->update([
            'name' => $request->name
        ]);

        return redirect()
            ->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroy($id)
{
    $karyawan = Barber::findOrFail($id);

    if ($karyawan->transactions()->exists()) {
        return redirect()
            ->route('admin.karyawan.index')
            ->with('error', 'Karyawan tidak bisa dihapus karena sudah memiliki transaksi.');
    }

    $karyawan->delete();

    return redirect()
        ->route('admin.karyawan.index')
        ->with('success', 'Karyawan berhasil dihapus.');
}

}