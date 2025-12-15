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

    public function show(string $id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}