<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'address',
        'ward',
        'district',
        'city',
        'is_primary',
    ];

    protected $attributes = [
        'is_primary' => false,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
