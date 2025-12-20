# Integrasi Midtrans Payment Gateway - LIVORA

## 📋 Overview

Dokumentasi lengkap integrasi **Midtrans Payment Gateway (Snap API)** ke dalam aplikasi LIVORA dengan fokus pada **keamanan tingkat CNS (Computer Network Security)**.

---

## 🔧 Instalasi & Konfigurasi

### 1. Install Midtrans PHP SDK

```bash
composer require midtrans/midtrans-php
```

✅ **Status**: Berhasil diinstall (midtrans/midtrans-php v2.6.2)

### 2. Environment Configuration

File: `.env`

```env
# Midtrans Payment Gateway Configuration
MIDTRANS_SERVER_KEY=your-server-key-here
MIDTRANS_CLIENT_KEY=your-client-key-here
MIDTRANS_MERCHANT_ID=your-merchant-id-here
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

⚠️ **PENTING untuk Production**:

-   Set `MIDTRANS_IS_PRODUCTION=true`
-   Gunakan Server Key dan Client Key untuk Production dari Midtrans Dashboard
-   Aktifkan 3DS untuk keamanan tambahan pada transaksi kartu kredit

### 3. Config File

File: `config/midtrans.php`

```php
return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    'notification_url' => env('APP_URL') . '/api/payment/notification',
];
```

---

## 🗄️ Database Migration

### File Migration

File: `database/migrations/2025_12_20_054341_add_midtrans_fields_to_payments_table.php`

**Kolom yang ditambahkan**:

-   `snap_token` - Token untuk Snap popup
-   `order_id` - Unique order identifier (LIVORA-{booking_id}-{timestamp})
-   `transaction_id` - Transaction ID dari Midtrans
-   `payment_type` - Tipe pembayaran (bank_transfer, gopay, credit_card, dll)
-   `payment_method` - Metode pembayaran spesifik
-   `midtrans_status` - Status dari Midtrans (capture, settlement, pending, dll)
-   `transaction_time` - Waktu transaksi
-   `midtrans_response` - Full JSON response dari Midtrans untuk audit

### Menjalankan Migration

```bash
php artisan migrate
```

⚠️ **Note**: Migration belum dijalankan karena database tidak running. Jalankan saat deploy ke Railway.

---

## 🔒 Fitur Keamanan CNS (Computer Network Security)

### 1. Signature Key Verification

**Implementasi** di `MidtransNotificationController::verifySignature()`

#### Algoritma Verifikasi:

```
Expected Signature = SHA512(order_id + status_code + gross_amount + ServerKey)
```

#### Security Features:

1. **Double Layer Verification**

    - Layer 1: Manual signature verification
    - Layer 2: Midtrans SDK automatic verification

2. **Hash Algorithm**: SHA-512 (512-bit)

    - Lebih secure daripada SHA-256
    - Resistant terhadap collision attack

3. **Constant-Time Comparison**

    ```php
    hash_equals($expectedSignature, $signatureKey)
    ```

    - Mencegah timing attack
    - Tidak memberikan informasi tentang perbedaan signature

4. **Logging & Audit Trail**
    - Semua notifikasi dicatat dengan IP address
    - Failed verification dicatat untuk monitoring
    - Full payload disimpan untuk forensik

### 2. Proteksi terhadap Attack Vectors

#### Man-in-the-Middle (MITM) Attack

-   ✅ Signature verification memastikan data tidak diubah
-   ✅ HTTPS/SSL encryption (Railway automatic)
-   ✅ Server Key tidak pernah terekspos ke client

#### Data Manipulation/Tampering

-   ✅ Signature mismatch akan reject request
-   ✅ Hash mencakup critical fields (order_id, status_code, amount)
-   ✅ Database transaction untuk consistency

#### Replay Attack

-   ✅ Order ID unik per transaksi
-   ✅ Transaction time validation
-   ✅ Status transition validation

#### Unauthorized Webhook Requests

-   ✅ Hanya request dengan valid signature diterima
-   ✅ IP logging untuk tracking
-   ✅ 403 Forbidden untuk invalid signature

### 3. Security Best Practices

1. **Server Key Protection**

    - ❌ Never commit to Git
    - ✅ Stored in environment variables
    - ✅ Different keys for Sandbox/Production

2. **CSRF Protection**

    - ✅ Laravel CSRF token untuk checkout endpoint
    - ⚠️ Webhook endpoint exempt (signature verification menggantikan CSRF)

3. **SQL Injection Prevention**

    - ✅ Eloquent ORM dengan parameter binding
    - ✅ Validated input data

4. **XSS Prevention**
    - ✅ Blade templating auto-escape
    - ✅ JSON response validation

---

## 🛣️ Routing Configuration

### Web Routes (`routes/web.php`)

```php
Route::prefix('tenant')->name('tenant.')->middleware('auth')->group(function () {
    // Existing payment routes
    Route::resource('payments', \App\Http\Controllers\Tenant\PaymentController::class);

    // Midtrans routes
    Route::get('/payments-midtrans/create', ...)->name('payments.midtrans.create');
    Route::post('/payments/midtrans/checkout', [PaymentController::class, 'createMidtransCheckout'])
        ->name('payments.midtrans.checkout');
    Route::get('/payments/finish', [PaymentController::class, 'finishPayment'])
        ->name('payments.finish');
});
```

### API Routes (`routes/api.php`)

```php
// Webhook - NO AUTHENTICATION (uses signature verification)
Route::post('/payment/notification', [MidtransNotificationController::class, 'handle']);
```

⚠️ **CRITICAL**: Webhook endpoint TIDAK menggunakan auth middleware karena:

-   Request datang dari Midtrans server, bukan user
-   Authentication dilakukan via signature verification
-   Lebih secure daripada bearer token

---

## 🎯 Flow Pembayaran

### 1. User Flow

```
User → Pilih Booking → Klik "Bayar Online" → Pilih Metode → Bayar → Notifikasi → Status Update
```

### 2. Technical Flow

#### A. Checkout Process

```
1. User mengakses /tenant/payments-midtrans/create
2. User memilih booking yang akan dibayar
3. User klik "Bayar Sekarang"
4. AJAX POST ke /tenant/payments/midtrans/checkout
5. Controller membuat transaksi:
   - Generate unique order_id
   - Create payment record
   - Call Midtrans Snap API
   - Get snap_token
