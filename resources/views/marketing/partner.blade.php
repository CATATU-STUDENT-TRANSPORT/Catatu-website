@extends('layouts.app')

@section('title', 'Partner with CATATU')

@section('content')
<section class="max-w-3xl mx-auto px-4 py-16">
    <h1 class="text-3xl font-bold">Partner with CATATU</h1>
    <p class="mt-3 text-zinc-600">
        Universities, student associations, and transport operators — let’s talk about bringing CATATU to your
        campus or fleet. Fill in the form below and our partnerships team will reach out.
    </p>

    <form method="POST" action="{{ route('partner.submit') }}" class="mt-10 grid gap-4 bg-white rounded-2xl border border-zinc-200 p-6 shadow-sm">
        @csrf
        <label class="block">
            <span class="text-sm font-medium text-zinc-700">Organization</span>
            <input name="organization" required class="mt-1 block w-full rounded-lg border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500" value="{{ old('organization') }}">
        </label>
        <div class="grid md:grid-cols-2 gap-4">
            <label class="block">
                <span class="text-sm font-medium text-zinc-700">Contact name</span>
                <input name="contact_name" required class="mt-1 block w-full rounded-lg border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500" value="{{ old('contact_name') }}">
            </label>
            <label class="block">
                <span class="text-sm font-medium text-zinc-700">Email</span>
                <input type="email" name="email" required class="mt-1 block w-full rounded-lg border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500" value="{{ old('email') }}">
            </label>
        </div>
        <label class="block">
            <span class="text-sm font-medium text-zinc-700">Phone (optional)</span>
            <input name="phone" class="mt-1 block w-full rounded-lg border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500" value="{{ old('phone') }}">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-zinc-700">Tell us about your fleet or students</span>
            <textarea name="message" rows="4" class="mt-1 block w-full rounded-lg border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('message') }}</textarea>
        </label>
        @if ($errors->any())
            <ul class="text-sm text-rose-600 list-disc pl-5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        @endif
        <button class="inline-flex justify-center rounded-full bg-emerald-600 text-white px-6 py-3 font-medium hover:bg-emerald-700">
            Submit partnership interest
        </button>
    </form>
</section>
@endsection
