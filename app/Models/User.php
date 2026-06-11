<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [

        'name',
        'email',
        'phone',
        'avatar',
        'birth_date',
        'gender',
        'agree_integrity',
        'password',
        'is_active'

    ];

    protected $hidden = [

        'password',
        'remember_token'

    ];

    protected function casts(): array
    {
        return [

            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'agree_integrity' => 'boolean'

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * User memiliki banyak alamat
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * User memiliki banyak pesanan
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * User memiliki banyak wishlist
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * User memiliki banyak item keranjang
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}