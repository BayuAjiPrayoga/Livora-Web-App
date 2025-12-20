# Panduan Debugging Midtrans Payment Flow

## Status Fixes

✅ Blade syntax error fixed (midtrans.blade.php)
✅ Intelephense error fixed (web.php line 123)
✅ Comprehensive logging added to PaymentController
✅ Enhanced webhook logging in MidtransNotificationController

---

## Cara Testing End-to-End Payment Flow

### 1. Persiapan Testing

**Pastikan Railway deployment selesai:**

```bash
# Check Railway logs
railway logs --service livora-web-app
```

**Environment variables di Railway (CONFIRMED):**

-   ✅ MIDTRANS_SERVER_KEY: `Mid-server-RBlm*********************` (Sandbox)
-   ✅ MIDTRANS_CLIENT_KEY: `Mid-client-cyFA************`
-   ✅ MIDTRANS_MERCHANT_ID: `G439181641`
-   ✅ MIDTRANS_IS_PRODUCTION: `false`

---

### 2. Test Payment Flow (Step-by-Step)

#### Step 1: Clear Browser Cache

```
1. Hard refresh: Ctrl + Shift + R
2. Clear browser cache
3. Disable ALL ad-blockers (uBlock, AdBlock, dll)
4. ATAU gunakan Incognito/Private mode
```

#### Step 2: Login sebagai Tenant

```
URL: https://arkanta.my.id/login
Role: tenant
```

#### Step 3: Buka Halaman Pembayaran

```
URL: https://arkanta.my.id/tenant/payments-midtrans/create
```

**Yang harus muncul:**

-   Dropdown berisi booking yang available
-   Jika kosong, buat booking baru dulu

#### Step 4: Check Console Log

```javascript
// Buka Developer Console (F12)
// Harus muncul:
"✅ Midtrans Snap.js loaded successfully";

// Jika muncul error:
"❌ Midtrans Snap.js FAILED TO LOAD";
// -> Disable ad-blocker dan refresh
```

#### Step 5: Pilih Booking & Klik "Bayar Sekarang"

**Expected behavior:**

1. Button berubah jadi "Memproses..."
2. Console log: `=== MIDTRANS RESPONSE ===`
3. Popup Midtrans muncul
4. Pilih metode pembayaran (Credit Card)

**Test Credit Card (Sandbox):**

```
Card Number: 4811 1111 1111 1114
CVV: 123
Exp: 01/26
OTP: 112233
```

---

### 3. Debugging dengan Laravel Logs

#### Check Application Logs

```bash
# Local (Laragon)
tail -f storage/logs/laravel.log

# Railway
railway logs --service livora-web-app
```

#### Logs yang harus muncul:

**1. Saat buka halaman pembayaran:**

```
[INFO] Payment page - Available bookings check
    user_id: X
    total_bookings: X
    available_count: X
```

**2. Saat klik "Bayar Sekarang":**

```
[INFO] === MIDTRANS CHECKOUT START ===
    user_id: X
    booking_id: X
    amount: X

[INFO] Booking found
    booking_id: X
    booking_code: XXX

[INFO] Creating new payment record
    order_id: LIVORA-X-TIMESTAMP

[INFO] Payment record created
    payment_id: X

[INFO] Calling Midtrans Snap API
    gross_amount: X

[INFO] Snap token received
    snap_token_prefix: ...

[INFO] === MIDTRANS CHECKOUT SUCCESS ===
```

**3. Saat pembayaran berhasil (webhook):**

```
[INFO] === MIDTRANS WEBHOOK RECEIVED ===
    order_id: LIVORA-X-TIMESTAMP
    transaction_status: settlement

[INFO] === WEBHOOK PROCESSED SUCCESSFULLY ===
    final_status: settlement
```

---

### 4. Check Database

#### Cek Payment Record

```sql
-- Check payment yang dibuat
SELECT
    id,
    order_id,
    booking_id,
    amount,
    status,
    snap_token,
    transaction_id,
    payment_type,
    created_at
FROM payments
ORDER BY created_at DESC
LIMIT 5;
```

**Expected result:**

-   `order_id`: LIVORA-{booking_id}-{timestamp}
-   `status`: pending -> settlement (setelah bayar)
-   `snap_token`: ada value
-   `transaction_id`: ada value (setelah webhook)

#### Cek Booking Status

```sql
-- Check booking status update
SELECT
    id,
    booking_code,
    status,
    user_id,
    updated_at
FROM bookings
WHERE id = {booking_id};
```

---

### 5. Troubleshooting Common Issues

#### Issue 1: "Midtrans Snap.js not loaded"

**Penyebab:**

-   Ad-blocker blocking script
-   Network/firewall blocking Midtrans domain

**Solusi:**

1. Disable ALL browser extensions
2. Use Incognito/Private mode
3. Check Network tab (F12) untuk request ke `app.sandbox.midtrans.com/snap/snap.js`

---

#### Issue 2: "snap is not defined"

**Penyebab:**

-   Snap.js gagal load
-   Blade syntax error (SUDAH FIXED)

**Solusi:**

