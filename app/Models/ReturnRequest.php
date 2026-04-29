<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRequest extends Model
{
    // Mengarahkan model ke tabel 'returns'
    protected $table = 'returns';

    protected $fillable = [
        'order_id',
        'return_number',
        'reason',
        'evidence_photos',
        'refund_amount',
        'status',
        'admin_note'
    ];

    /**
     * Casting otomatis untuk mempermudah manipulasi data.
     */
    protected $casts = [
        'evidence_photos' => 'array', // Foto akan otomatis jadi array PHP
        'refund_amount' => 'decimal:2',
    ];

    /**
     * Relasi ke Order: Satu retur milik satu pesanan.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Boot function untuk generate otomatis nomor retur.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->return_number = 'RET-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4));
        });
    }
}
