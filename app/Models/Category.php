<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id'
    ];

    // mutator: set slug về dạng lowercase trước khi lưu vào db
    public function setSlugAttribute($value) {
        $this -> attributes['slug'] = strtolower($value);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Danh mục cha
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    
    // Danh mục con
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
