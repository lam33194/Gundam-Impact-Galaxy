<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function variantValues()
    {
        return $this->hasMany(VariantValue::class);
    }
}
