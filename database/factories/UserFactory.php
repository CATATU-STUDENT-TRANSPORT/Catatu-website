<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone' => '+2547'.fake()->numerify('########'),
            'password' => static::$password ??= Hash::make('password'),
            'role' => User::ROLE_STUDENT,
            'remember_token' => Str::random(10),
        ];
    }

    public function student(): static
    {
        return $this->state(fn () => [
            'role' => User::ROLE_STUDENT,
            'email' => fake()->unique()->userName().'@strathmore.edu',
            'student_verification_status' => 'pending',
        ]);
    }

    public function driver(): static
    {
        return $this->state(fn () => [
            'role' => User::ROLE_DRIVER,
            'student_verification_status' => 'not_required',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'role' => User::ROLE_ADMIN,
            'student_verification_status' => 'not_required',
        ]);
    }

    public function superAdmin(): static
    {
        return $this->state(fn () => [
            'role' => User::ROLE_SUPER_ADMIN,
            'student_verification_status' => 'not_required',
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
