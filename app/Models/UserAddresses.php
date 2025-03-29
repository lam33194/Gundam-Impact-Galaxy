<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddresses extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        "address",
        "ward",
        "district",
        "city",
        "is_primary",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
