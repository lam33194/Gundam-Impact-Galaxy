<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sub_category_id',
        'name',
        'slug',
        'sku',
        'thumb_image',
        'price_regular',
        'price_sale',
        'description',
        'content',
        'views',
        'is_active',
        'is_hot_deal',
        'is_good_deal',
        'is_new',
        'is_show_home'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_hot_deal' => 'boolean',
        'is_good_deal' => 'boolean',
        'is_new' => 'boolean',
        'is_show_home' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => 0,
        'is_hot_deal' => 0,
        'is_good_deal' => 0,
        'is_new' => 0,
        'is_show_home' => 0,
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
