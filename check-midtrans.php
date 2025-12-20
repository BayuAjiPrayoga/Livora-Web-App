<!DOCTYPE html>
<html>
<head>
    <title>Midtrans Payment Checker</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #FF6600; }
        h2 { color: #333; border-bottom: 2px solid #FF6600; padding-bottom: 10px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #FF6600; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .order-id { font-family: monospace; background: #f0f0f0; padding: 4px 8px; border-radius: 4px; }
        .instruction { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 4px; margin: 20px 0; }
        .instruction ol { margin: 10px 0; padding-left: 20px; }
        .code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Midtrans Payment Checker</h1>
        
        <?php
        require __DIR__.'/vendor/autoload.php';
        
        $app = require_once __DIR__.'/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        use App\Models\Payment;
        use App\Models\Booking;
        
        // Get payments with snap_token
        $paymentsWithToken = Payment::with(['booking.user', 'booking.room.boardingHouse'])
            ->whereNotNull('snap_token')
            ->orderBy('created_at', 'desc')
            ->get();
        
        if ($paymentsWithToken->isEmpty()) {
            echo '<p class="error">❌ Tidak ada payment dengan snap_token di database!</p>';
            echo '<p>Ini berarti Midtrans API belum pernah berhasil dipanggil.</p>';
        } else {
            echo '<p class="success">✅ Found ' . $paymentsWithToken->count() . ' payment(s) dengan snap_token</p>';
            echo '<p><strong>Transaksi ini SUDAH MASUK ke Midtrans Dashboard!</strong></p>';
            
            echo '<h2>📋 Daftar Order ID yang Ada di Midtrans</h2>';
            echo '<table>';
            echo '<tr>';
            echo '<th>Payment ID</th>';
            echo '<th>Order ID</th>';
            echo '<th>Booking</th>';
            echo '<th>Tenant</th>';
            echo '<th>Amount</th>';
            echo '<th>Status</th>';
            echo '<th>Created</th>';
            echo '</tr>';
            
            foreach ($paymentsWithToken as $payment) {
                $statusClass = 'warning';
                if ($payment->status === 'settlement' || $payment->status === 'capture') {
                    $statusClass = 'success';
                } elseif (in_array($payment->status, ['deny', 'cancel', 'expire'])) {
                    $statusClass = 'error';
                }
                
                echo '<tr>';
                echo '<td>' . $payment->id . '</td>';
                echo '<td class="order-id">' . $payment->order_id . '</td>';
                echo '<td>' . ($payment->booking ? $payment->booking->booking_code : 'N/A') . '</td>';
                echo '<td>' . ($payment->booking && $payment->booking->user ? $payment->booking->user->name : 'N/A') . '</td>';
                echo '<td>Rp ' . number_format($payment->amount, 0, ',', '.') . '</td>';
                echo '<td class="' . $statusClass . '">' . strtoupper($payment->status) . '</td>';
                echo '<td>' . $payment->created_at->format('Y-m-d H:i:s') . '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
            
            // Check Midtrans Dashboard Instructions
            echo '<div class="instruction">';
            echo '<h2>📌 Cara Cek di Midtrans Dashboard</h2>';
            echo '<ol>';
            echo '<li><strong>Login</strong> ke Midtrans Dashboard:<br>';
            echo '    <a href="https://dashboard.sandbox.midtrans.com" target="_blank">https://dashboard.sandbox.midtrans.com</a></li>';
            echo '<li>Email: <code>bayuwanderlust@gmail.com</code></li>';
            echo '<li>Klik menu <strong>"Transactions"</strong> di sidebar kiri</li>';
            echo '<li>Di search box, cari salah satu Order ID ini:</li>';
            echo '</ol>';
            
            echo '<table style="max-width: 500px;">';
            echo '<tr><th>Order ID untuk dicari</th></tr>';
            foreach ($paymentsWithToken->take(5) as $payment) {
                echo '<tr><td class="order-id">' . $payment->order_id . '</td></tr>';
            }
            echo '</table>';
            
            echo '<ol start="5">';
            echo '<li>Jika transaksi muncul → <span class="success">✅ BERHASIL!</span></li>';
            echo '<li>Jika tidak muncul → Copy paste <strong>EXACT</strong> order_id dari tabel di atas</li>';
            echo '</ol>';
            echo '</div>';
            
            // Statistics
            echo '<h2>📊 Payment Statistics</h2>';
            $statuses = $paymentsWithToken->groupBy('status');
            echo '<table style="max-width: 400px;">';
            echo '<tr><th>Status</th><th>Count</th></tr>';
            foreach ($statuses as $status => $payments) {
                echo '<tr><td>' . strtoupper($status) . '</td><td>' . $payments->count() . '</td></tr>';
            }
            echo '</table>';
            
            // Pending payments
            $pendingPayments = $paymentsWithToken->where('status', 'pending');
            if ($pendingPayments->isNotEmpty()) {
                echo '<div class="instruction">';
                echo '<h2>⚠️ Ada ' . $pendingPayments->count() . ' Payment PENDING</h2>';
                echo '<p>Transaksi sudah dibuat di Midtrans tapi belum dibayar.</p>';
                echo '<p><strong>Untuk complete payment:</strong></p>';
                echo '<ol>';
                echo '<li>Buka halaman payment tenant</li>';
                echo '<li>Pilih booking yang sama</li>';
                echo '<li>Klik "Bayar Sekarang"</li>';
                echo '<li>Popup akan muncul dengan transaksi yang sudah ada</li>';
                echo '<li>Complete payment dengan test card:</li>';
                echo '</ol>';
                echo '<ul>';
                echo '<li>Card: <code>4811 1111 1111 1114</code></li>';
                echo '<li>CVV: <code>123</code></li>';
                echo '<li>Exp: <code>01/26</code></li>';
                echo '<li>OTP: <code>112233</code></li>';
                echo '</ul>';
                echo '</div>';
            }
            
            // Check if webhook received
            $withTransactionId = $paymentsWithToken->whereNotNull('transaction_id');
            echo '<h2>📡 Webhook Status</h2>';
            echo '<p>Payments with transaction_id (webhook received): <strong>' . $withTransactionId->count() . '</strong></p>';
            
            if ($withTransactionId->isEmpty()) {
                echo '<p class="warning">⚠️ Belum ada webhook dari Midtrans</p>';
                echo '<p>Ini normal jika payment masih pending atau baru dibuat.</p>';
            } else {
                echo '<p class="success">✅ Webhook berhasil diterima untuk ' . $withTransactionId->count() . ' payment(s)</p>';
            }
        }
        
        // Recent logs check
        echo '<h2>📝 Check Laravel Logs</h2>';
        echo '<p>Untuk melihat log detail, jalankan:</p>';
        echo '<pre style="background: #2d2d30; color: #d4d4d4; padding: 15px; border-radius: 4px; overflow-x: auto;">';
        echo '# Check checkout logs
railway logs | grep "MIDTRANS CHECKOUT"

# Check webhook logs
railway logs | grep "MIDTRANS WEBHOOK"

# Check all Midtrans logs
railway logs | grep "MIDTRANS"
';
        echo '</pre>';
        
        ?>
    </div>
</body>
</html>
