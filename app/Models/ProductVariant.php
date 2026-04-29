<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'sku', 'price', 'weight', 'variant_values'];

    protected $casts = [
        'variant_values' => 'array', // Menyimpan {"warna": "hitam", "size": "L"}
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stockLedgers(): HasMany
    {
        return $this->hasMany(StockLedger::class, 'product_variant_id');
    }

    // Accessor: Menghitung sisa stok secara real-time dari mutasi
    public function getStockAttribute(): int
    {
        return $this->stockLedgers()->sum('quantity');
    }
}
