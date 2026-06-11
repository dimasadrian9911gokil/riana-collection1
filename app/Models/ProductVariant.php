<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    // Pastikan ini sesuai dengan kolom di tabel product_variants Anda
    protected $fillable = [
        'product_id',
        'name',
        'image_path',
        'price_modifier',
        'stock',
        'description',
        'how_to_use',
        'ingredients'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}