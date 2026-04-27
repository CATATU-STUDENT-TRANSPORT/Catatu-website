<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    public const STATUS_THRESHOLD_PENDING = 'threshold_pending';

    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_BOARDING = 'boarding';

    public const STATUS_DEPARTED = 'departed';

    public const STATUS_ARRIVED = 'arrived';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'route_id', 'vehicle_id', 'driver_id',
        'departure_time', 'arrived_at', 'status',
        'seats_total', 'seats_booked',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrived_at' => 'datetime',
    ];

    /** @return BelongsTo<Route, $this> */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /** @return BelongsTo<Vehicle, $this> */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /** @return BelongsTo<Driver, $this> */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /** @return HasMany<Booking, $this> */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
