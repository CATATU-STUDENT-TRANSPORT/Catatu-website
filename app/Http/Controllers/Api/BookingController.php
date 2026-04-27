<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route;
use App\Services\Booking\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BookingController extends Controller
{
    public function __construct(private readonly BookingService $bookings) {}

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'route_id' => ['required', 'exists:routes,id'],
            'seats' => ['required', 'array', 'min:1', 'max:6'],
            'seats.*' => ['string', 'regex:/^[A-Z]\d{1,2}$/'],
        ]);

        $route = Route::findOrFail($data['route_id']);

        $booking = $this->bookings->createPendingBooking(
            user: $request->user(),
            route: $route,
            seats: $data['seats'],
        );

        return response()->json(['data' => $booking], 201);
    }

    public function mine(Request $request): JsonResponse
    {
        $bookings = $request->user()->bookings()
            ->with(['route', 'trip'])
            ->latest()
            ->get();

        return response()->json(['data' => $bookings]);
    }

    public function show(Request $request, Booking $booking): JsonResponse
    {
        $this->authorizeOwnership($request, $booking);
        $booking->load(['route', 'trip', 'payments']);

        return response()->json(['data' => $booking]);
    }

    public function cancel(Request $request, Booking $booking): JsonResponse
    {
        $this->authorizeOwnership($request, $booking);

        $this->bookings->cancel($booking);

        return response()->json(['data' => $booking->refresh()]);
    }

    protected function authorizeOwnership(Request $request, Booking $booking): void
    {
        if ($booking->user_id !== $request->user()->id) {
            throw new AccessDeniedHttpException;
        }
    }
}
