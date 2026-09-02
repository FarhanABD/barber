<?php

namespace App\Http\Controllers;

use App\Models\MasterKaryawan;
use Illuminate\Http\Request;

class MasterKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterKaryawan::query();

        // Filter Divisi
        if ($request->filled('divisi') && $request->divisi !== 'all') {
            $query->where('divisi', $request->divisi);
        }

        // Filter Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Pencarian Nama / Alamat
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $karyawans = $query->orderBy('created_at', 'desc')->get();

        // Statistik Ringkas
        $totalKaryawan = MasterKaryawan::count();
        $totalBarber = MasterKaryawan::where('divisi', 'barber')->count();
        $totalAngkringan = MasterKaryawan::where('divisi', 'angkringan')->count();
        $totalAktif = MasterKaryawan::where('status', 'aktif')->count();

        return view('admin.master-karyawan.index', compact(
            'karyawans',
            'totalKaryawan',
            'totalBarber',
            'totalAngkringan',
            'totalAktif'
        ));
    }

    public function create()
    {
        return view('admin.master-karyawan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'divisi' => 'required|in:barber,angkringan,lainnya',
            'status' => 'required|in:aktif,nonaktif',
            'alamat' => 'nullable|string',
        ], [
            'nama.required'   => 'Nama karyawan wajib diisi.',
            'divisi.required' => 'Pilih divisi karyawan.',
            'status.required' => 'Pilih status karyawan.',
        ]);

        MasterKaryawan::create($validated);

        return redirect()->route('admin.master-karyawan.index')
            ->with('success', 'Data Karyawan berhasil ditambahkan!');
    }

    public function show($id)
    {
        $karyawan = MasterKaryawan::findOrFail($id);
        return view('admin.master-karyawan.show', compact('karyawan'));
    }

    public function edit($id)
    {
        $karyawan = MasterKaryawan::findOrFail($id);
        return view('admin.master-karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = MasterKaryawan::findOrFail($id);

        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'divisi' => 'required|in:barber,angkringan,lainnya',
            'status' => 'required|in:aktif,nonaktif',
            'alamat' => 'nullable|string',
        ], [
            'nama.required'   => 'Nama karyawan wajib diisi.',
            'divisi.required' => 'Pilih divisi karyawan.',
            'status.required' => 'Pilih status karyawan.',
        ]);

        $karyawan->update($validated);

        return redirect()->route('admin.master-karyawan.index')
            ->with('success', 'Data Karyawan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $karyawan = MasterKaryawan::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('admin.master-karyawan.index')
            ->with('success', 'Data Karyawan berhasil dihapus!');
    }
}
