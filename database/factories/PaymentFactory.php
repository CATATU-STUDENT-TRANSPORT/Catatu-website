<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Payment> */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $booking = Booking::factory()->create();

        return [
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id ?? User::factory(),
            'method' => 'mpesa',
            'amount' => $booking->amount,
            'phone' => '+2547'.fake()->numerify('########'),
            'mpesa_checkout_request_id' => 'CO-'.Str::upper(Str::random(12)),
            'status' => Payment::STATUS_PENDING,
        ];
    }
}
