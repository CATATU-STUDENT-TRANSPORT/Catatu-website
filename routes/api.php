<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\RouteController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

Route::prefix('routes')->group(function () {
    Route::get('/', [RouteController::class, 'index']);
    Route::get('{route}', [RouteController::class, 'show']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('bookings')->group(function () {
        Route::post('/', [BookingController::class, 'store']);
        Route::get('user', [BookingController::class, 'mine']);
        Route::get('{booking}', [BookingController::class, 'show']);
        Route::delete('{booking}', [BookingController::class, 'cancel']);
    });

    Route::prefix('payments')->group(function () {
        Route::post('mpesa', [PaymentController::class, 'mpesa']);
    });
});

// Safaricom callback — public, validated server-side via CheckoutRequestID.
Route::post('payments/callback', [PaymentController::class, 'callback']);
