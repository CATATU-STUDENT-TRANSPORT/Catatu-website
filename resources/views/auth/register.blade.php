@extends('layouts.app')

@section('title', 'Create your CATATU account')

@section('content')
<section class="max-w-md mx-auto px-4 py-16">
    <h1 class="text-2xl font-bold">Create your account</h1>
    <p class="text-sm text-zinc-600 mt-1">Students ride with CATATU — use your campus email for verified access.</p>

    <form method="POST" class="mt-8 grid gap-4 bg-white rounded-2xl border border-zinc-200 p-6 shadow-sm">
        @csrf
        <label class="block">
            <span class="text-sm font-medium text-zinc-700">Full name</span>
            <input name="name" required class="mt-1 block w-full rounded-lg border-zinc-300" value="{{ old('name') }}">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-zinc-700">Username (optional)</span>
            <input name="username" class="mt-1 block w-full rounded-lg border-zinc-300" value="{{ old('username') }}">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-zinc-700">Email</span>
            <input type="email" name="email" required class="mt-1 block w-full rounded-lg border-zinc-300" value="{{ old('email') }}">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-zinc-700">Phone (optional, for M-Pesa)</span>
            <input name="phone" placeholder="+2547..." class="mt-1 block w-full rounded-lg border-zinc-300" value="{{ old('phone') }}">
        </label>
        <div class="grid md:grid-cols-2 gap-4">
            <label class="block">
                <span class="text-sm font-medium text-zinc-700">Password</span>
                <input type="password" name="password" required class="mt-1 block w-full rounded-lg border-zinc-300">
            </label>
            <label class="block">
                <span class="text-sm font-medium text-zinc-700">Confirm password</span>
                <input type="password" name="password_confirmation" required class="mt-1 block w-full rounded-lg border-zinc-300">
            </label>
        </div>
        @if ($errors->any())
            <ul class="text-sm text-rose-600 list-disc pl-5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        @endif
        <button class="rounded-full bg-emerald-600 text-white px-6 py-3 font-medium hover:bg-emerald-700">Create account</button>
        <p class="text-sm text-zinc-600 text-center">
            Already have an account? <a href="{{ route('login') }}" class="text-emerald-700 font-medium">Log in</a>
        </p>
    </form>
</section>
@endsection
