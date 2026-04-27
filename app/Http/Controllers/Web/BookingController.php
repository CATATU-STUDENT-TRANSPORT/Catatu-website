<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route;
use App\Services\Booking\BookingService;
use App\Services\Mpesa\MpesaClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookings,
        private readonly MpesaClient $mpesa,
    ) {}

    public function store(Request $request, Route $route): RedirectResponse
    {
        $data = $request->validate([
            'seats' => ['required', 'string'],
        ]);

        $seats = array_values(array_filter(array_map(
            'strval',
            (array) json_decode($data['seats'], true)
        )));

        if (count($seats) < 1) {
            return back()->withErrors(['seats' => 'Pick at least one seat.']);
        }

        $booking = $this->bookings->createPendingBooking(
            user: $request->user(),
            route: $route,
            seats: $seats,
        );

        return redirect()->route('bookings.show', $booking);
    }

    public function index(Request $request): View
    {
        $bookings = $request->user()->bookings()
            ->with(['route', 'trip'])
            ->latest()
            ->get();

        return view('student.bookings.index', ['bookings' => $bookings]);
    }

    public function show(Request $request, Booking $booking): View
    {
        $this->ensureOwner($request, $booking);

        return view('student.bookings.show', ['booking' => $booking->load(['route', 'trip', 'payments'])]);
    }

    public function pay(Request $request, Booking $booking): RedirectResponse
    {
        $this->ensureOwner($request, $booking);

        $data = $request->validate([
            'phone' => ['required', 'string', 'regex:/^(\+?254|0)7\d{8}$/'],
        ]);

        $this->mpesa->initiate($booking, $data['phone']);

        return back()->with('success', 'STK push sent. Check your phone to complete the payment.');
    }

    public function simulateSuccess(Request $request, Booking $booking): RedirectResponse
    {
        $this->ensureOwner($request, $booking);
        abort_unless(config('app.env') !== 'production', 404);

        $payment = $booking->payments()->latest()->first();
        if ($payment) {
            $this->mpesa->simulateSuccessCallback($payment);
        }

        return back()->with('success', 'Simulated M-Pesa success callback.');
    }

    protected function ensureOwner(Request $request, Booking $booking): void
    {
        if ($booking->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
