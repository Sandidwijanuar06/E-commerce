<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('store.index')->with('error', 'Keranjang kosong.');

        // Simulasi data alamat default user
        $address = auth()->user()->addresses()->where('is_default', true)->first();
        
        return view('store.checkout.index', compact('cart', 'address'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'address_id' => 'required',
            'courier' => 'required',
            'payment_method' => 'required'
        ]);

        $cart = session()->get('cart');
        
        DB::beginTransaction();
        try {
            // 1. Hitung Total
            $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
            $shipping_cost = 15000; // Contoh statis, bisa dari API RajaOngkir
            $total = $subtotal + $shipping_cost;

            // 2. Buat Header Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'INV-' . strtoupper(uniqid()),
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping_cost,
                'total' => $total,
                'status' => 'pending',
                'shipping_address_snapshot' => auth()->user()->addresses()->find($request->address_id)->toArray(),
            ]);

            // 3. Buat Detail Order Items
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
            }

            DB::commit();
            session()->forget('cart'); // Kosongkan keranjang

            return redirect()->route('customer.order.show', $order->order_number)
                             ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
