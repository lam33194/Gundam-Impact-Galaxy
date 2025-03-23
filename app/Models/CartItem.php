<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'product_name',
        'variant_name',
        'sku',
        'extra_price',
        'product_price',
        'quantity',
        'attributes',
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    // Accessor: làm tròn price
    public function getRoundExtraPriceAttribute()
    {
        return round($this->extra_price);
    }
}
