<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug', // Untuk URL cantik, misal: /category/makeup
        'image' // Untuk ikon/gambar kategori yang muncul di halaman utama
    ];

    /**
     * Satu kategori bisa punya banyak produk
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}