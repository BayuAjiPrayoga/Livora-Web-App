<?php

/**
 * Debug Script untuk Payment Flow
 * 
 * Jalankan di browser: https://arkanta.my.id/debug-payment
 * ATAU via railway: railway run php debug-payment.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;

echo "<h1>🔍 LIVORA Payment Flow Debugger</h1>";
echo "<style>
body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
h1, h2, h3 { color: #4ec9b0; }
.success { color: #4ec9b0; }
.error { color: #f48771; }
.warning { color: #dcdcaa; }
table { border-collapse: collapse; width: 100%; margin: 20px 0; }
th, td { border: 1px solid #555; padding: 8px; text-align: left; }
th { background: #2d2d30; }
pre { background: #2d2d30; padding: 10px; border-radius: 4px; overflow-x: auto; }
</style>";

// ==============================================
// 1. CHECK ENVIRONMENT VARIABLES
// ==============================================
echo "<h2>📋 Step 1: Check Midtrans Configuration</h2>";

$serverKey = config('midtrans.server_key');
$clientKey = config('midtrans.client_key');
$isProduction = config('midtrans.is_production');

echo "<table>";
echo "<tr><th>Config</th><th>Value</th><th>Status</th></tr>";

// Server Key
echo "<tr>";
echo "<td>MIDTRANS_SERVER_KEY</td>";
echo "<td>" . (empty($serverKey) ? '<span class="error">EMPTY</span>' : substr($serverKey, 0, 20) . '...') . "</td>";
echo "<td>" . (empty($serverKey) ? '<span class="error">❌ MISSING</span>' : '<span class="success">✅ OK</span>') . "</td>";
echo "</tr>";

// Client Key
echo "<tr>";
echo "<td>MIDTRANS_CLIENT_KEY</td>";
echo "<td>" . (empty($clientKey) ? '<span class="error">EMPTY</span>' : substr($clientKey, 0, 20) . '...') . "</td>";
echo "<td>" . (empty($clientKey) ? '<span class="error">❌ MISSING</span>' : '<span class="success">✅ OK</span>') . "</td>";
echo "</tr>";

// Is Production
echo "<tr>";
echo "<td>MIDTRANS_IS_PRODUCTION</td>";
echo "<td>" . ($isProduction ? 'true' : 'false') . "</td>";
echo "<td>" . ($isProduction ? '<span class="warning">⚠️ PRODUCTION MODE</span>' : '<span class="success">✅ SANDBOX</span>') . "</td>";
echo "</tr>";

echo "</table>";

if (empty($serverKey) || empty($clientKey)) {
    echo "<p class='error'>❌ <strong>CRITICAL ERROR:</strong> Midtrans credentials belum diset!</p>";
    echo "<p>Set di Railway environment variables atau .env file</p>";
    exit;
}

// ==============================================
// 2. CHECK DATABASE - USERS
// ==============================================
echo "<h2>👥 Step 2: Check Tenant Users</h2>";

$tenants = User::where('role', 'tenant')->get();

if ($tenants->isEmpty()) {
    echo "<p class='error'>❌ Tidak ada user dengan role 'tenant'</p>";
} else {
    echo "<p class='success'>✅ Found " . $tenants->count() . " tenant(s)</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Bookings</th></tr>";
    foreach ($tenants as $tenant) {
        $bookingCount = Booking::where('user_id', $tenant->id)->count();
        echo "<tr>";
        echo "<td>{$tenant->id}</td>";
        echo "<td>{$tenant->name}</td>";
        echo "<td>{$tenant->email}</td>";
        echo "<td>{$bookingCount}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// ==============================================
// 3. CHECK DATABASE - BOOKINGS
// ==============================================
echo "<h2>📚 Step 3: Check Bookings Status</h2>";

$bookings = Booking::with(['user', 'room.boardingHouse', 'payments'])
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

if ($bookings->isEmpty()) {
    echo "<p class='error'>❌ Tidak ada booking sama sekali!</p>";
    echo "<p class='warning'>⚠️ <strong>SOLUSI:</strong> Buat booking baru sebagai tenant</p>";
} else {
    echo "<p class='success'>✅ Found " . Booking::count() . " total bookings (showing latest 10)</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Code</th><th>Tenant</th><th>Status</th><th>Payments</th><th>Can Pay?</th></tr>";
    
    foreach ($bookings as $booking) {
        $canPay = in_array($booking->status, ['pending', 'confirmed']) && 
                  !$booking->payments()->whereIn('status', ['settlement', 'capture'])->exists();
        
        $paymentStatuses = $booking->payments->pluck('status')->toArray();
        $paymentInfo = empty($paymentStatuses) ? '<span class="warning">No payments</span>' : implode(', ', $paymentStatuses);
        
        echo "<tr>";
        echo "<td>{$booking->id}</td>";
        echo "<td>{$booking->booking_code}</td>";
        echo "<td>{$booking->user->name ?? 'N/A'}</td>";
        echo "<td><strong>{$booking->status}</strong></td>";
        echo "<td>{$paymentInfo}</td>";
        echo "<td>" . ($canPay ? '<span class="success">✅ YES</span>' : '<span class="error">❌ NO</span>') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check pending/confirmed bookings
    $payableBookings = Booking::whereIn('status', ['pending', 'confirmed'])
        ->whereDoesntHave('payments', function ($query) {
            $query->whereIn('status', ['settlement', 'capture']);
        })
        ->count();
    
    if ($payableBookings === 0) {
        echo "<p class='error'>❌ Tidak ada booking yang bisa dibayar!</p>";
        echo "<p class='warning'>⚠️ <strong>SOLUSI:</strong> Buat booking baru dengan status 'pending'</p>";
    } else {
        echo "<p class='success'>✅ Ada {$payableBookings} booking yang bisa dibayar</p>";
    }
}

// ==============================================
// 4. CHECK DATABASE - PAYMENTS
// ==============================================
echo "<h2>💳 Step 4: Check Payment Records</h2>";

$payments = Payment::with(['booking.user'])
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

if ($payments->isEmpty()) {
    echo "<p class='error'>❌ Tidak ada payment record sama sekali!</p>";
    echo "<p class='warning'>⚠️ <strong>INI MASALAHNYA:</strong> Tenant belum pernah klik 'Bayar Sekarang'</p>";
    echo "<pre>";
    echo "Langkah-langkah:\n";
    echo "1. Login sebagai tenant\n";
    echo "2. Buka: https://arkanta.my.id/tenant/payments-midtrans/create\n";
    echo "3. Pilih booking dari dropdown\n";
    echo "4. Klik tombol 'Bayar Sekarang'\n";
    echo "5. Tunggu popup Midtrans muncul\n";
    echo "</pre>";
} else {
    echo "<p class='success'>✅ Found " . Payment::count() . " payment records (showing latest 10)</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Order ID</th><th>Tenant</th><th>Amount</th><th>Status</th><th>Snap Token</th><th>Transaction ID</th><th>Created</th></tr>";
    
    foreach ($payments as $payment) {
        echo "<tr>";
        echo "<td>{$payment->id}</td>";
        echo "<td><code>{$payment->order_id}</code></td>";
        echo "<td>{$payment->booking->user->name ?? 'N/A'}</td>";
        echo "<td>Rp " . number_format($payment->amount, 0, ',', '.') . "</td>";
        echo "<td><strong>{$payment->status}</strong></td>";
        echo "<td>" . ($payment->snap_token ? '<span class="success">✅ YES</span>' : '<span class="error">❌ NO</span>') . "</td>";
        echo "<td>" . ($payment->transaction_id ?? '<span class="warning">-</span>') . "</td>";
        echo "<td>" . $payment->created_at->format('Y-m-d H:i:s') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check if snap tokens exist
    $withSnapToken = Payment::whereNotNull('snap_token')->count();
    $withTransactionId = Payment::whereNotNull('transaction_id')->count();
    
    echo "<h3>Payment Statistics</h3>";
    echo "<ul>";
    echo "<li>Total payments: " . Payment::count() . "</li>";
    echo "<li>With snap_token: {$withSnapToken} " . ($withSnapToken > 0 ? '<span class="success">✅</span>' : '<span class="error">❌</span>') . "</li>";
    echo "<li>With transaction_id: {$withTransactionId} " . ($withTransactionId > 0 ? '<span class="success">✅</span>' : '<span class="warning">⚠️ Belum ada webhook</span>') . "</li>";
    echo "</ul>";
    
    if ($withSnapToken === 0) {
        echo "<p class='error'>❌ <strong>CRITICAL:</strong> Tidak ada payment dengan snap_token!</p>";
        echo "<p>Ini berarti Midtrans API tidak pernah berhasil dipanggil.</p>";
        echo "<p class='warning'>⚠️ Check Laravel logs untuk error:</p>";
        echo "<pre>railway logs | grep 'MIDTRANS CHECKOUT ERROR'</pre>";
    }
}

// ==============================================
// 5. TEST MIDTRANS API CONNECTION
// ==============================================
echo "<h2>🌐 Step 5: Test Midtrans API Connection</h2>";

try {
    \Midtrans\Config::$serverKey = $serverKey;
    \Midtrans\Config::$isProduction = $isProduction;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;
    
    // Test dengan fake transaction
    $testOrderId = 'TEST-' . time();
    $params = [
        'transaction_details' => [
            'order_id' => $testOrderId,
            'gross_amount' => 10000,
        ],
        'customer_details' => [
            'first_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
        ],
        'item_details' => [
            [
                'id' => 'TEST-ITEM',
                'price' => 10000,
                'quantity' => 1,
                'name' => 'Test Payment',
            ]
        ],
    ];
    
    echo "<p>Testing Midtrans API with test transaction...</p>";
    echo "<pre>Order ID: {$testOrderId}</pre>";
    
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    
    echo "<p class='success'>✅ <strong>SUCCESS!</strong> Midtrans API berhasil dipanggil</p>";
    echo "<pre>Snap Token: " . substr($snapToken, 0, 50) . "...</pre>";
    echo "<p class='success'>✅ Credentials valid dan API working!</p>";
    
    echo "<h3>✅ Check di Midtrans Dashboard:</h3>";
    echo "<ol>";
    echo "<li>Login: <a href='https://dashboard.sandbox.midtrans.com' target='_blank'>https://dashboard.sandbox.midtrans.com</a></li>";
    echo "<li>Menu: <strong>Transactions</strong></li>";
    echo "<li>Search: <code>{$testOrderId}</code></li>";
    echo "<li>Status: <strong>pending</strong> (belum dibayar)</li>";
    echo "</ol>";
    
} catch (\Exception $e) {
    echo "<p class='error'>❌ <strong>MIDTRANS API ERROR:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre>File: " . $e->getFile() . "</pre>";
    echo "<pre>Line: " . $e->getLine() . "</pre>";
    
    if (strpos($e->getMessage(), '401') !== false) {
        echo "<p class='error'>❌ Authentication Error - Server Key salah atau invalid</p>";
    } elseif (strpos($e->getMessage(), '400') !== false) {
        echo "<p class='error'>❌ Bad Request - Parameter tidak valid</p>";
    }
}

// ==============================================
// 6. SUMMARY & RECOMMENDATIONS
// ==============================================
echo "<h2>📊 Summary & Recommendations</h2>";

$issues = [];
$recommendations = [];

// Check 1: Credentials
if (empty($serverKey) || empty($clientKey)) {
    $issues[] = "Midtrans credentials belum diset";
    $recommendations[] = "Set MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di Railway";
}

// Check 2: Bookings
$payableBookings = Booking::whereIn('status', ['pending', 'confirmed'])
    ->whereDoesntHave('payments', function ($query) {
        $query->whereIn('status', ['settlement', 'capture']);
    })
    ->count();

if ($payableBookings === 0) {
    $issues[] = "Tidak ada booking yang bisa dibayar";
    $recommendations[] = "Buat booking baru sebagai tenant";
}

// Check 3: Payments
$totalPayments = Payment::count();
if ($totalPayments === 0) {
    $issues[] = "Tidak ada payment record sama sekali";
    $recommendations[] = "Tenant harus klik 'Bayar Sekarang' di halaman payment";
}

// Check 4: Snap tokens
$withSnapToken = Payment::whereNotNull('snap_token')->count();
if ($totalPayments > 0 && $withSnapToken === 0) {
    $issues[] = "Payment ada tapi tidak ada snap_token";
    $recommendations[] = "Check Laravel logs: railway logs | grep 'MIDTRANS CHECKOUT ERROR'";
}

if (empty($issues)) {
    echo "<p class='success'>✅ <strong>SEMUA OK!</strong> Sistem berjalan normal.</p>";
    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Login sebagai tenant</li>";
    echo "<li>Pilih booking untuk dibayar</li>";
    echo "<li>Klik 'Bayar Sekarang'</li>";
    echo "<li>Complete payment dengan test card</li>";
    echo "<li>Check Midtrans dashboard</li>";
    echo "</ol>";
} else {
    echo "<h3 class='error'>❌ Found " . count($issues) . " issue(s):</h3>";
    echo "<ul>";
    foreach ($issues as $issue) {
        echo "<li class='error'>{$issue}</li>";
    }
    echo "</ul>";
    
    echo "<h3 class='success'>✅ Recommendations:</h3>";
    echo "<ol>";
    foreach ($recommendations as $rec) {
        echo "<li class='success'>{$rec}</li>";
    }
    echo "</ol>";
}

echo "<hr>";
echo "<p style='text-align: center; color: #888;'>Debug script generated at " . date('Y-m-d H:i:s') . "</p>";
