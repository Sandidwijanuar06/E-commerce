<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class FulfillmentController extends Controller
{
    public function packingList()
    {
        // Hanya ambil order status 'paid' atau 'processing' yang perlu dipacking
        $orders = Order::whereIn('status', ['paid', 'processing'])
            ->with(['items.variant.product', 'user'])
            ->oldest() // First In First Out (FIFO)
            ->get();

        return view('admin.fulfillment.packing', compact('orders'));
    }

    public function markAsPacked(Order $order)
    {
        $order->update(['status' => 'ready_to_ship']); // Status baru sebelum 'shipped'
        return back()->with('success', "Order #{$order->order_number} is packed and ready for courier.");
    }
}
