<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    
   public function index()
{
    $services = Service::orderBy('id', 'desc')->get();
    return view('admin.layanan.index', compact('services'));
}

    public function create()
    {
        return view('admin.layanan.create');
    }

     public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan');
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit($id)
{
    $service = Service::findOrFail($id);
    return view('admin.layanan.edit', compact('service'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:100',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
    ]);

    $service = Service::findOrFail($id);

    $service->update([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
    ]);

    return redirect()
        ->route('admin.services.index')
        ->with('success', 'Layanan berhasil diperbarui');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
{
    $service = Service::findOrFail($id);
    $service->delete();

    return redirect()
        ->route('admin.services.index')
        ->with('success', 'Layanan berhasil dihapus');
}

}