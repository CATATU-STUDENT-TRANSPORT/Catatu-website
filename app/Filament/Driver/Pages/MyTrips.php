<?php

namespace App\Filament\Driver\Pages;

use App\Models\Trip;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class MyTrips extends Page
{
    protected string $view = 'filament.driver.pages.my-trips';

    protected static ?string $title = 'My assigned trips';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    public function getTrips(): Collection
    {
        $driverId = auth()->user()?->driver?->id;
        if (! $driverId) {
            return new Collection;
        }

        return Trip::query()
            ->with(['route.startZone', 'route.endZone', 'vehicle', 'bookings.user'])
            ->where('driver_id', $driverId)
            ->whereIn('status', [
                Trip::STATUS_THRESHOLD_PENDING,
                Trip::STATUS_SCHEDULED,
                Trip::STATUS_BOARDING,
                Trip::STATUS_DEPARTED,
            ])
            ->orderBy('departure_time')
            ->get();
    }
}
