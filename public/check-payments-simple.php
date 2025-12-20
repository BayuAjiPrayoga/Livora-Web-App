<!DOCTYPE html>
<html>
<head>
    <title>Payment Order IDs</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #FF6600; }
        .order-id { 
            background: #f0f0f0; 
            padding: 15px; 
            margin: 10px 0; 
            border-left: 4px solid #FF6600;
            font-family: monospace;
            font-size: 16px;
            word-break: break-all;
        }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #FF6600; color: white; }
        .instructions { background: #fff3cd; padding: 15px; border-radius: 4px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Payment Order IDs</h1>
        
        <?php
        // Direct MySQL connection
        $host = getenv('MYSQLHOST') ?: 'localhost';
        $port = getenv('MYSQLPORT') ?: '3306';
        $database = getenv('MYSQLDATABASE') ?: 'railway';
        $username = getenv('MYSQLUSER') ?: 'root';
        $password = getenv('MYSQLPASSWORD') ?: '';
        
        try {
            $pdo = new PDO(
                "mysql:host={$host};port={$port};dbname={$database}",
                $username,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            echo '<p class="success">✅ Connected to database</p>';
            
            // Query payments with snap_token
            $stmt = $pdo->query("
                SELECT 
                    p.id,
                    p.order_id,
                    p.booking_id,
                    p.amount,
                    p.status,
                    SUBSTRING(p.snap_token, 1, 30) AS snap_token_preview,
                    p.transaction_id,
                    p.payment_type,
                    p.created_at
                FROM payments p
                WHERE p.snap_token IS NOT NULL
                ORDER BY p.created_at DESC
                LIMIT 20
            ");
            
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($payments)) {
                echo '<p class="error">❌ Tidak ada payment dengan snap_token</p>';
                echo '<p>Ini berarti Midtrans API belum pernah berhasil dipanggil.</p>';
            } else {
                echo '<p class="success">✅ Found ' . count($payments) . ' payment(s) dengan snap_token</p>';
                
                echo '<h2>📋 Order IDs untuk dicari di Midtrans Dashboard</h2>';
                
                echo '<div class="instructions">';
                echo '<strong>Cara cek di Midtrans:</strong><br>';
                echo '1. Login: <a href="https://dashboard.sandbox.midtrans.com" target="_blank">https://dashboard.sandbox.midtrans.com</a><br>';
                echo '2. Email: bayuwanderlust@gmail.com<br>';
                echo '3. Menu: <strong>Transactions</strong><br>';
                echo '4. Di search box, copy paste Order ID dibawah ini (EXACT):';
                echo '</div>';
                
                foreach ($payments as $payment) {
                    echo '<div class="order-id">';
                    echo '<strong>Order ID:</strong> ' . htmlspecialchars($payment['order_id']) . '<br>';
                    echo '<small>';
                    echo 'Payment ID: ' . $payment['id'] . ' | ';
                    echo 'Amount: Rp ' . number_format($payment['amount'], 0, ',', '.') . ' | ';
                    echo 'Status: ' . strtoupper($payment['status']) . ' | ';
                    echo 'Created: ' . $payment['created_at'];
                    echo '</small>';
                    echo '</div>';
                }
                
                echo '<h2>📊 Detail Table</h2>';
                echo '<table>';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Order ID</th>';
                echo '<th>Amount</th>';
                echo '<th>Status</th>';
                echo '<th>Snap Token</th>';
                echo '<th>Created</th>';
                echo '</tr>';
                
                foreach ($payments as $payment) {
                    echo '<tr>';
                    echo '<td>' . $payment['id'] . '</td>';
                    echo '<td style="font-family: monospace; font-size: 11px;">' . htmlspecialchars($payment['order_id']) . '</td>';
                    echo '<td>Rp ' . number_format($payment['amount'], 0, ',', '.') . '</td>';
                    echo '<td><strong>' . strtoupper($payment['status']) . '</strong></td>';
                    echo '<td style="font-size: 10px;">' . ($payment['snap_token_preview'] ?: '-') . '...</td>';
                    echo '<td style="font-size: 11px;">' . $payment['created_at'] . '</td>';
                    echo '</tr>';
                }
                
                echo '</table>';
                
                // Check for NULL order_ids
                $nullOrderIds = $pdo->query("
                    SELECT COUNT(*) as count 
                    FROM payments 
                    WHERE snap_token IS NOT NULL AND order_id IS NULL
                ")->fetch(PDO::FETCH_ASSOC);
                
                if ($nullOrderIds['count'] > 0) {
                    echo '<p class="error">⚠️ Found ' . $nullOrderIds['count'] . ' payment(s) dengan snap_token tapi order_id NULL!</p>';
                    echo '<p>Ini bug! Order ID wajib ada untuk cari di Midtrans.</p>';
                }
            }
            
            // Check all payments (with or without snap_token)
            echo '<h2>📈 All Payments Summary</h2>';
            $allPayments = $pdo->query("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN snap_token IS NOT NULL THEN 1 ELSE 0 END) as with_token,
                    SUM(CASE WHEN snap_token IS NULL THEN 1 ELSE 0 END) as without_token
                FROM payments
            ")->fetch(PDO::FETCH_ASSOC);
            
            echo '<table style="max-width: 400px;">';
            echo '<tr><th>Metric</th><th>Count</th></tr>';
            echo '<tr><td>Total Payments</td><td>' . $allPayments['total'] . '</td></tr>';
            echo '<tr><td>With Snap Token (sent to Midtrans)</td><td class="success">' . $allPayments['with_token'] . '</td></tr>';
            echo '<tr><td>Without Snap Token (failed)</td><td class="error">' . $allPayments['without_token'] . '</td></tr>';
            echo '</table>';
            
        } catch (PDOException $e) {
            echo '<p class="error">❌ Database Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
        
        <div class="instructions">
            <h3>❓ Jika Masih Tidak Ketemu di Midtrans</h3>
            <p><strong>Kemungkinan penyebab:</strong></p>
            <ol>
                <li><strong>Merchant ID berbeda</strong> - Pastikan login dengan email <code>bayuwanderlust@gmail.com</code></li>
                <li><strong>Environment salah</strong> - Pastikan di Sandbox bukan Production</li>
                <li><strong>Filter status</strong> - Di Midtrans dashboard, pilih "All Status"</li>
                <li><strong>Transaksi expired</strong> - Coba buat transaksi baru</li>
            </ol>
            
            <p><strong>Solusi: Buat transaksi baru SEKARANG</strong></p>
            <ol>
                <li>Login sebagai tenant</li>
                <li>Buka: <a href="/tenant/payments-midtrans/create">/tenant/payments-midtrans/create</a></li>
                <li>Pilih booking</li>
                <li>Klik "Bayar Sekarang"</li>
                <li>Langsung check Midtrans dashboard dalam 1 menit</li>
            </ol>
        </div>
    </div>
</body>
</html>
