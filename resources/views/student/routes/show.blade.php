@extends('layouts.app')

@section('title', $route->start_location.' → '.$route->end_location.' — CATATU')

@section('content')
<section class="max-w-4xl mx-auto px-4 py-10">
    <a href="{{ route('routes.index') }}" class="text-sm text-zinc-500 hover:text-emerald-700">← All routes</a>

    <div class="mt-4">
        <p class="text-xs font-mono text-zinc-400">{{ $route->code }}</p>
        <h1 class="text-3xl font-bold">{{ $route->start_location }} → {{ $route->end_location }}</h1>
    </div>

    <div class="mt-6">
        <route-map
            start="{{ $route->start_location }}"
            end="{{ $route->end_location }}"
            price="{{ number_format((float) $route->price) }}"
            @if ($trip) departure-time="{{ $trip->departure_time->format('D, j M · H:i') }}" @endif>
        </route-map>
    </div>

    @if ($trip)
        <div class="mt-6 rounded-xl border border-zinc-200 p-5 bg-white">
            <h2 class="font-semibold">Next trip</h2>
            <div class="mt-3 grid sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-zinc-500">Status</p>
                    <p class="font-medium">{{ str_replace('_', ' ', $trip->status) }}</p>
                </div>
                <div>
                    <p class="text-zinc-500">Seats filled</p>
                    <p class="font-medium">{{ $trip->seats_booked }} / {{ $trip->seats_total }}</p>
                </div>
                <div>
                    <p class="text-zinc-500">Threshold</p>
                    <p class="font-medium">{{ $route->threshold }} seats to activate</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('bookings.store', $route) }}" class="mt-6 rounded-xl border border-zinc-200 p-5 bg-white">
            @csrf
            <h2 class="font-semibold">Pick your seat(s)</h2>
            <p class="text-sm text-zinc-500 mt-1">Tap a seat to select it. Max 4 seats per booking.</p>

            <div class="mt-5">
                <seat-picker
                    :rows="{{ $rows }}"
                    :cols="{{ $cols }}"
                    :taken="{{ json_encode($taken) }}"
                    :max-select="4"
                    input-name="seats"
                ></seat-picker>
            </div>

            @if ($errors->any())
                <p class="text-sm text-rose-600 mt-3">{{ $errors->first() }}</p>
            @endif

            <button class="mt-6 rounded-full bg-emerald-600 text-white px-6 py-3 font-medium hover:bg-emerald-700">
                Reserve seat(s)
            </button>
        </form>
    @else
        <div class="mt-6 rounded-xl border border-zinc-200 p-5 bg-zinc-50 text-zinc-600 text-sm">
            No upcoming trip yet. Come back soon or ask CATATU ops to schedule one.
        </div>
    @endif
</section>
@endsection
