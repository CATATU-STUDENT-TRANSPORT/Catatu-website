<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Route;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Trip> */
class TripFactory extends Factory
{
    public function definition(): array
    {
        $vehicle = Vehicle::factory()->create();

        return [
            'route_id' => Route::factory(),
            'vehicle_id' => $vehicle->id,
            'driver_id' => Driver::factory(),
            'departure_time' => fake()->dateTimeBetween('+1 hour', '+7 days'),
            'status' => Trip::STATUS_THRESHOLD_PENDING,
            'seats_total' => $vehicle->capacity,
            'seats_booked' => 0,
        ];
    }
}
