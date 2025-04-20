<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_MEMBER = 'member';

    public static $is_active = [
        true => 'Active',
        false => 'No Active'
    ];

    protected $appends = ['total_price'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'role',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // Accessor để lấy tổng tiền giỏ hàng
    public function getTotalPriceAttribute()
    {
        // Eager load
        if (!$this->relationLoaded('cartItems')) {
            $this->load('cartItems.variant.product');
        }

        $total = $this->cartItems->sum(function ($cartItem) {
            $variant = $cartItem->variant;
            $product = $variant->product;

            // if price_sale else price_regular
            $price = $product->price_sale > 0 ? $product->price_sale : $product->price_regular;

            return $price * $cartItem->quantity;
        });

        $this->unsetRelation('cartItems');
        return $total;
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }
}
