<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class OrderItem
 * * Merepresentasikan item barang di dalam sebuah pesanan.
 */
class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id', 
        'product_name',
        'price',
        'qty',
        'subtotal',
        'variant'
    ];

    /**
     * Cast attributes ke tipe data yang sesuai.
     * Memastikan angka selalu diolah dengan benar oleh Laravel.
     */
    protected $casts = [
        'price'    => 'decimal:2',
        'subtotal' => 'decimal:2',
        'qty'      => 'integer',
        'order_id' => 'integer',
        'product_id' => 'integer',
    ];

    /**
     * Relasi ke model Order (Satu item milik satu pesanan).
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke model Product.
     * Memungkinkan akses data produk melalui $item->product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}