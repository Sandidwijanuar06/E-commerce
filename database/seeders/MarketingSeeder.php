<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;
use App\Models\Coupon;

class MarketingSeeder extends Seeder
{
    public function run()
    {
        // Banner untuk Slider Homepage
        Banner::create([
            'title' => 'Ramadan Sale 2026',
            'subtitle' => 'Promo berkah diskon melimpah',
            'image_path' => 'banners/promo-ramadan.jpg',
            'is_active' => true,
            'order_priority' => 1
        ]);

        // Coupon (Sesuai tabel coupons di SQL)
        Coupon::create([
            'code' => 'BERKAH2026',
            'type' => 'percentage',
            'value' => 10.00,
            'min_purchase' => 500000,
            'limit' => 100,
            'used' => 0,
            'start_date' => now(),
            'end_date' => now()->addMonths(1),
            'is_active' => true
        ]);
    }
}
