<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Route;
use App\Models\Trip;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalRevenue = (float) Payment::where('status', Payment::STATUS_SUCCESS)->sum('amount');
        $paidBookings = Booking::where('status', Booking::STATUS_PAID)->count();
        $studentCount = User::where('role', User::ROLE_STUDENT)->count();
        $activeRoutes = Route::where('is_active', true)->count();
        $upcomingTrips = Trip::whereIn('status', [
            Trip::STATUS_THRESHOLD_PENDING,
            Trip::STATUS_SCHEDULED,
            Trip::STATUS_BOARDING,
        ])->count();

        return [
            Stat::make('Students', $studentCount)
                ->description('Registered student accounts')
                ->color('emerald'),
            Stat::make('Active routes', $activeRoutes)
                ->description($upcomingTrips.' upcoming trips')
                ->color('sky'),
            Stat::make('Paid bookings', $paidBookings)
                ->description('All-time')
                ->color('amber'),
            Stat::make('Revenue (KES)', number_format($totalRevenue))
                ->description('Successful M-Pesa payments')
                ->color('emerald'),
        ];
    }
}
