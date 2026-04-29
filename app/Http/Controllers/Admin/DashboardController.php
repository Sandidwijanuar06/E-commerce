<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $data['total_revenue'] = Order::where('status', 'paid')->sum('total');
        $data['total_orders'] = Order::count();
        $data['total_customers'] = User::where('role', 'customer')->count();
        $data['low_stock_count'] = ProductVariant::withSum('stockLedgers as stock', 'quantity')
            ->having('stock', '<', 10)
            ->count();

        // Data Grafik Penjualan (7 Hari Terakhir)
        $data['sales_chart'] = Order::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as date"), DB::raw('SUM(total) as total'))
            ->where('status', 'paid')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->take(7)
            ->get();

        // Pesanan Terbaru
        $data['recent_orders'] = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', $data);
    }
}