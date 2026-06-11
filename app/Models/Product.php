<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignable).
     */
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'skin_type',
        'rating',
        'is_pre_order',
        'is_bundle',
        'hair_type',
        'how_to_use',
        'ingredients'
    ];

    /**
     * Konversi tipe data otomatis (Casting).
     */
    protected $casts = [
        'is_pre_order'   => 'boolean',
        'is_bundle'      => 'boolean',
        'price'          => 'decimal:2',
        'rating'         => 'decimal:1',
        'stock'          => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function flashSaleItems(): HasMany
    {
        return $this->hasMany(FlashSaleItem::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |--------------------------------------------------------------------------
    */

    /**
     * Mengakses harga final yang mempertimbangkan diskon flash sale.
     */
    protected function finalPrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                $activeFlashSaleItem = \App\Models\FlashSaleItem::where('product_id', $this->id)
                    ->whereHas('flashSale', function ($q) {
                        $q->where('is_active', true)
                          ->where('start_time', '<=', now())
                          ->where('end_time', '>=', now());
                    })
                    ->first();

                return $activeFlashSaleItem ? (float) $activeFlashSaleItem->discount_price : (float) $this->price;
            }
        );
    }

    /**
     * Mengecek ketersediaan stok produk.
     */
    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}