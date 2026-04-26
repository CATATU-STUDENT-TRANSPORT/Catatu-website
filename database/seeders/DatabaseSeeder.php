<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\PromoCode;
use App\Models\Route;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Demo accounts — passwords all 'password'.
        User::factory()->superAdmin()->create([
            'name' => 'Super Admin',
            'email' => 'admin@catatu.test',
            'username' => 'admin',
        ]);

        User::factory()->admin()->create([
            'name' => 'Ops Admin',
            'email' => 'ops@catatu.test',
            'username' => 'ops',
        ]);

        $studentUser = User::factory()->student()->create([
            'name' => 'Esther Kadenge',
            'email' => 'esther.demo@strathmore.edu',
            'username' => 'esther',
        ]);

        // Zones
        $zones = Zone::factory()->count(6)->create();

        // Vehicles + drivers
        $vehicles = Vehicle::factory()->count(4)->create();
        foreach ($vehicles as $vehicle) {
            $driverUser = User::factory()->driver()->create([
                'email' => 'driver.'.$vehicle->id.'@catatu.test',
                'username' => 'driver'.$vehicle->id,
            ]);
            Driver::factory()->create([
                'user_id' => $driverUser->id,
                'vehicle_id' => $vehicle->id,
                'availability' => 'online',
            ]);
        }

        // Routes between zones
        $samples = [
            ['start' => 0, 'end' => 1, 'name' => 'Kilimani → Strathmore', 'price' => 100, 'threshold' => 10],
            ['start' => 2, 'end' => 1, 'name' => 'Westlands → Strathmore', 'price' => 120, 'threshold' => 10],
            ['start' => 3, 'end' => 4, 'name' => 'South B → USIU', 'price' => 150, 'threshold' => 12],
            ['start' => 5, 'end' => 4, 'name' => 'Rongai → USIU', 'price' => 200, 'threshold' => 12],
        ];

        foreach ($samples as $i => $sample) {
            $route = Route::factory()->create([
                'code' => 'R-CAT'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT),
                'start_zone_id' => $zones[$sample['start']]->id,
                'end_zone_id' => $zones[$sample['end']]->id,
                'start_location' => $zones[$sample['start']]->name,
                'end_location' => $zones[$sample['end']]->name,
                'price' => $sample['price'],
                'threshold' => $sample['threshold'],
            ]);

            // A scheduled trip 2 hours out
            $driver = Driver::inRandomOrder()->first();
            $trip = Trip::factory()->create([
                'route_id' => $route->id,
                'vehicle_id' => $driver?->vehicle_id,
                'driver_id' => $driver?->id,
                'departure_time' => now()->addHours(2 + $i),
                'status' => Trip::STATUS_THRESHOLD_PENDING,
                'seats_total' => $driver?->vehicle->capacity ?? 32,
                'seats_booked' => 0,
            ]);

            // A couple of pending bookings so KPI widgets have data
            Booking::factory()->count(2)->create([
                'user_id' => $studentUser->id,
                'route_id' => $route->id,
                'trip_id' => $trip->id,
                'amount' => $route->price,
            ]);
        }

        PromoCode::factory()->create([
            'code' => 'WELCOME10',
            'type' => 'percent',
            'value' => 10,
            'is_active' => true,
        ]);
    }
}
