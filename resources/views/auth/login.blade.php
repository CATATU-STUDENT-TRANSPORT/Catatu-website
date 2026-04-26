@extends('layouts.app')

@section('title', 'Log in — CATATU')

@section('content')
<section class="max-w-md mx-auto px-4 py-16">
    <h1 class="text-2xl font-bold">Welcome back</h1>
    <p class="text-sm text-zinc-600 mt-1">Log in to book your next trip.</p>

    <form method="POST" class="mt-8 grid gap-4 bg-white rounded-2xl border border-zinc-200 p-6 shadow-sm">
        @csrf
        <label class="block">
            <span class="text-sm font-medium text-zinc-700">Email</span>
            <input type="email" name="email" required autofocus class="mt-1 block w-full rounded-lg border-zinc-300" value="{{ old('email') }}">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-zinc-700">Password</span>
            <input type="password" name="password" required class="mt-1 block w-full rounded-lg border-zinc-300">
        </label>
        <label class="flex items-center gap-2 text-sm text-zinc-700">
            <input type="checkbox" name="remember" value="1" class="rounded border-zinc-300 text-emerald-600">
            Keep me signed in
        </label>
        @if ($errors->any())
            <p class="text-sm text-rose-600">{{ $errors->first() }}</p>
        @endif
        <button class="rounded-full bg-emerald-600 text-white px-6 py-3 font-medium hover:bg-emerald-700">Log in</button>
        <p class="text-sm text-zinc-600 text-center">
            No account? <a href="{{ route('register') }}" class="text-emerald-700 font-medium">Join CATATU</a>
        </p>
    </form>
</section>
@endsection
