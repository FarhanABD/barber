<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id_category', 'desc')->get();
        return view('frontend-angkringan.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('frontend-angkringan.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        Category::create([
            'nama'   => $request->nama,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('frontend-angkringan.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'nama'   => $request->nama,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }


    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }

    public function toggleStatus(Category $category)
{
    $category->update([
        'status' => !$category->status
    ]);

    return response()->json([
        'success' => true,
        'status' => $category->status
    ]);
}

}