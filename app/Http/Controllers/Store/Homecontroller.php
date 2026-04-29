<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)->orderBy('order_priority')->get();
        $categories = Category::all(); // Bisa ditambah limit jika terlalu banyak
        
        // Mengambil produk terbaru dengan relasi variant untuk harga terendah
        $bestSellers = Product::with('variants')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return view('store.index', compact('banners', 'categories', 'bestSellers'));
    }
}