1. Hard refresh (Ctrl + Shift + R)
2. Check Console untuk syntax errors
3. Verify deployment selesai

---

#### Issue 3: Dashboard Midtrans kosong

**Penyebab:**

-   Payment popup tidak dibuka/closed langsung
-   User belum complete payment
-   Webhook tidak terkirim

**Debugging:**

1. Check logs untuk "=== MIDTRANS CHECKOUT SUCCESS ==="
2. Pastikan popup Midtrans muncul
3. Complete test payment sampai selesai
4. Check webhook logs di Railway

**Test Webhook Manual:**

```bash
# Test webhook locally (jika pakai ngrok)
curl -X POST https://arkanta.my.id/api/midtrans/notification \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "LIVORA-123-1234567890",
    "transaction_status": "settlement",
    "transaction_id": "test-12345",
    "payment_type": "credit_card",
    "gross_amount": "500000"
  }'
```

---

#### Issue 4: HTTP 401 Unauthorized

**Penyebab:**

-   Server key salah (SUDAH FIXED)
-   Prefix validation error (SUDAH FIXED)

**Verify config:**

```bash
# Railway
railway run php artisan tinker
>>> config('midtrans.server_key')
=> "Mid-server-RBlm*********************"
```

---

### 6. Verification Checklist

**Frontend (User Experience):**

-   [ ] Page load tanpa syntax error
-   [ ] Dropdown booking muncul
-   [ ] Console: "✅ Midtrans Snap.js loaded"
-   [ ] Klik "Bayar Sekarang" → popup muncul
-   [ ] Pilih payment method → form lengkap
-   [ ] Submit payment → redirect success

**Backend (Logs):**

-   [ ] Log: "=== MIDTRANS CHECKOUT START ==="
-   [ ] Log: "Payment record created"
-   [ ] Log: "Snap token received"
-   [ ] Log: "=== MIDTRANS CHECKOUT SUCCESS ==="
-   [ ] Log: "=== MIDTRANS WEBHOOK RECEIVED ===" (setelah bayar)

**Database:**

-   [ ] Table `payments` ada record baru
-   [ ] Field `snap_token` terisi
-   [ ] Field `status` = 'pending'
-   [ ] Field `status` berubah jadi 'settlement' (setelah bayar)
-   [ ] Table `bookings` status update (setelah webhook)

**Midtrans Dashboard:**

-   [ ] Login: https://dashboard.sandbox.midtrans.com
-   [ ] Menu: Transactions
-   [ ] Transaksi muncul dengan order_id: `LIVORA-*`
-   [ ] Status: settlement
-   [ ] Amount sesuai

---

## Quick Diagnostic Commands

```bash
# Check if Railway deployment selesai
railway status

# Check latest logs
railway logs --tail 100

# Check Midtrans config
railway run php artisan tinker
>>> config('midtrans.server_key')
>>> config('midtrans.client_key')

# Clear application cache
railway run php artisan cache:clear
railway run php artisan config:clear

# Check database payments
railway run php artisan tinker
>>> \App\Models\Payment::latest()->take(5)->get(['id', 'order_id', 'status', 'amount'])
```

---

## Contact Midtrans Support

Jika masih ada issue setelah semua debugging:

1. **Login Midtrans Dashboard:** https://dashboard.sandbox.midtrans.com
2. **Menu:** Settings → Webhook Settings
3. **Set Webhook URL:** `https://arkanta.my.id/api/midtrans/notification`
4. **Test webhook** dari dashboard

**Support:**

-   Email: support@midtrans.com
-   Docs: https://docs.midtrans.com
-   Slack: https://midtrans.com/slack

---

## Summary of Changes

### Files Modified:

1. ✅ `resources/views/tenant/payments/midtrans.blade.php`

    - Removed ALL Blade {{ }} from JavaScript
    - Fixed 4 syntax errors

2. ✅ `routes/web.php` (line 123)

    - Changed `auth()->id()` → `\Illuminate\Support\Facades\Auth::id()`
    - Fixed Intelephense error

3. ✅ `app/Http/Controllers/Tenant/PaymentController.php`

    - Added comprehensive logging at every step
    - Logs: checkout start, booking found, payment created, API call, token received

4. ✅ `app/Http/Controllers/Api/MidtransNotificationController.php`
    - Enhanced webhook logging
    - Logs: webhook received, signature verified, payment found, status updated

### All Issues Fixed:

-   ✅ Property badge (is_verified)
-   ✅ Flash messages
-   ✅ Booking total_amount
-   ✅ Payment system audit
-   ✅ Midtrans 401 authentication
-   ✅ Server key typo
-   ✅ Prefix validation
-   ✅ Blade syntax errors
-   ✅ Intelephense errors
-   ✅ Comprehensive debugging logging

### Ready for Testing:

Payment flow sekarang FULLY INSTRUMENTED dengan logging. Setiap step tercatat di logs untuk troubleshooting.

**Next Steps:**

1. Wait for Railway deployment (~1 minute)
2. Hard refresh browser (Ctrl + Shift + R)
3. Test payment flow end-to-end
4. Monitor logs di Railway
5. Report any remaining issues with log excerpts
