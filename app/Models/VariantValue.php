<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'variant_attribute_id',
        'value'
    ];

    // Relations
    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'pivot_vv');
    }
    public function variantAttribute()
    {
        return $this->belongsTo(VariantAttribute::class );
    }
}
