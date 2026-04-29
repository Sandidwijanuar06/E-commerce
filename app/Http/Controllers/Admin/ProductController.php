<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\StockLedger;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Mengambil produk dengan eager loading varian dan total stok
        $products = Product::with(['category', 'variants' => function($query) {
            $query->withSum('stockLedgers as total_stock', 'quantity');
        }])->latest()->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'required|unique:product_variants,sku',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 2. Handle Upload Gambar
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            // 3. Simpan Produk Utama
            $product = Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . time(),
                'description' => $request->description,
                'image' => $imagePath, // Sesuaikan kolom database Anda
                'is_active' => $request->is_active,
                // Jika ada kolom brand: 'brand' => $request->brand,
            ]);

            // 4. Inisialisasi Warehouse
            $defaultWarehouse = Warehouse::firstOrCreate(
                ['code' => 'WH01'],
                ['name' => 'Main Warehouse', 'address' => 'Default Address']
            );

            // 5. Simpan Varian & Ledger
            foreach ($request->variants as $v) {
                // Validasi JSON string dari input
                $variantValues = json_decode($v['values'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $variantValues = ['label' => $v['values']]; // Fallback jika bukan JSON valid
                }

                $variant = $product->variants()->create([
                    'sku' => $v['sku'],
                    'price' => $v['price'],
                    'weight' => $v['weight'] ?? 0,
                    'variant_values' => $variantValues, 
                ]);

                if ($v['stock'] > 0) {
                    StockLedger::create([
                        'product_variant_id' => $variant->id,
                        'warehouse_id' => $defaultWarehouse->id,
                        'quantity' => $v['stock'],
                        'type' => 'in', // Sesuai enum/tipe di SQL Anda
                        'reference_type' => 'initial_stock',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product Created Successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Menampilkan halaman form edit
    public function edit(Product $product)
    {
        $categories = Category::all();
        // Load varian agar bisa diedit juga
        $product->load('variants'); 
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // 1. Update Image jika ada file baru
            if ($request->hasFile('image')) {
                // Hapus foto lama jika ingin hemat storage (Opsional)
                // if($product->image) Storage::disk('public')->delete($product->image);
                
                $product->image = $request->file('image')->store('products', 'public');
            }

            // 2. Update Data Produk
            $product->update([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'is_active' => $request->is_active,
            ]);

            // 3. Update Harga Varian
            if ($request->has('variants')) {
                foreach ($request->variants as $id => $data) {
                    \App\Models\ProductVariant::where('id', $id)->update([
                        'price' => $data['price']
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // 1. Ambil semua ID varian milik produk ini
            $variantIds = $product->variants()->pluck('id');

            // 2. Hapus data Stock Ledger yang merujuk ke varian produk ini
            // Jika tidak dihapus, database akan menolak penghapusan karena 'Foreign Key Constraint'
            \App\Models\StockLedger::whereIn('product_variant_id', $variantIds)->delete();

            // 3. Hapus foto dari storage jika ada
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }

            // 4. Hapus varian
            $product->variants()->delete();

            // 5. Hapus produk utama
            $product->delete();

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produk dan semua data terkait berhasil dihapus!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            // Log error untuk mempermudah debugging jika masih gagal
            \Log::error("Gagal menghapus produk ID {$product->id}: " . $e->getMessage());
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}