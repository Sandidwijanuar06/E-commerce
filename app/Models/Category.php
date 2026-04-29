<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'name', 
        'slug', 
        'parent_id', 
        'attributes_definition'
    ];

    /**
     * Casting tipe data otomatis.
     * Sangat penting untuk 'attributes_definition' agar otomatis menjadi array.
     */
    protected $casts = [
        'attributes_definition' => 'array',
    ];

    /**
     * Relasi ke Produk: Satu kategori memiliki banyak produk.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relasi ke Induk Kategori (Self-referencing):
     * Memungkinkan struktur seperti Elektronik > Smartphone.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relasi ke Anak Kategori:
     * Mengambil semua sub-kategori di bawah kategori ini.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Helper untuk mendapatkan breadcrumb atau nama lengkap hirarki.
     */
    public function getFullNameAttribute(): string
    {
        return $this->parent 
            ? "{$this->parent->name} > {$this->name}" 
            : $this->name;
    }
}