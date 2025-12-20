<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Mitra\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public Routes (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/browse', [HomeController::class, 'browse'])->name('browse');
Route::get('/properties/{id}', [HomeController::class, 'show'])->name('properties.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

Route::get('/dashboard', function () {
    if (Auth::check()) {
        $user = Auth::user();
        
        if ($user->role === 'tenant') {
            return redirect()->route('tenant.dashboard');
        } elseif ($user->role === 'owner') {
            return redirect()->route('mitra.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
    }
    
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // User Notifications Routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::patch('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/clear-read', [\App\Http\Controllers\NotificationController::class, 'clearRead'])->name('notifications.clear-read');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// LIVORA Mitra Routes
Route::prefix('mitra')->name('mitra.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Property Management Routes
    Route::resource('properties', \App\Http\Controllers\Mitra\PropertyController::class);
    
    // Room Management Routes (nested under properties)
    Route::prefix('properties/{property}')->group(function () {
        Route::get('/rooms', [\App\Http\Controllers\Mitra\RoomController::class, 'index'])->name('rooms.index');
        Route::get('/rooms/create', [\App\Http\Controllers\Mitra\RoomController::class, 'create'])->name('rooms.create');
        Route::post('/rooms', [\App\Http\Controllers\Mitra\RoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/{room}', [\App\Http\Controllers\Mitra\RoomController::class, 'show'])->name('rooms.show');
        Route::get('/rooms/{room}/edit', [\App\Http\Controllers\Mitra\RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/rooms/{room}', [\App\Http\Controllers\Mitra\RoomController::class, 'update'])->name('rooms.update');
        Route::delete('/rooms/{room}', [\App\Http\Controllers\Mitra\RoomController::class, 'destroy'])->name('rooms.destroy');
        Route::patch('/rooms/{room}/toggle-availability', [\App\Http\Controllers\Mitra\RoomController::class, 'toggleAvailability'])->name('rooms.toggle-availability');
    });
    
    // Booking Management Routes
    Route::resource('bookings', \App\Http\Controllers\Mitra\BookingController::class);
    Route::get('/bookings/rooms/{boardingHouse}', [\App\Http\Controllers\Mitra\BookingController::class, 'getRooms'])->name('bookings.rooms');
    Route::post('/bookings/{booking}/confirm', [\App\Http\Controllers\Mitra\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/check-in', [\App\Http\Controllers\Mitra\BookingController::class, 'checkIn'])->name('bookings.check-in');
    Route::post('/bookings/{booking}/check-out', [\App\Http\Controllers\Mitra\BookingController::class, 'checkOut'])->name('bookings.check-out');
    Route::post('/bookings/{booking}/cancel', [\App\Http\Controllers\Mitra\BookingController::class, 'cancel'])->name('bookings.cancel');
    
    // Ticket Management Routes (CRM System)
    Route::resource('tickets', \App\Http\Controllers\Mitra\TicketController::class)->only(['index', 'show', 'edit', 'update']);
    Route::patch('/tickets/{ticket}/status', [\App\Http\Controllers\Mitra\TicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::patch('/tickets/{ticket}/priority', [\App\Http\Controllers\Mitra\TicketController::class, 'updatePriority'])->name('tickets.update-priority');
    
    // Payment Management Routes (Verification & Monitoring)
    Route::resource('payments', \App\Http\Controllers\Mitra\PaymentController::class)->only(['index', 'show']);
    Route::patch('/payments/{payment}/verify', [\App\Http\Controllers\Mitra\PaymentController::class, 'verify'])->name('payments.verify');
    Route::patch('/payments/{payment}/reject', [\App\Http\Controllers\Mitra\PaymentController::class, 'reject'])->name('payments.reject');
    Route::post('/payments/bulk-action', [\App\Http\Controllers\Mitra\PaymentController::class, 'bulkAction'])->name('payments.bulk-action');
    Route::get('/payments/{payment}/download-proof', [\App\Http\Controllers\Mitra\PaymentController::class, 'downloadProof'])->name('payments.download-proof');
    Route::get('/payments/{payment}/download-receipt', [\App\Http\Controllers\Mitra\PaymentController::class, 'downloadReceipt'])->name('payments.download-receipt');
    
    // Report Routes
    Route::get('/reports', [\App\Http\Controllers\Mitra\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [\App\Http\Controllers\Mitra\ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-excel', [\App\Http\Controllers\Mitra\ReportController::class, 'exportExcel'])->name('reports.export-excel');
    Route::get('/reports/revenue', [\App\Http\Controllers\Mitra\ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/occupancy', [\App\Http\Controllers\Mitra\ReportController::class, 'occupancy'])->name('reports.occupancy');
});

// LIVORA Tenant Routes
Route::prefix('tenant')->name('tenant.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management Routes
    Route::get('/profile', [\App\Http\Controllers\Tenant\ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile', [\App\Http\Controllers\Tenant\ProfileController::class, 'update'])->name('profile.update');
    
    // Booking Management Routes for Tenants
    Route::resource('bookings', \App\Http\Controllers\Tenant\BookingController::class);
    Route::get('/bookings/rooms/{boardingHouse}', [\App\Http\Controllers\Tenant\BookingController::class, 'getRooms'])->name('bookings.rooms');
    Route::post('/bookings/{booking}/cancel', [\App\Http\Controllers\Tenant\BookingController::class, 'cancel'])->name('bookings.cancel');
    
    // Ticket Management Routes for Tenants
    Route::resource('tickets', \App\Http\Controllers\Tenant\TicketController::class);
    
    // Payment Management Routes (Submit & Track Payments)
    // METODE PEMBAYARAN KONVENSIONAL - DINONAKTIFKAN (MENGGUNAKAN MIDTRANS)
    // Route::resource('payments', \App\Http\Controllers\Tenant\PaymentController::class);
    
    // Only keep index and show routes for viewing payment history
    Route::get('/payments', [\App\Http\Controllers\Tenant\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [\App\Http\Controllers\Tenant\PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/download-receipt', [\App\Http\Controllers\Tenant\PaymentController::class, 'downloadReceipt'])->name('payments.download-receipt');
    
    // Midtrans Payment Routes
    Route::get('/payments-midtrans/create', function() {
        $userId = \Illuminate\Support\Facades\Auth::id();
        
        // Get bookings that need payment (Midtrans status check)
        $availableBookings = \App\Models\Booking::with(['room.boardingHouse', 'payments'])
            ->where('user_id', $userId)
            ->whereIn('status', ['confirmed', 'pending']) // Only confirmed or pending bookings
            ->whereDoesntHave('payments', function ($query) {
                // Exclude bookings with successful Midtrans payments only
                $query->whereIn('status', ['settlement', 'capture']);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Debug: Log detailed info untuk troubleshooting
        \Log::info('Payment page - Available bookings check', [
            'user_id' => $userId,
            'total_bookings' => \App\Models\Booking::where('user_id', $userId)->count(),
            'available_count' => $availableBookings->count(),
            'all_bookings' => \App\Models\Booking::where('user_id', $userId)
                ->with('payments')
                ->get()
                ->map(function($b) {
                    return [
                        'id' => $b->id,
                        'code' => $b->booking_code,
                        'status' => $b->status,
                        'payments' => $b->payments->map(fn($p) => ['id' => $p->id, 'status' => $p->status])
                    ];
                })
        ]);
        
        return view('tenant.payments.midtrans', compact('availableBookings'));
    })->name('payments.midtrans.create');
    Route::post('/payments/midtrans/checkout', [\App\Http\Controllers\Tenant\PaymentController::class, 'createMidtransCheckout'])->name('payments.midtrans.checkout');
    Route::get('/payments/finish', [\App\Http\Controllers\Tenant\PaymentController::class, 'finishPayment'])->name('payments.finish');
});

// LIVORA Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // User Management Routes
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::patch('/users/{user}/activate', [\App\Http\Controllers\Admin\UserController::class, 'activate'])->name('users.activate');
    Route::patch('/users/{user}/deactivate', [\App\Http\Controllers\Admin\UserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/bulk-activate', [\App\Http\Controllers\Admin\UserController::class, 'bulkActivate'])->name('users.bulk-activate');
    Route::post('/users/bulk-deactivate', [\App\Http\Controllers\Admin\UserController::class, 'bulkDeactivate'])->name('users.bulk-deactivate');
    Route::post('/users/bulk-delete', [\App\Http\Controllers\Admin\UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::get('/users-export', [\App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
    
    // Property Management Routes
    Route::resource('properties', \App\Http\Controllers\Admin\PropertyController::class);
    Route::patch('/properties/{property}/verify', [\App\Http\Controllers\Admin\PropertyController::class, 'verify'])->name('properties.verify');
    Route::patch('/properties/{property}/suspend', [\App\Http\Controllers\Admin\PropertyController::class, 'suspend'])->name('properties.suspend');
    Route::post('/properties/bulk-verify', [\App\Http\Controllers\Admin\PropertyController::class, 'bulkVerify'])->name('properties.bulk-verify');
    Route::post('/properties/bulk-suspend', [\App\Http\Controllers\Admin\PropertyController::class, 'bulkSuspend'])->name('properties.bulk-suspend');
    Route::get('/properties-export', [\App\Http\Controllers\Admin\PropertyController::class, 'export'])->name('properties.export');
    Route::get('/properties/{property}/rooms', [\App\Http\Controllers\Admin\PropertyController::class, 'getRooms'])->name('properties.rooms');
    
    // Booking Management Routes
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class);
    Route::post('/bookings/{booking}/approve', [\App\Http\Controllers\Admin\BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{booking}/reject', [\App\Http\Controllers\Admin\BookingController::class, 'reject'])->name('bookings.reject');
    Route::post('/bookings/bulk-approve', [\App\Http\Controllers\Admin\BookingController::class, 'bulkApprove'])->name('bookings.bulk-approve');
    Route::post('/bookings/bulk-reject', [\App\Http\Controllers\Admin\BookingController::class, 'bulkReject'])->name('bookings.bulk-reject');
    Route::get('/bookings-export', [\App\Http\Controllers\Admin\BookingController::class, 'export'])->name('bookings.export');
    
    // Payment Management Routes
    Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class);
    Route::patch('/payments/{payment}/verify', [\App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('payments.verify');
    Route::patch('/payments/{payment}/reject', [\App\Http\Controllers\Admin\PaymentController::class, 'reject'])->name('payments.reject');
    Route::post('/payments/bulk-verify', [\App\Http\Controllers\Admin\PaymentController::class, 'bulkVerify'])->name('payments.bulk-verify');
    Route::post('/payments/bulk-reject', [\App\Http\Controllers\Admin\PaymentController::class, 'bulkReject'])->name('payments.bulk-reject');
    Route::get('/payments/{payment}/download-proof', [\App\Http\Controllers\Admin\PaymentController::class, 'downloadProof'])->name('payments.download-proof');
    Route::get('/payments-export', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');
    
    // Ticket Management Routes
    Route::resource('tickets', \App\Http\Controllers\Admin\TicketController::class);
    Route::patch('/tickets/{ticket}/status', [\App\Http\Controllers\Admin\TicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::patch('/tickets/{ticket}/priority', [\App\Http\Controllers\Admin\TicketController::class, 'updatePriority'])->name('tickets.update-priority');
    Route::patch('/tickets/{ticket}/assign', [\App\Http\Controllers\Admin\TicketController::class, 'assign'])->name('tickets.assign');
    Route::patch('/tickets/{ticket}/resolve', [\App\Http\Controllers\Admin\TicketController::class, 'resolve'])->name('tickets.resolve');
    Route::post('/tickets/bulk-status', [\App\Http\Controllers\Admin\TicketController::class, 'bulkUpdateStatus'])->name('tickets.bulk-status');
    Route::post('/tickets/bulk-assign', [\App\Http\Controllers\Admin\TicketController::class, 'bulkAssign'])->name('tickets.bulk-assign');
    Route::post('/tickets/bulk-close', [\App\Http\Controllers\Admin\TicketController::class, 'bulkClose'])->name('tickets.bulk-close');
    Route::get('/tickets-export', [\App\Http\Controllers\Admin\TicketController::class, 'export'])->name('tickets.export');
    
    // Notifications Routes
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [\App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notifications.store');
    Route::get('/notifications/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'show'])->name('notifications.show');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/bulk-delete', [\App\Http\Controllers\Admin\NotificationController::class, 'bulkDelete'])->name('notifications.bulk-delete');
    Route::post('/notifications/send-test', [\App\Http\Controllers\Admin\NotificationController::class, 'sendTest'])->name('notifications.send-test');
    Route::get('/notifications/stats', [\App\Http\Controllers\Admin\NotificationController::class, 'getStats'])->name('notifications.stats');
    Route::get('/notifications-export', [\App\Http\Controllers\Admin\NotificationController::class, 'export'])->name('notifications.export');

    // Reports Routes
    Route::get('/reports/revenue', [\App\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/revenue/export', [\App\Http\Controllers\Admin\ReportController::class, 'revenueExport'])->name('reports.revenue.export');
    Route::get('/reports/occupancy', [\App\Http\Controllers\Admin\ReportController::class, 'occupancy'])->name('reports.occupancy');
    Route::get('/reports/occupancy/export', [\App\Http\Controllers\Admin\ReportController::class, 'occupancyExport'])->name('reports.occupancy.export');
    Route::get('/reports/performance', [\App\Http\Controllers\Admin\ReportController::class, 'performance'])->name('reports.performance');
    Route::get('/reports/performance/export', [\App\Http\Controllers\Admin\ReportController::class, 'performanceExport'])->name('reports.performance.export');
    Route::get('/reports/users', [\App\Http\Controllers\Admin\ReportController::class, 'users'])->name('reports.users');
    Route::get('/reports/users/export', [\App\Http\Controllers\Admin\ReportController::class, 'usersExport'])->name('reports.users.export');
    Route::get('/reports/export/{type}', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    
    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Settings Routes
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::patch('/settings/general', [\App\Http\Controllers\Admin\SettingController::class, 'updateGeneral'])->name('settings.general');
    Route::patch('/settings/email', [\App\Http\Controllers\Admin\SettingController::class, 'updateEmail'])->name('settings.email');
    Route::patch('/settings/maintenance', [\App\Http\Controllers\Admin\SettingController::class, 'updateMaintenance'])->name('settings.maintenance');
    Route::post('/settings/test-email', [\App\Http\Controllers\Admin\SettingController::class, 'testEmail'])->name('settings.test-email');
    Route::post('/settings/clear-cache', [\App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/clear-logs', [\App\Http\Controllers\Admin\SettingController::class, 'clearLogs'])->name('settings.clear-logs');
});

require __DIR__.'/auth.php';

// Debug route untuk troubleshoot payment flow
Route::get('/debug-payment', function () {
    require base_path('debug-payment.php');
    exit;
})->middleware('auth');

// Check Midtrans payments route
Route::get('/check-midtrans', function () {
    require base_path('check-midtrans.php');
    exit;
})->middleware('auth');

// Test route
Route::get('/test-button', function () {
    return view('test-button');
});
