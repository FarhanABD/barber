<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['categoryData', 'mitra'])
            ->orderBy('id_menu', 'desc')
            ->get();

        return view('frontend-angkringan.menus.index', compact('menus'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->get();
        $mitras     = Mitra::where('status', true)->get();

        return view('frontend-angkringan.menus.create', compact('categories', 'mitras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'   => 'required|exists:categories,id_category',
            'mitra_id'   => 'nullable|exists:mitras,id_mitra',
            'nama'       => 'required|string|max:255',
            'harga'      => 'required|numeric',
            'hpp'        => 'nullable|numeric',
            'status'     => 'required|boolean',
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png',
            'deskripsi'  => 'nullable|string',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('images/menu', 'public');
        }

        Menu::create([
            'category'  => $request->category,
            'mitra_id'  => $request->mitra_id, // 🔥 mitra
            'nama'      => $request->nama,
            'harga'     => $request->harga,
            'hpp'       => $request->hpp,
            'status'    => $request->status,
            'gambar'    => $gambarPath,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit($id)
    {
        $menu       = Menu::findOrFail($id);
        $categories = Category::where('status', true)->get();
        $mitras     = Mitra::where('status', true)->get();

        return view('frontend-angkringan.menus.edit', compact('menu', 'categories', 'mitras'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'category'   => 'required|exists:categories,id_category',
            'mitra_id'   => 'nullable|exists:mitras,id_mitra',
            'nama'       => 'required|string|max:255',
            'harga'      => 'required|numeric',
            'hpp'        => 'nullable|numeric',
            'status'     => 'required|boolean',
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png',
            'deskripsi'  => 'nullable|string',
        ]);

        if ($request->hasFile('gambar')) {
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }

            $menu->gambar = $request->file('gambar')
                ->store('images/menu', 'public');
        }

        $menu->update([
            'category'  => $request->category,
            'mitra_id'  => $request->mitra_id, // 🔥 mitra
            'nama'      => $request->nama,
            'hpp'       => $request->hpp,
            'harga'     => $request->harga,
            'status'    => $request->status,
            'gambar'    => $menu->gambar,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus');
    }
}