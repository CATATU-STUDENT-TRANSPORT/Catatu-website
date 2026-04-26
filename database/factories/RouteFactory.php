<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Route> */
class RouteFactory extends Factory
{
    public function definition(): array
    {
        $start = Zone::factory()->create();
        $end = Zone::factory()->create();

        return [
            'code' => 'R-'.Str::upper(fake()->unique()->lexify('????')),
            'start_zone_id' => $start->id,
            'end_zone_id' => $end->id,
            'start_location' => $start->name,
            'end_location' => $end->name,
            'price' => fake()->randomElement([80, 100, 120, 150, 200]),
            'threshold' => fake()->randomElement([8, 10, 12]),
            'estimated_minutes' => fake()->numberBetween(20, 90),
            'is_active' => true,
        ];
    }
}
