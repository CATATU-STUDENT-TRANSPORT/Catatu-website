<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\Mpesa\MpesaClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PaymentController extends Controller
{
    public function __construct(private readonly MpesaClient $mpesa) {}

    public function mpesa(Request $request): JsonResponse
    {
        $data = $request->validate([
            'booking_id' => ['required', 'exists:bookings,id'],
            'phone' => ['required', 'string', 'regex:/^(\+?254|0)7\d{8}$/'],
        ]);

        /** @var Booking $booking */
        $booking = Booking::findOrFail($data['booking_id']);
        if ($booking->user_id !== $request->user()->id) {
            throw new AccessDeniedHttpException;
        }

        $payment = $this->mpesa->initiate($booking, $data['phone']);

        return response()->json([
            'data' => $payment,
            'live' => $this->mpesa->isLive(),
        ], 202);
    }

    public function callback(Request $request): JsonResponse
    {
        $payment = $this->mpesa->handleCallback($request->all());

        // Always 200-OK to Safaricom per Daraja guide.
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted', 'payment_id' => $payment?->id]);
    }
}
