<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_name',
        'sku',
        'stock',
        'extra_price'
    ];

    public function variantValues()
    {
        return $this->belongsToMany(VariantValue::class, 'pivot_vv');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
}
