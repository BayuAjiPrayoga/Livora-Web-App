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

// Debug Route
Route::get('/debug/payments', function () {
    // Get recent payments
    $payments = \App\Models\Payment::with('booking')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

    // Check logs
    $logPath = storage_path('logs/laravel.log');
    $logs = [];
    if (file_exists($logPath)) {
        $lines = file($logPath);
        $logs = array_slice($lines, -100); // Last 100 lines
        $logs = array_reverse($logs); // Newest first
    }

    return response()->json([
        'server_time' => now()->toDateTimeString(),
        'db_connection' => config('database.default'),
        'recent_payments' => $payments,
        'recent_logs' => $logs
    ]);
});

// Force Settlement Route (Manual Fix)
Route::get('/debug/force-settlement/{orderId}', function ($orderId) {
    if (!config('app.debug') && !request()->has('force')) {
        return response()->json(['error' => 'Debug mode only'], 403);
    }

    $payment = \App\Models\Payment::with('booking')->where('order_id', $orderId)->first();
    if (!$payment)
        return response()->json(['error' => 'Payment not found'], 404);

    // Update payment
    $payment->update([
        'status' => 'settlement',
        'midtrans_status' => 'settlement',
        'verified_at' => now(),
        'notes' => 'Force updated via debug tool'
    ]);

    // Update booking
    if ($payment->booking) {
        $payment->booking->update(['status' => 'confirmed']);
    }

    return response()->json([
        'success' => true,
        'message' => 'Payment and Booking updated to Settlement/Confirmed',
        'payment' => $payment->fresh(),
        'booking' => $payment->booking->fresh()
    ]);
});

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
