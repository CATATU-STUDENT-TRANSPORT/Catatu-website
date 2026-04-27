<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripEvent extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'event', 'latitude', 'longitude', 'meta'];

    protected $casts = [
        'meta' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /** @return BelongsTo<Trip, $this> */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