6. Frontend membuka Snap popup dengan snap_token
7. User menyelesaikan pembayaran di Snap
```

#### B. Notification Process (Webhook)

```
1. Midtrans mengirim POST request ke /api/payment/notification
2. Controller verify signature:
   ✓ Calculate expected signature
   ✓ Compare dengan signature dari request
   ✓ Reject jika tidak match
3. Parse notification data
4. Update payment record
5. Update booking status (jika settlement)
6. Return 200 OK response
```

#### C. Status Mapping

| Midtrans Status       | LIVORA Status | Booking Status | Description              |
| --------------------- | ------------- | -------------- | ------------------------ |
| `pending`             | `pending`     | -              | Menunggu pembayaran      |
| `settlement`          | `verified`    | `confirmed`    | Pembayaran sukses        |
| `capture` (accept)    | `verified`    | `confirmed`    | CC payment sukses        |
| `capture` (challenge) | `pending`     | -              | Under fraud review       |
| `deny`                | `rejected`    | -              | Ditolak payment provider |
| `expire`              | `rejected`    | -              | Expired                  |
| `cancel`              | `rejected`    | -              | Dibatalkan               |

---

## 📁 File Structure

```
app/
├── Http/Controllers/
│   ├── Tenant/
│   │   └── PaymentController.php         # Checkout logic
│   └── Api/
│       └── MidtransNotificationController.php  # Webhook handler (CNS Secure)
├── Models/
│   ├── Payment.php                       # Updated with Midtrans fields
│   └── Booking.php
config/
└── midtrans.php                          # Midtrans configuration
database/migrations/
└── 2025_12_20_054341_add_midtrans_fields_to_payments_table.php
resources/views/tenant/payments/
├── index.blade.php                       # Updated dengan tombol Midtrans
└── midtrans.blade.php                    # Midtrans checkout page
routes/
├── web.php                               # Tenant routes
└── api.php                               # Webhook route
```

---

## 🎨 Frontend Integration

### Midtrans Snap Script

Di `resources/views/tenant/payments/midtrans.blade.php`:

```html
<!-- Midtrans Snap JS -->
<script
    type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"
></script>
```

### Payment Button Handler

```javascript
snap.pay(snapToken, {
    onSuccess: function (result) {
        // Redirect ke payments index dengan success message
        window.location.href = "/tenant/payments?payment_success=1";
    },
    onPending: function (result) {
        // Redirect dengan pending message
        window.location.href = "/tenant/payments?payment_pending=1";
    },
    onError: function (result) {
        // Show error message
        alert("Terjadi kesalahan saat memproses pembayaran.");
    },
    onClose: function () {
        // User menutup popup tanpa menyelesaikan pembayaran
        console.log("Payment popup closed");
    },
});
```

---

## 🧪 Testing

### 1. Sandbox Testing

**Test Cards** (Midtrans Sandbox):

```
Success Card:
- Card Number: 4811 1111 1111 1114
- Exp: 01/25
- CVV: 123

Challenge Card (Fraud Detection):
- Card Number: 4611 1111 1111 1113
- Exp: 01/25
- CVV: 123
```

### 2. Webhook Testing

Tool: **Postman** atau **ngrok**

#### Testing dengan Postman:

```bash
POST http://localhost/api/payment/notification
Content-Type: application/json

