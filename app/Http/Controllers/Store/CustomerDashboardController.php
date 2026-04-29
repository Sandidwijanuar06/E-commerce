<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    /**
     * Ringkasan Dashboard User
     */
    public function index()
    {
        $recentOrders = auth()->user()->orders()->latest()->take(5)->get();
        $wishlistCount = auth()->user()->wishlists()->count();
        
        return view('store.customer.dashboard', compact('recentOrders', 'wishlistCount'));
    }

    /**
     * Riwayat Pesanan
     */
    public function orders()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('store.customer.orders.index', compact('orders'));
    }

    /**
     * Detail Pesanan & Tracking Resi
     */
    public function showOrder($order_number)
    {
        $order = auth()->user()->orders()
            ->with(['items.variant.product'])
            ->where('order_number', $order_number)
            ->firstOrFail();

        return view('store.customer.orders.show', compact('order'));
    }

    /**
     * Manajemen Alamat
     */
    public function settings()
    {
        $user = auth()->user();
        $addresses = $user->addresses;
        return view('store.customer.settings', compact('user', 'addresses'));
    }
}
