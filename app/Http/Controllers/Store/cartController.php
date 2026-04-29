<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('store.cart.index', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);
        $cart = session()->get('cart', []);

        // Jika produk sudah ada di keranjang, tambah quantity
        if(isset($cart[$variant->id])) {
            $cart[$variant->id]['quantity'] += $request->quantity;
        } else {
            // Jika produk baru, masukkan ke session
            $cart[$variant->id] = [
                "name" => $variant->product->name,
                "variant_name" => $variant->name,
                "quantity" => $request->quantity,
                "price" => $variant->price,
                "image" => $variant->product->image_path,
                "sku" => $variant->sku
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return back()->with('success', 'Produk dihapus dari keranjang.');
        }
    }
}
