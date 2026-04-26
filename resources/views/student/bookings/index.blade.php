@extends('layouts.app')

@section('title', 'My trips — CATATU')

@section('content')
<section class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold">My trips</h1>

    <div class="mt-6 grid gap-4">
        @forelse($bookings as $booking)
            <a href="{{ route('bookings.show', $booking) }}" class="block rounded-xl border border-zinc-200 p-5 hover:border-emerald-400 transition bg-white">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-mono text-zinc-400">{{ $booking->reference }}</p>
                        <h3 class="font-semibold">{{ $booking->route->start_location }} → {{ $booking->route->end_location }}</h3>
                        <p class="text-sm text-zinc-500 mt-1">
                            Seats: {{ implode(', ', $booking->seats ?? []) }} · KES {{ number_format((float) $booking->amount) }}
                        </p>
                    </div>
                    <span @class([
                        'text-xs font-semibold rounded-full px-3 py-1',
                        'bg-emerald-100 text-emerald-800' => $booking->status === 'paid',
                        'bg-amber-100 text-amber-800' => $booking->status === 'pending_payment',
                        'bg-rose-100 text-rose-800' => in_array($booking->status, ['cancelled', 'refunded']),
                        'bg-zinc-100 text-zinc-700' => ! in_array($booking->status, ['paid', 'pending_payment', 'cancelled', 'refunded']),
                    ])>
                        {{ str_replace('_', ' ', $booking->status) }}
                    </span>
                </div>
            </a>
        @empty
            <p class="text-zinc-600">You haven’t booked anything yet. <a href="{{ route('routes.index') }}" class="text-emerald-700 font-medium">Browse routes →</a></p>
        @endforelse
    </div>
</section>
@endsection
