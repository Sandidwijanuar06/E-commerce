<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'subtotal', 'tax', 
        'shipping_cost', 'total', 'status', 'shipping_address_snapshot'
    ];

    protected $casts = [
        'shipping_address_snapshot' => 'array',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_variant_id', 'quantity', 
        'price_at_purchase', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array', // Simpan Nama Produk & Varian saat dibeli
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
