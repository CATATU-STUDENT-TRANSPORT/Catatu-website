<?php

namespace Database\Factories;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Incident> */
class IncidentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->student(),
            'severity' => fake()->randomElement(['low', 'medium', 'high']),
            'type' => fake()->randomElement(['safety', 'driver', 'trip', 'other']),
            'description' => fake()->sentence(),
            'status' => 'open',
        ];
    }
}
