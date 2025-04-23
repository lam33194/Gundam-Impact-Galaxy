<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
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

    protected $appends = ['average_rating', 'total_comments', 'total_ratings'];

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

    // Accessor cho trung bình rating
    public function getAverageRatingAttribute(): float
    {
        return round($this->comments()->whereNotNull('rating')->avg('rating'), 2) ?? 0;
    }

    // Accessor cho tổng lượt đánh giá
    public function getTotalRatingsAttribute(): int
    {
        return $this->comments()->whereNotNull('rating')->count();
    }

    // Accessor cho tổng số bình luận
    public function getTotalCommentsAttribute(): int
    {
        return $this->comments()->whereNotNull('content')->count();
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scope
    public function scopeNameFilter($query, $name)
    {
        return $query->where('name', 'LIKE', "%$name%");
    }
    public function scopeSlugFilter($query, $slug)
    {
        return $query->where('slug', 'LIKE', "%$slug%");
    }
    public function scopeSkuFilter($query, $sku)
    {
        return $query->where('sku', $sku);
    }
    public function scopePriceRangeFilter($query, $minPrice, $maxPrice)
    {
        if ($minPrice !== null) {
            $query->where('price_regular', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('price_regular', '<=', $maxPrice);
        }
        return $query;
    }

    // Mutator đặt is_good_deal và is_hot_deal
    // public function setPriceSaleAttribute($value)
    // {
    //     $this->attributes['price_sale'] = $value;

    //     if ($this->price_regular > 0 && $value > 0) {
    //         $discount = (($this->price_regular - $value) / $this->price_regular) * 100;
    //         $this->attributes['is_hot_deal'] = $discount >= 30;
    //         $this->attributes['is_good_deal'] = $discount >= 10 && $discount < 30;
    //     }
    // }
}
