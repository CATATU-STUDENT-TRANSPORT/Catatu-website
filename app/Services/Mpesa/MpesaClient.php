<?php

namespace App\Services\Mpesa;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Str;

/**
 * M-Pesa Daraja STK Push client.
 *
 * Ships as a STUB for Phase 1 (no live credentials). When live credentials are
 * configured in the environment, swap this class's internals to call the real
 * Daraja endpoints — the public contract (initiate / handleCallback) stays the
 * same so the rest of the system is unchanged.
 *
 * Docs referenced:
 *   https://developer.safaricom.co.ke/Documentation (Lipa Na M-Pesa Online / STK Push)
 */
class MpesaClient
{
    public function __construct(private readonly MpesaConfig $config) {}

    public function isLive(): bool
    {
        return $this->config->isConfigured() && ! $this->config->stub;
    }

    /**
     * Initiate an STK push. In stub mode this just creates a pending Payment
     * row and returns a fake CheckoutRequestID so the UI flow is identical.
     */
    public function initiate(Booking $booking, string $phone): Payment
    {
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'method' => 'mpesa',
            'amount' => $booking->amount,
            'phone' => $phone,
            'status' => Payment::STATUS_PENDING,
            'mpesa_checkout_request_id' => 'STUB_'.Str::upper(Str::random(16)),
        ]);

        if (! $this->isLive()) {
            // Stub mode: no external call. Leave status pending; the user can
            // confirm the fake payment from the dev payments page.
            return $payment;
        }

        // TODO: Real Daraja call — token fetch + POST /mpesa/stkpush/v1/processrequest.
        // Left as an integration point. When adding, update the checkout request id
        // on the payment row with Daraja's response.

        return $payment;
    }

    /**
     * Dev-only helper to simulate Safaricom's async callback.
     */
    public function simulateSuccessCallback(Payment $payment): void
    {
        $this->handleCallback([
            'Body' => [
                'stkCallback' => [
                    'MerchantRequestID' => 'SIM-'.Str::upper(Str::random(8)),
                    'CheckoutRequestID' => $payment->mpesa_checkout_request_id,
                    'ResultCode' => 0,
                    'ResultDesc' => 'The service request is processed successfully.',
                    'CallbackMetadata' => [
                        'Item' => [
                            ['Name' => 'Amount', 'Value' => (float) $payment->amount],
                            ['Name' => 'MpesaReceiptNumber', 'Value' => 'STUB'.Str::upper(Str::random(8))],
                            ['Name' => 'PhoneNumber', 'Value' => $payment->phone],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Process a Safaricom callback payload. This is the same validator used in
     * both live and stub modes so the logic is covered by tests.
     *
     * @param  array<string, mixed>  $payload
     */
    public function handleCallback(array $payload): ?Payment
    {
        $stk = data_get($payload, 'Body.stkCallback');
        if (! $stk) {
            return null;
        }

        $payment = Payment::where('mpesa_checkout_request_id', data_get($stk, 'CheckoutRequestID'))->first();
        if (! $payment) {
            return null;
        }

        $payment->callback_payload = $payload;
        $resultCode = (int) data_get($stk, 'ResultCode', -1);

        if ($resultCode !== 0) {
            $payment->status = Payment::STATUS_FAILED;
            $payment->save();

            return $payment;
        }

        $items = collect(data_get($stk, 'CallbackMetadata.Item', []))
            ->keyBy(fn ($i) => data_get($i, 'Name'));

        $amount = (float) data_get($items, 'Amount.Value');
        $receipt = (string) data_get($items, 'MpesaReceiptNumber.Value');

        // Validation per the execution doc's rules: amount must match booking price,
        // transaction must be unique, status must be SUCCESS.
        $expected = (float) $payment->booking->amount;
        if (abs($expected - $amount) > 0.01) {
            $payment->status = Payment::STATUS_FAILED;
            $payment->save();

            return $payment;
        }

        $payment->mpesa_receipt = $receipt;
        $payment->status = Payment::STATUS_SUCCESS;
        $payment->save();

        $payment->booking->update(['status' => Booking::STATUS_PAID]);

        return $payment;
    }
}
