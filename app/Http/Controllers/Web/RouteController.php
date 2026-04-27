<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route;
use App\Models\Trip;
use Illuminate\View\View;

class RouteController extends Controller
{
    public function index(): View
    {
        $routes = Route::query()
            ->with(['startZone', 'endZone'])
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        return view('student.routes.index', ['routes' => $routes]);
    }

    public function show(Route $route): View
    {
        $route->load(['startZone', 'endZone']);

        $trip = $route->trips()
            ->whereIn('status', [
                Trip::STATUS_THRESHOLD_PENDING,
                Trip::STATUS_SCHEDULED,
                Trip::STATUS_BOARDING,
            ])
            ->orderBy('departure_time')
            ->first();

        $taken = [];
        if ($trip) {
            $taken = $trip->bookings()
                ->whereIn('status', [
                    Booking::STATUS_PENDING_PAYMENT,
                    Booking::STATUS_PAID,
                    Booking::STATUS_BOARDED,
                ])
                ->pluck('seats')
                ->flatten()
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        $vehicle = $trip?->vehicle;
        $rows = 8;
        $cols = 4;
        if ($vehicle !== null) {
            $rows = $vehicle->rows ?: $rows;
            $cols = $vehicle->cols ?: $cols;
        }

        return view('student.routes.show', [
            'route' => $route,
            'trip' => $trip,
            'taken' => $taken,
            'rows' => $rows,
            'cols' => $cols,
        ]);
    }
}
