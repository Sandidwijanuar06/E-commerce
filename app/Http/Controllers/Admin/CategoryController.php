<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'attributes' => 'nullable|array' // Memastikan input adalah array
        ]);

        // AMBIL DATA ARRAY DARI REQUEST TERLEBIH DAHULU
        $attributes = $request->input('attributes', []);

        Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            // Gunakan array_filter pada variabel $attributes, bukan pada object request
            'attributes_definition' => array_filter($attributes), 
        ]);

        return back()->with('success', 'Category and Specs Defined!');
    }

    // Menampilkan halaman form edit kategori
    public function edit(Category $category)
    {
        // Karena kita menggunakan EAV (JSON), pastikan data terkirim ke view
        return view('admin.categories.edit', compact('category'));
    }

    // Memproses perubahan data kategori
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'attributes' => 'nullable|array'
        ]);

        try {
            $category->update([
                'name' => $request->name,
                'slug' => \Illuminate\Support\Str::slug($request->name),
                // array_filter digunakan untuk menghapus input atribut yang kosong
                'attributes_definition' => array_filter($request->input('attributes', [])),
            ]);

            return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        // Opsional: Cek apakah kategori masih dipakai produk
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Gagal hapus! Kategori ini masih memiliki produk.');
        }

        $category->delete();
        return back()->with('success', 'Category deleted!');
    }
}