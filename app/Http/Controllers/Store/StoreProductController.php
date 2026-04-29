<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

// Nama class harus StoreProductController
class StoreProductController extends Controller 
{
    public function index(Request $request)
    {
        $categories = Category::all(); 

        $query = Product::with(['variants.stockLedgers', 'category'])->where('is_active', true);

        // Pencarian Nama
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        // Filter Kategori
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->whereIn('slug', (array)$request->category);
            });
        }

        // FILTER HARGA (Berdasarkan harga di tabel variants)
        if ($request->filled('min_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        $products = $query->paginate(12)->withQueryString();
        
        return view('store.products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::with(['variants', 'category'])->where('slug', $slug)->firstOrFail();
        return view('store.products.show', compact('product'));
    }
}