@extends('layouts.app')

@section('title', 'How CATATU works')

@section('content')
<section class="max-w-4xl mx-auto px-4 py-16">
    <h1 class="text-3xl font-bold">How it works</h1>
    <div class="mt-10 grid md:grid-cols-2 gap-6">
        @foreach([
            ['1', 'Sign up with your student email', 'We verify your .edu / campus email to keep the ride student-only.'],
            ['2', 'Pick a route & seat', 'See live routes near you, pick a specific seat, and add a group if friends are travelling.'],
            ['3', 'Pay with M-Pesa', 'An STK push hits your phone. Confirm and you’re on the manifest.'],
            ['4', 'Trip activates', 'Once the route hits its passenger threshold, we lock in a driver and depart time.'],
            ['5', 'Board with QR / PIN', 'The driver scans your QR or checks your 6-digit boarding PIN.'],
            ['6', 'Ride & rate', 'Rate the driver, earn streaks, unlock loyalty tiers (Bronze → Silver → Gold).'],
        ] as $step)
            <div class="rounded-xl border border-zinc-200 p-6 flex gap-4">
                <span class="w-9 h-9 rounded-full bg-emerald-600 text-white grid place-items-center font-bold">{{ $step[0] }}</span>
                <div>
                    <h3 class="font-semibold">{{ $step[1] }}</h3>
                    <p class="text-sm text-zinc-600 mt-1">{{ $step[2] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