{
    "order_id": "LIVORA-123-1703059234",
    "status_code": "200",
    "gross_amount": "500000.00",
    "signature_key": "calculated_sha512_hash",
    "transaction_status": "settlement",
    "transaction_id": "test-transaction-123",
    "payment_type": "bank_transfer"
}
```

**Calculate Signature**:

```php
$signature = hash('sha512',
    'LIVORA-123-1703059234' . '200' . '500000.00' . config('midtrans.server_key')
);
```

#### Testing dengan ngrok (untuk testing dari Midtrans):

```bash
ngrok http 80
# Copy URL (contoh: https://abc123.ngrok.io)
# Set di Midtrans Dashboard → Settings → Notification URL
```

---

## 🚀 Deployment ke Railway

### 1. Environment Variables

Di Railway Dashboard, set:

```
MIDTRANS_SERVER_KEY=Mid-server-xxx (Production Key)
MIDTRANS_CLIENT_KEY=Mid-client-xxx (Production Key)
MIDTRANS_MERCHANT_ID=Gxxxxxx
MIDTRANS_IS_PRODUCTION=true
```

### 2. Notification URL

Set di **Midtrans Dashboard**:

```
https://your-app.railway.app/api/payment/notification
```

### 3. Migration Command

```bash
php artisan migrate
```

### 4. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

---

## 📊 Monitoring & Logging

### Log Files

Lokasi: `storage/logs/laravel.log`

**Log Events**:

1. ✅ Checkout created
2. ✅ Notification received (with IP)
3. ⚠️ Signature verification failed
4. ✅ Payment status updated
5. ❌ Error handling

### Log Search Commands

```bash
# Cari failed signature verification
grep "Signature Verification Failed" storage/logs/laravel.log

# Cari semua Midtrans notifications
grep "Midtrans Notification" storage/logs/laravel.log

# Cari error
grep "ERROR" storage/logs/laravel.log | grep "Midtrans"
```

---

## 🔧 Troubleshooting

### Problem 1: Signature Verification Failed

**Symptoms**: Webhook return 403 Forbidden

**Solutions**:

1. Pastikan `MIDTRANS_SERVER_KEY` benar
2. Check format signature calculation
3. Verify gross_amount format (decimal vs integer)
4. Check log untuk detail signature mismatch

### Problem 2: Snap Popup Tidak Muncul

**Solutions**:

1. Check browser console untuk error
2. Verify Client Key di `.env`
3. Pastikan Snap.js loaded
4. Check CORS settings

### Problem 3: Payment Status Tidak Update

**Solutions**:

1. Check webhook URL accessibility
2. Verify notification URL di Midtrans Dashboard
3. Check logs untuk webhook errors
4. Test webhook manually dengan Postman

---

## 📚 Resources

### Documentation Links

1. **Midtrans Docs**: https://docs.midtrans.com/
2. **Snap Integration**: https://docs.midtrans.com/en/snap/overview
3. **Notification Handler**: https://docs.midtrans.com/en/after-payment/http-notification
4. **Security Guide**: https://docs.midtrans.com/en/technical-reference/security-guide

### Support

-   Midtrans Support: support@midtrans.com
-   Technical Slack: https://midtrans.com/slack

---

## ✅ Checklist Deployment

-   [x] Install Midtrans SDK
-   [x] Configure environment variables
-   [x] Create migration untuk kolom Midtrans
-   [x] Implement checkout logic
-   [x] Implement webhook dengan signature verification
-   [x] Update routes
-   [x] Create frontend UI
-   [ ] Run migration di production
-   [ ] Set notification URL di Midtrans Dashboard
-   [ ] Test dengan sandbox
-   [ ] Switch ke production keys
-   [ ] Monitor logs untuk errors

---

## 🎓 CNS Security Summary

Implementasi ini memenuhi standar **Computer Network Security** dengan:

1. ✅ **Cryptographic Verification**: SHA-512 signature
2. ✅ **Constant-Time Comparison**: Mencegah timing attack
3. ✅ **Audit Logging**: Complete audit trail
4. ✅ **Double Layer Security**: Manual + SDK verification
5. ✅ **Secure Key Management**: Environment variables
6. ✅ **Input Validation**: Semua input divalidasi
7. ✅ **Database Transaction**: Data consistency
8. ✅ **Error Handling**: Graceful degradation
9. ✅ **HTTPS Enforcement**: SSL/TLS encryption
10. ✅ **No Sensitive Data Exposure**: Client-side aman

---

**Dibuat oleh**: GitHub Copilot
**Tanggal**: 20 Desember 2025
**Project**: LIVORA - Boarding House Management System
**Security Level**: CNS Grade
