@extends('layouts.app')

@section('title', 'Booking '.$booking->reference.' — CATATU')

@section('content')
<section class="max-w-3xl mx-auto px-4 py-10">
    <a href="{{ route('bookings.index') }}" class="text-sm text-zinc-500 hover:text-emerald-700">← My trips</a>

    <div class="mt-4 flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-mono text-zinc-400">{{ $booking->reference }}</p>
            <h1 class="text-2xl font-bold">{{ $booking->route->start_location }} → {{ $booking->route->end_location }}</h1>
        </div>
        <span @class([
            'text-xs font-semibold rounded-full px-3 py-1 whitespace-nowrap',
            'bg-emerald-100 text-emerald-800' => $booking->status === 'paid',
            'bg-amber-100 text-amber-800' => $booking->status === 'pending_payment',
            'bg-rose-100 text-rose-800' => in_array($booking->status, ['cancelled', 'refunded']),
            'bg-zinc-100 text-zinc-700' => ! in_array($booking->status, ['paid', 'pending_payment', 'cancelled', 'refunded']),
        ])>
            {{ str_replace('_', ' ', $booking->status) }}
        </span>
    </div>

    <div class="mt-6 grid sm:grid-cols-3 gap-4 text-sm">
        <div class="rounded-xl border border-zinc-200 p-4 bg-white">
            <p class="text-zinc-500">Seats</p>
            <p class="font-semibold">{{ implode(', ', $booking->seats ?? []) ?: '—' }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 p-4 bg-white">
            <p class="text-zinc-500">Amount</p>
            <p class="font-semibold">KES {{ number_format((float) $booking->amount) }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 p-4 bg-white">
            <p class="text-zinc-500">Boarding PIN</p>
            <p class="font-semibold tracking-wider">{{ $booking->boarding_pin ?: '—' }}</p>
        </div>
    </div>

    @if ($booking->status === 'paid' && $booking->qr_token)
        <div class="mt-6 rounded-xl border border-zinc-200 p-6 bg-white flex flex-col items-center">
            <p class="text-sm text-zinc-500">Show this QR to your driver at boarding</p>
            <div class="mt-3">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(220)->generate($booking->qr_token) !!}
            </div>
            <p class="text-xs font-mono text-zinc-400 mt-3">{{ $booking->qr_token }}</p>
        </div>
    @endif

    @if ($booking->status === 'pending_payment')
        <div class="mt-6 rounded-xl border border-zinc-200 p-5 bg-white">
            <h2 class="font-semibold">Pay with M-Pesa</h2>
            <p class="text-sm text-zinc-500 mt-1">We’ll send an STK push to your phone for KES {{ number_format((float) $booking->amount) }}.</p>
            <form method="POST" action="{{ route('bookings.pay', $booking) }}" class="mt-4 grid gap-3 sm:grid-cols-[1fr_auto]">
                @csrf
                <input name="phone" required placeholder="+2547..." value="{{ auth()->user()->phone }}" class="rounded-lg border-zinc-300">
                <button class="rounded-full bg-emerald-600 text-white px-6 py-3 font-medium hover:bg-emerald-700">Send STK push</button>
            </form>
            @if ($errors->any())
                <p class="text-sm text-rose-600 mt-2">{{ $errors->first() }}</p>
            @endif

            @if (app()->environment() !== 'production')
                <form method="POST" action="{{ route('bookings.simulate-success', $booking) }}" class="mt-4">
                    @csrf
                    <button class="text-xs text-zinc-500 underline">
                        [dev] simulate M-Pesa success callback
                    </button>
                </form>
            @endif
        </div>
    @endif
</section>
@endsection
