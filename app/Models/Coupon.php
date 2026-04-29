<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_purchase', 
        'limit', 'used', 'start_date', 'end_date', 'is_active'
    ];

    /**
     * Casting tipe data agar end_date & start_date menjadi Carbon Instance.
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Helper untuk mengecek apakah kupon masih berlaku.
     */
    public function isValid(): bool
    {
        return $this->is_active && 
               now()->between($this->start_date, $this->end_date) && 
               $this->used < $this->limit;
    }
}