<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run()
    {
        // 1. Warehouse
        $wh = Warehouse::firstOrCreate(
            ['code' => 'WH-JKT-001'],
            [
                'name' => 'Gudang Pusat Jakarta',
                'address' => 'Jl. Hub Industri No. 10, Jakarta'
            ]
        );

        // 2. Tambahkan Banner (Karena tabel banners punya kolom image_path)
        Banner::create([
            'title' => 'Summer Mega Sale',
            'subtitle' => 'Diskon hingga 70% untuk produk pilihan',
            'image_path' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=1200',
            'is_active' => true,
            'order_priority' => 1
        ]);

        // 3. Data Catalog
        $data = [
            [
                'category' => 'Gadget & Elektronik',
                'products' => [
                    ['name' => 'MacBook Pro M3', 'price' => 25999000],
                    ['name' => 'iPhone 15 Pro', 'price' => 18999000],
                    ['name' => 'Sony WH-1000XM5', 'price' => 5499000],
                ]
            ],
            [
                'category' => 'Fashion Pria',
                'products' => [
                    ['name' => 'Jaket Denim Vintage', 'price' => 450000],
                    ['name' => 'Sepatu Sneakers Putih', 'price' => 899000],
                ]
            ]
        ];

        foreach ($data as $item) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($item['category'])],
                ['name' => $item['category']]
            );

            foreach ($item['products'] as $p) {
                // Sesuai SQL Anda: Tidak ada kolom image/image_path di tabel products
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $p['name'],
                    'slug' => Str::slug($p['name']) . '-' . Str::random(5),
                    'description' => "Produk premium {$p['name']}.",
                    'is_active' => true
                ]);

                // Buat Varian (Di sini harga disimpan)
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => strtoupper(Str::random(12)),
                    'price' => $p['price'],
                    'weight' => rand(500, 2000),
                    'variant_values' => json_encode(['pilihan' => 'Default'])
                ]);

                // Stok
                DB::table('stock_ledgers')->insert([
                    'product_variant_id' => $variant->id,
                    'warehouse_id' => $wh->id,
                    'quantity' => rand(20, 100),
                    'type' => 'in',
                    'reference_type' => 'initial_seed',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}