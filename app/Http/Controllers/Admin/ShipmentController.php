<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index()
    {
        // Mengambil order yang sudah memiliki nomor resi (status shipped/completed)
        $shipments = Order::whereNotNull('tracking_number')
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('admin.shipments.index', compact('shipments'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.variant.product'])->findOrFail($id);
        
        // Simulasi data tracking dari API Ekspedisi (JNE/J&T/Sicepat)
        $tracking_logs = [
            ['date' => now()->subHours(2), 'desc' => 'Paket sedang dibawa kurir [Jakarta Selatan]', 'status' => 'On Process'],
            ['date' => now()->subDays(1), 'desc' => 'Paket telah sampai di Warehouse transit [Jakarta]', 'status' => 'Transit'],
            ['date' => now()->subDays(1)->subHours(5), 'desc' => 'Pesanan sedang diproses oleh penjual', 'status' => 'Packed'],
            ['date' => now()->subDays(2), 'desc' => 'Pembayaran berhasil dikonfirmasi', 'status' => 'Paid'],
        ];

        return view('admin.shipments.show', compact('order', 'tracking_logs'));
    }
}