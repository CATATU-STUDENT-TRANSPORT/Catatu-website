@extends('layouts.app')

@section('title', 'CATATU — Student transport, done right')

@section('content')
<section class="bg-gradient-to-br from-emerald-50 via-white to-sky-50 border-b border-zinc-100">
    <div class="max-w-6xl mx-auto px-4 py-20 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <p class="inline-flex items-center gap-2 rounded-full bg-emerald-100 text-emerald-800 text-xs font-medium px-3 py-1 mb-4">
                Demand-driven student mobility
            </p>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-tight">
                Direct rides from your area to campus — <span class="text-emerald-700">pre-booked, prepaid, on time.</span>
            </h1>
            <p class="mt-5 text-lg text-zinc-600">
                CATATU gets enough students on the same route, then activates the trip. You pay with M-Pesa, get a QR ticket, and ride with a verified driver.
            </p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="inline-flex rounded-full bg-emerald-600 text-white px-6 py-3 font-medium hover:bg-emerald-700">
                    Join CATATU
                </a>
                <a href="{{ route('how-it-works') }}" class="inline-flex rounded-full border border-zinc-300 text-zinc-700 px-6 py-3 font-medium hover:border-emerald-500 hover:text-emerald-700">
                    How it works
                </a>
            </div>
            <dl class="mt-10 grid grid-cols-3 gap-4 text-center">
                <div>
                    <dt class="text-xs uppercase text-zinc-500">Route fill rate</dt>
                    <dd class="text-2xl font-bold text-emerald-700">80%+</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-zinc-500">Payment success</dt>
                    <dd class="text-2xl font-bold text-emerald-700">95%+</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-zinc-500">Verified drivers</dt>
                    <dd class="text-2xl font-bold text-emerald-700">100%</dd>
                </div>
            </dl>
        </div>
        <div class="bg-white rounded-2xl shadow-xl border border-zinc-200 p-6">
            <p class="text-xs uppercase text-zinc-500 font-semibold">Next trip near you</p>
            <route-map start="Kilimani" end="Strathmore University" departure-time="Today · 7:30 AM" price="100"></route-map>
            <div class="mt-4 text-sm text-zinc-600">
                Threshold: 10 / 10 seats filled — trip activated.
            </div>
        </div>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 py-16">
    <h2 class="text-2xl font-bold text-center mb-10">Why CATATU?</h2>
    <div class="grid md:grid-cols-3 gap-6">
        <div class="rounded-xl border border-zinc-200 p-6">
            <h3 class="font-semibold">Pre-booked seats</h3>
            <p class="text-sm text-zinc-600 mt-2">No chaos. Pick your seat, get a digital boarding pass with QR + PIN.</p>
        </div>
        <div class="rounded-xl border border-zinc-200 p-6">
            <h3 class="font-semibold">M-Pesa first</h3>
            <p class="text-sm text-zinc-600 mt-2">STK push, refunds for cancelled trips, wallet credits, promo codes.</p>
        </div>
        <div class="rounded-xl border border-zinc-200 p-6">
            <h3 class="font-semibold">Safety built in</h3>
            <p class="text-sm text-zinc-600 mt-2">Verified drivers, share-trip links, SOS, and student-ID verification.</p>
        </div>
    </div>
</section>

<section class="bg-zinc-900 text-white">
    <div class="max-w-6xl mx-auto px-4 py-16 text-center">
        <h2 class="text-2xl md:text-3xl font-bold">Ready to skip the matatu scramble?</h2>
        <p class="mt-3 text-zinc-300">Register with your student email and book your first ride in under 2 minutes.</p>
        <a href="{{ route('register') }}" class="mt-6 inline-flex rounded-full bg-emerald-500 text-white px-6 py-3 font-medium hover:bg-emerald-600">
            Create your account
        </a>
    </div>
</section>
@endsection
