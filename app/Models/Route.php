<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'start_zone_id', 'end_zone_id', 'start_location', 'end_location',
        'price', 'threshold', 'estimated_minutes', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'bool',
    ];

    /** @return BelongsTo<Zone, $this> */
    public function startZone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'start_zone_id');
    }

    /** @return BelongsTo<Zone, $this> */
    public function endZone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'end_zone_id');
    }

    /** @return HasMany<Trip, $this> */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    /** @return HasMany<Booking, $this> */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
