<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\MarketingController;
use App\Http\Controllers\Web\RouteController;
use Illuminate\Support\Facades\Route;

// Marketing site
Route::get('/', [MarketingController::class, 'home'])->name('home');
Route::get('/about', [MarketingController::class, 'about'])->name('about');
Route::get('/how-it-works', [MarketingController::class, 'howItWorks'])->name('how-it-works');
Route::get('/partner', [MarketingController::class, 'partner'])->name('partner');
Route::post('/partner', [MarketingController::class, 'partnerSubmit'])->name('partner.submit');

// Student auth (simple web auth — distinct from API + Filament panel logins)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Student-facing booking flow
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('routes.index'))->name('dashboard');
    Route::get('/routes', [RouteController::class, 'index'])->name('routes.index');
    Route::get('/routes/{route}', [RouteController::class, 'show'])->name('routes.show');
    Route::post('/routes/{route}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/pay', [BookingController::class, 'pay'])->name('bookings.pay');
    Route::post('/bookings/{booking}/simulate-success', [BookingController::class, 'simulateSuccess'])
        ->name('bookings.simulate-success');
});
