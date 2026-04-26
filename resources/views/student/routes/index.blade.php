@extends('layouts.app')

@section('title', 'Available routes — CATATU')

@section('content')
<section class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold">Available routes</h1>
    <p class="text-sm text-zinc-600 mt-1">Pick a route to see the next trip and book your seat.</p>

    <div class="mt-8 grid md:grid-cols-2 gap-5">
        @forelse($routes as $route)
            <a href="{{ route('routes.show', $route) }}" class="block rounded-xl border border-zinc-200 p-5 hover:border-emerald-400 hover:shadow transition">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-mono text-zinc-400">{{ $route->code }}</p>
                        <h3 class="text-lg font-semibold mt-1">{{ $route->start_location }} → {{ $route->end_location }}</h3>
                        <p class="text-sm text-zinc-500 mt-1">
                            ~{{ $route->estimated_minutes }} min · threshold {{ $route->threshold }} seats
                        </p>
                    </div>
                    <span class="text-lg font-bold text-emerald-700 whitespace-nowrap">KES {{ number_format((float) $route->price) }}</span>
                </div>
            </a>
        @empty
            <p class="text-zinc-600">No routes are live right now. Check back soon.</p>
        @endforelse
    </div>
</section>
@endsection
