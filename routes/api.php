<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MidtransNotificationController;
use App\Http\Controllers\Api\V1\PropertyController;
use App\Http\Controllers\Api\V1\RoomController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\DashboardController;


// Midtrans Webhook - Tidak perlu authentication karena menggunakan signature verification
Route::post('/payment/notification', [MidtransNotificationController::class, 'handle'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::prefix('v1')->group(function () {
    // Public routes - Authentication
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Public routes - Properties (Browse without auth)
    Route::get('/properties', [PropertyController::class, 'index']);
    Route::get('/properties/{slug}', [PropertyController::class, 'show']);
    Route::get('/rooms/{id}', [RoomController::class, 'show']);

    // Protected routes (Sanctum authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/user', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Tenant Bookings
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/bookings/{id}', [BookingController::class, 'show']);
        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);

        // Tenant Payments
        Route::get('/payments', [PaymentController::class, 'index']);
        Route::post('/payments', [PaymentController::class, 'store']);

        // Owner routes
        Route::prefix('owner')->group(function () {
            // Dashboard
            Route::get('/dashboard', [DashboardController::class, 'ownerStats']);

            // Properties management
            Route::get('/properties', [PropertyController::class, 'ownerProperties']);

            // Bookings management
            Route::get('/bookings', [BookingController::class, 'ownerBookings']);
            Route::post('/bookings/{bookingId}/payments/{paymentId}/verify', [BookingController::class, 'verifyPayment']);
            Route::post('/bookings/{bookingId}/payments/{paymentId}/reject', [BookingController::class, 'rejectPayment']);
        });
    });
});
