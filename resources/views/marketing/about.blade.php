@extends('layouts.app')

@section('title', 'About CATATU')

@section('content')
<section class="max-w-3xl mx-auto px-4 py-16 prose prose-zinc">
    <h1>About CATATU</h1>
    <p>
        CATATU is a demand-driven, prepaid student mobility platform built in Kenya. We coordinate direct,
        scheduled routes between student residential zones and campuses, replacing cash, crowding and chaos
        with prepaid seats, real-time visibility and verified drivers.
    </p>
    <h2>Our mission</h2>
    <p>
        To make the daily campus commute safer, cheaper and more predictable — and to build the coordination
        layer that student mobility in emerging markets has been missing.
    </p>
    <h2>Problem we solve</h2>
    <ul>
        <li>Lack of real-time visibility of available buses</li>
        <li>Cash-based payments that are insecure and slow</li>
        <li>No standard way for students to coordinate group transport</li>
        <li>Overcrowding and safety concerns in public transport</li>
    </ul>
    <h2>Our approach</h2>
    <p>
        We pre-book seats along popular student corridors. When a route hits its threshold of committed
        students, we activate the trip, assign a verified driver and vehicle, and issue QR/PIN boarding
        passes to each passenger.
    </p>
</section>
@endsection
