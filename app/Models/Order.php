<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'city',
        'province',
        'invoice',
        'subtotal',
        'shipping_cost',
        'admin_fee',
        'discount',
        'total',
        'payment_method',
        'status',
        'courier',
        'tracking_number',
        'snap_token',
        'voucher_id',
        'payment_proof'
    ];

    /**
     * Menambahkan casting tipe data untuk kolom angka.
     * Ini memudahkan Laravel mengelola format desimal/integer.
     */
    protected $casts = [
        'subtotal'      => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'admin_fee'     => 'decimal:2',
        'discount'      => 'decimal:2',
        'total'         => 'decimal:2',
        'created_at'    => 'datetime',
    ];

    /**
     * Relasi ke OrderItem (Satu pesanan punya banyak item).
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke User (Pesanan milik satu user).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper untuk memformat total sebagai mata uang Rupiah.
     * Bisa dipanggil dengan $order->formattedTotal
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp' . number_format($this->total, 0, ',', '.');
    }

    /**
     * Memeriksa dan memperbarui status pesanan jika melewati batas pembayaran 30 menit.
     * Mengembalikan nilai true jika pesanan dibatalkan karena expired.
     */
    public function checkExpiry(): bool
    {
        if ($this->status === 'menunggu_pembayaran' && $this->payment_method !== 'COD') {
            if ($this->created_at->copy()->addMinutes(30)->isPast()) {
                $this->update(['status' => 'dibatalkan']);
                return true;
            }
        }
        return false;
    }
}