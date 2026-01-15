<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MitraController extends Controller
{
    public function index()
    {
        $mitras = Mitra::orderBy('id_mitra', 'desc')->get();
        return view('admin.mitras.index', compact('mitras'));
    }

    public function create()
    {
        return view('admin.mitras.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'kontak'     => 'nullable|string|max:100',
            'alamat'     => 'nullable|string',
            'status'     => 'required|boolean',
        ]);

        Mitra::create($request->all());

        return redirect()->route('admin.mitras.index')
            ->with('success', 'Mitra berhasil ditambahkan');
    }

    public function edit($id)
    {
        $mitra = Mitra::findOrFail($id);
        return view('admin.mitras.edit', compact('mitra'));
    }

    public function update(Request $request, $id)
    {
        $mitra = Mitra::findOrFail($id);

        $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'kontak'     => 'nullable|string|max:100',
            'alamat'     => 'nullable|string',
            'status'     => 'required|boolean',
        ]);

        $mitra->update($request->all());

        return redirect()->route('admin.mitras.index')
            ->with('success', 'Mitra berhasil diperbarui');
    }

    public function destroy($id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->delete();

        return redirect()->route('admin.mitras.index')
            ->with('success', 'Mitra berhasil dihapus');
    }
    public function show(Request $request, $id)
{
    $mitra = Mitra::findOrFail($id);

    $from = $request->from;
    $to   = $request->to;

    $query = DB::table('transaction_items_angkringan as ti')
        ->join('menus as m', 'ti.menu_id', '=', 'm.id_menu')
        ->join('transaction_angkringan as t', 'ti.transaction_id', '=', 't.id_transaction')
        ->where('m.mitra_id', $id);

    if ($from && $to) {
        $query->whereBetween('t.tanggal', [
            Carbon::parse($from)->startOfDay(),
            Carbon::parse($to)->endOfDay()
        ]);
    }

    $menus = $query
        ->select(
            'm.nama as nama_menu',
            DB::raw('SUM(ti.qty) as total_terjual'),
            DB::raw('SUM(ti.subtotal) as total_pendapatan')
        )
        ->groupBy('m.id_menu', 'm.nama')
        ->get();

    $grandTotalQty = $menus->sum('total_terjual');
    $grandTotalRp  = $menus->sum('total_pendapatan');

    return view('admin.mitras.show', compact(
        'mitra',
        'menus',
        'from',
        'to',
        'grandTotalQty',
        'grandTotalRp'
    ));
}
}