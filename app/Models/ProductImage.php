<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    // Pastikan fillable mencakup kolom yang ada di database
    protected $fillable = ['product_id', 'image_path'];

    // Relasi ke tabel products
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}