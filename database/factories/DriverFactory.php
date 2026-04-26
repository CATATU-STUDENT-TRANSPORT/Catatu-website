<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Driver> */
class DriverFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->driver(),
            'vehicle_id' => Vehicle::factory(),
            'license_number' => 'DL-'.fake()->unique()->numerify('########'),
            'license_expiry' => fake()->dateTimeBetween('+6 months', '+5 years'),
            'is_verified' => true,
            'availability' => fake()->randomElement(['online', 'offline']),
            'rating_avg' => fake()->randomFloat(2, 3.5, 5),
            'rating_count' => fake()->numberBetween(0, 200),
            'performance_score' => fake()->randomFloat(2, 60, 99),
        ];
    }
}
