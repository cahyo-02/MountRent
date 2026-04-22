<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('barangs')->latest()->paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255' // Validasi untuk input bernama 'nama'
        ]);

        Category::create([
            'nama' => $request->nama // Ambil input 'nama' simpan ke kolom 'nama'
        ]);

        return redirect()->route('admin.category.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $category->update($request->all());

        return redirect()->route('admin.category.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.category.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
