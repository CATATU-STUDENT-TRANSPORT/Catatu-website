<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Vehicle> */
class VehicleFactory extends Factory
{
    public function definition(): array
    {
        $rows = fake()->randomElement([8, 10]);
        $cols = 4;

        return [
            'plate_number' => 'K'.strtoupper(fake()->bothify('??')).fake()->numerify(' ### ').strtoupper(fake()->randomLetter()),
            'make' => fake()->randomElement(['Toyota', 'Isuzu', 'Nissan', 'Mitsubishi']),
            'model' => fake()->randomElement(['Hiace', 'NQR', 'Civilian', 'Rosa']),
            'capacity' => $rows * $cols,
            'rows' => $rows,
            'cols' => $cols,
            'status' => 'active',
        ];
    }
}
