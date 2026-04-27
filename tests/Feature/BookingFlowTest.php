<?php

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Route;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Zone;

function makeRouteWithTrip(int $threshold = 10): Route
{
    $start = Zone::factory()->create();
    $end = Zone::factory()->create();
    $vehicle = Vehicle::factory()->create();
    $driverUser = User::factory()->driver()->create();
    $driver = Driver::factory()->create([
        'user_id' => $driverUser->id,
        'vehicle_id' => $vehicle->id,
    ]);

    $route = Route::factory()->create([
        'start_zone_id' => $start->id,
        'end_zone_id' => $end->id,
        'price' => 100,
        'threshold' => $threshold,
    ]);

    Trip::factory()->create([
        'route_id' => $route->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'seats_total' => $vehicle->capacity,
        'seats_booked' => 0,
        'departure_time' => now()->addHours(2),
        'status' => Trip::STATUS_THRESHOLD_PENDING,
    ]);

    return $route;
}

it('lists active routes for a logged-in student', function () {
    $route = makeRouteWithTrip();
    $student = User::factory()->student()->create();

    $this->actingAs($student)
        ->get('/routes')
        ->assertOk()
        ->assertSee($route->code);
});

it('creates a pending booking and increments trip seat count', function () {
    $route = makeRouteWithTrip();
    $student = User::factory()->student()->create();

    $this->actingAs($student)
        ->post(route('bookings.store', $route), [
            'seats' => json_encode(['A1', 'A2']),
        ])
        ->assertRedirect();

    $booking = Booking::firstWhere('user_id', $student->id);
    expect($booking)->not->toBeNull();
    expect($booking->status)->toBe(Booking::STATUS_PENDING_PAYMENT);
    expect($booking->seats)->toBe(['A1', 'A2']);
    expect((float) $booking->amount)->toBe(200.0);

    $trip = $route->trips()->first();
    expect($trip->seats_booked)->toBe(2);
});

it('activates the trip when the seat threshold is hit', function () {
    $route = makeRouteWithTrip(threshold: 2);
    $student = User::factory()->student()->create();

    $this->actingAs($student)
        ->post(route('bookings.store', $route), [
            'seats' => json_encode(['B1', 'B2']),
        ])
        ->assertRedirect();

    expect($route->trips()->first()->status)->toBe(Trip::STATUS_SCHEDULED);
});

it('prevents booking seats that are already taken', function () {
    $route = makeRouteWithTrip();
    $student = User::factory()->student()->create();
    $other = User::factory()->student()->create();

    $this->actingAs($other)
        ->post(route('bookings.store', $route), [
            'seats' => json_encode(['C1']),
        ])
        ->assertRedirect();

    $this->actingAs($student)
        ->post(route('bookings.store', $route), [
            'seats' => json_encode(['C1']),
        ]);

    expect(Booking::where('user_id', $student->id)->count())->toBe(0);
});
