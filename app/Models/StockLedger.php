<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockLedger extends Model
{
    protected $fillable = [
        'product_variant_id', 'warehouse_id', 'quantity', 
        'type', 'reference_type', 'reference_id'
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    // Memungkinkan relasi ke Order atau Purchase secara fleksibel
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
