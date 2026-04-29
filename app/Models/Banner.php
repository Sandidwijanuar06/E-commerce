<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    protected $fillable = ['title', 'subtitle', 'image_path', 'link_url', 'order_priority', 'is_active'];

    // Helper untuk menampilkan URL gambar secara otomatis
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
