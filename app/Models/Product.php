<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'slug',
        'price',
        'thumbnail',
        'description',
    ];

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cartitem::class);
    }

    // Accessor: làm tròn price
    public function getRoundPriceAttribute()
    {
        return round($this->price);
    }

    // Scope
    public function scopePriceFilter($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeNameFilter($query, $name)
    {
        return $query->where('name','LIKE',"%$name%");
    }
    
    public function scopeSlugFilter($query, $slug)
    {
        return $query->where('name','LIKE',"%$slug%");
    }
}
