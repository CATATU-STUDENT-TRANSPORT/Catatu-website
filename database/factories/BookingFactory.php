<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Route;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Booking> */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        $route = Route::factory()->create();

        return [
            'reference' => 'CAT-'.Str::upper(Str::random(8)),
            'user_id' => User::factory()->student(),
            'route_id' => $route->id,
            'trip_id' => Trip::factory()->state(['route_id' => $route->id]),
            'seats' => ['A1'],
            'amount' => $route->price,
            'status' => Booking::STATUS_PENDING_PAYMENT,
            'boarding_pin' => (string) fake()->numberBetween(100000, 999999),
            'qr_token' => Str::random(40),
            'hold_expires_at' => now()->addMinutes(10),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn () => ['status' => Booking::STATUS_PAID]);
    }
}
