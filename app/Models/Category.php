<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Relasi ke model Product.
     * Satu kategori memiliki banyak produk.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
