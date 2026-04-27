<?php

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Route;
use App\Models\User;
use App\Models\Zone;
use App\Services\Mpesa\MpesaClient;

function freshBooking(int $price = 100): Booking
{
    $route = Route::factory()->create([
        'start_zone_id' => Zone::factory()->create()->id,
        'end_zone_id' => Zone::factory()->create()->id,
        'price' => $price,
    ]);
    $student = User::factory()->student()->create();

    return Booking::factory()->create([
        'user_id' => $student->id,
        'route_id' => $route->id,
        'trip_id' => null,
        'amount' => $price,
        'status' => Booking::STATUS_PENDING_PAYMENT,
    ]);
}

it('initiates a stub M-Pesa STK push', function () {
    $booking = freshBooking();
    /** @var MpesaClient $mpesa */
    $mpesa = app(MpesaClient::class);

    $payment = $mpesa->initiate($booking, '+254712345678');

    expect($payment->status)->toBe(Payment::STATUS_PENDING);
    expect($payment->mpesa_checkout_request_id)->toStartWith('STUB_');
});

it('marks payment success and booking paid on a valid callback', function () {
    $booking = freshBooking(200);
    /** @var MpesaClient $mpesa */
    $mpesa = app(MpesaClient::class);
    $payment = $mpesa->initiate($booking, '+254712345678');

    $mpesa->simulateSuccessCallback($payment);

    $payment->refresh();
    $booking->refresh();

    expect($payment->status)->toBe(Payment::STATUS_SUCCESS);
    expect($payment->mpesa_receipt)->toStartWith('STUB');
    expect($booking->status)->toBe(Booking::STATUS_PAID);
});

it('rejects callbacks where the amount does not match', function () {
    $booking = freshBooking(200);
    /** @var MpesaClient $mpesa */
    $mpesa = app(MpesaClient::class);
    $payment = $mpesa->initiate($booking, '+254712345678');

    $mpesa->handleCallback([
        'Body' => [
            'stkCallback' => [
                'CheckoutRequestID' => $payment->mpesa_checkout_request_id,
                'ResultCode' => 0,
                'ResultDesc' => 'OK',
                'CallbackMetadata' => [
                    'Item' => [
                        ['Name' => 'Amount', 'Value' => 50],
                        ['Name' => 'MpesaReceiptNumber', 'Value' => 'BAD123'],
                        ['Name' => 'PhoneNumber', 'Value' => '254712345678'],
                    ],
                ],
            ],
        ],
    ]);

    $payment->refresh();
    $booking->refresh();
    expect($payment->status)->toBe(Payment::STATUS_FAILED);
    expect($booking->status)->toBe(Booking::STATUS_PENDING_PAYMENT);
});

it('public callback endpoint always 200s for Safaricom', function () {
    $booking = freshBooking();
    /** @var MpesaClient $mpesa */
    $mpesa = app(MpesaClient::class);
    $payment = $mpesa->initiate($booking, '+254712345678');

    $this->postJson('/api/payments/callback', [
        'Body' => [
            'stkCallback' => [
                'CheckoutRequestID' => $payment->mpesa_checkout_request_id,
                'ResultCode' => 0,
                'ResultDesc' => 'OK',
                'CallbackMetadata' => [
                    'Item' => [
                        ['Name' => 'Amount', 'Value' => (float) $payment->amount],
                        ['Name' => 'MpesaReceiptNumber', 'Value' => 'TEST123'],
                        ['Name' => 'PhoneNumber', 'Value' => '254712345678'],
                    ],
                ],
            ],
        ],
    ])
        ->assertOk()
        ->assertJsonPath('ResultCode', 0);
});
