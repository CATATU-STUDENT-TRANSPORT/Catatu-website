<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'type', 'latitude', 'longitude', 'campus', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'latitude' => 'float',
        'longitude' => 'float',
    ];
}
