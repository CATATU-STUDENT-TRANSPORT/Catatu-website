<?php

namespace Database\Factories;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Zone> */
class ZoneFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'type' => fake()->randomElement(['pickup', 'dropoff', 'both']),
            'latitude' => fake()->latitude(-1.5, -1.1),
            'longitude' => fake()->longitude(36.7, 37.0),
            'campus' => fake()->randomElement(['Strathmore', 'USIU', 'Kenyatta', null]),
            'is_active' => true,
        ];
    }
}
