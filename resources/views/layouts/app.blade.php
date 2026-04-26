<!DOCTYPE html>
<html lang="en" class="h-full bg-white antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CATATU — Student transport, done right')</title>
    <meta name="description" content="@yield('description', 'CATATU is a demand-driven student mobility platform: book seats, pay with M-Pesa, ride with verified drivers.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-full flex flex-col font-sans text-zinc-900">
    <header class="border-b border-zinc-200 bg-white/90 backdrop-blur sticky top-0 z-20">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-600 text-white grid place-items-center font-bold">C</span>
                <span class="font-semibold text-lg tracking-tight">CATATU</span>
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm text-zinc-700">
                <a href="{{ route('how-it-works') }}" class="hover:text-emerald-700">How it works</a>
                <a href="{{ route('about') }}" class="hover:text-emerald-700">About</a>
                <a href="{{ route('partner') }}" class="hover:text-emerald-700">Partner</a>
                @auth
                    <a href="{{ route('routes.index') }}" class="hover:text-emerald-700">Routes</a>
                    <a href="{{ route('bookings.index') }}" class="hover:text-emerald-700">My Trips</a>
                @endauth
            </nav>
            <div class="flex items-center gap-3 text-sm">
                @guest
                    <a href="{{ route('login') }}" class="text-zinc-700 hover:text-emerald-700">Log in</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center rounded-full bg-emerald-600 text-white px-4 py-2 font-medium hover:bg-emerald-700">
                        Join CATATU
                    </a>
                @endguest
                @auth
                    <span class="text-zinc-600 hidden sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-zinc-700 hover:text-rose-600">Log out</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <main id="vue-app" class="flex-1">
        @if (session('success'))
            <div class="max-w-6xl mx-auto px-4 mt-4">
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="border-t border-zinc-200 bg-zinc-50">
        <div class="max-w-6xl mx-auto px-4 py-8 flex flex-col md:flex-row items-center justify-between gap-3 text-sm text-zinc-600">
            <p>© {{ date('Y') }} CATATU Mobility. All rights reserved.</p>
            <p class="flex gap-4">
                <a href="{{ route('about') }}" class="hover:text-emerald-700">About</a>
                <a href="{{ route('partner') }}" class="hover:text-emerald-700">Partner with us</a>
                <a href="mailto:hello@catatu.app" class="hover:text-emerald-700">hello@catatu.app</a>
            </p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
