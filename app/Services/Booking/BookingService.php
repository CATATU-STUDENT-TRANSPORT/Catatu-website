<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\Route;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class BookingService
{
    public const HOLD_MINUTES = 10;

    /**
     * @param  array<int, string>  $seats
     */
    public function createPendingBooking(User $user, Route $route, array $seats, ?Trip $trip = null): Booking
    {
        if (count($seats) < 1) {
            throw new InvalidArgumentException('At least one seat is required.');
        }

        return DB::transaction(function () use ($user, $route, $seats, $trip) {
            $trip ??= $this->resolveTrip($route);
            $this->assertSeatsAvailable($trip, $seats);

            $amount = (float) $route->price * count($seats);

            $booking = Booking::create([
                'reference' => 'CAT-'.Str::upper(Str::random(8)),
                'user_id' => $user->id,
                'route_id' => $route->id,
                'trip_id' => $trip?->id,
                'seats' => $seats,
                'amount' => $amount,
                'status' => Booking::STATUS_PENDING_PAYMENT,
                'boarding_pin' => (string) random_int(100000, 999999),
                'qr_token' => Str::random(40),
                'hold_expires_at' => now()->addMinutes(self::HOLD_MINUTES),
            ]);

            if ($trip) {
                $trip->increment('seats_booked', count($seats));
                $this->maybeActivateThreshold($trip);
            }

            return $booking->refresh();
        });
    }

    public function cancel(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            $booking->status = Booking::STATUS_CANCELLED;
            $booking->save();

            if ($booking->trip) {
                $booking->trip->decrement('seats_booked', count($booking->seats ?? []));
            }

            return $booking;
        });
    }

    /**
     * @param  array<int, string>  $seats
     */
    protected function assertSeatsAvailable(?Trip $trip, array $seats): void
    {
        if (! $trip) {
            return;
        }

        $taken = $trip->bookings()
            ->whereIn('status', [
                Booking::STATUS_PENDING_PAYMENT,
                Booking::STATUS_PAID,
                Booking::STATUS_BOARDED,
            ])
            ->pluck('seats')
            ->flatten()
            ->filter()
            ->values()
            ->all();

        $conflict = array_intersect($seats, $taken);
        if ($conflict !== []) {
            throw new RuntimeException('Seats already taken: '.implode(', ', $conflict));
        }
    }

    protected function resolveTrip(Route $route): ?Trip
    {
        return $route->trips()
            ->whereIn('status', [
                Trip::STATUS_THRESHOLD_PENDING,
                Trip::STATUS_SCHEDULED,
                Trip::STATUS_BOARDING,
            ])
            ->orderBy('departure_time')
            ->first();
    }

    protected function maybeActivateThreshold(Trip $trip): void
    {
        if ($trip->status === Trip::STATUS_THRESHOLD_PENDING
            && $trip->seats_booked >= $trip->route->threshold) {
            $trip->status = Trip::STATUS_SCHEDULED;
            $trip->save();
        }
    }
}
