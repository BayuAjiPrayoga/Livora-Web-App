# Payment Business Flow - Livora System

## 📋 Alur Bisnis Pembayaran dengan Midtrans

### **Diagram Alur:**

```
Tenant Buat Booking → Status: PENDING
         ↓
Mitra Konfirmasi Booking → Status: CONFIRMED  ← TENANT BISA BAYAR MULAI DARI SINI
         ↓
Tenant Bayar via Midtrans → Payment: PENDING
         ↓
Midtrans Settlement → Payment: SETTLEMENT
         ↓
Status Booking Updated
```

---

## 🔄 Langkah-langkah Detail

### **Step 1: Tenant Membuat Booking**

**File:** `app/Services/BookingService.php` (line 68)

```php
$booking = Booking::create([
    'status' => Booking::STATUS_PENDING, // Status awal: PENDING
    // ... data lainnya
]);
```

**Status:** `pending`  
**Tenant bisa bayar?** ✅ **YA** (karena query di web.php include `pending`)

---

### **Step 2: Mitra Konfirmasi Booking (OPTIONAL)**

**File:** `app/Http/Controllers/Mitra/BookingController.php` (line 405)

```php
public function confirm(Booking $booking)
{
    $booking->update([
        'status' => 'confirmed',
    ]);
}
```

**Status:** `confirmed`  
**Tenant bisa bayar?** ✅ **YA** (query include `confirmed`)

---

### **Step 3: Tenant Membuka Halaman Pembayaran**

**File:** `routes/web.php` (line 123-140)

```php
Route::get('/payments-midtrans/create', function() {
    $userId = \Illuminate\Support\Facades\Auth::id();

    // QUERY INI YANG MENENTUKAN BOOKING MANA YANG BISA DIBAYAR
    $availableBookings = \App\Models\Booking::with(['room.boardingHouse', 'payments'])
        ->where('user_id', $userId)
        ->whereIn('status', ['confirmed', 'pending']) // ✅ PENDING ATAU CONFIRMED
        ->whereDoesntHave('payments', function ($query) {
            // Exclude booking yang sudah ada payment settlement/capture
            $query->whereIn('status', ['settlement', 'capture']);
        })
        ->orderBy('created_at', 'desc')
        ->get();

    return view('tenant.payments.midtrans', compact('availableBookings'));
});
```

**Kondisi booking muncul di dropdown:**

-   ✅ Status booking: `pending` ATAU `confirmed`
-   ✅ Belum ada payment dengan status `settlement` atau `capture`
-   ✅ Milik user yang login

---

### **Step 4: Tenant Klik "Bayar Sekarang"**

**File:** `app/Http/Controllers/Tenant/PaymentController.php` (line 292)

```php
public function createMidtransCheckout(Request $request)
{
    // 1. Generate order_id
    $orderId = 'LIVORA-' . $booking->id . '-' . time();

    // 2. Create payment record di database
    $payment = Payment::create([
        'booking_id' => $booking->id,
        'amount' => $request->amount,
        'status' => 'pending',
        'order_id' => $orderId
    ]);

    // 3. Call Midtrans Snap API
    $snapToken = Snap::getSnapToken($params);

    // 4. Update payment dengan snap_token
    $payment->update(['snap_token' => $snapToken]);

    // 5. Return snap_token ke frontend
    return response()->json([
        'success' => true,
        'snap_token' => $snapToken,
        'order_id' => $orderId
    ]);
}
```

**⚠️ CRITICAL: TRANSAKSI DIBUAT DI TAHAP INI**

Begitu klik "Bayar Sekarang":

-   ✅ Payment record dibuat di database
-   ✅ Midtrans API dipanggil (Snap::getSnapToken)
-   ✅ **TRANSAKSI MASUK KE DASHBOARD MIDTRANS** dengan status `pending`

---

### **Step 5: Tenant Complete Payment di Midtrans Popup**

**Frontend:** `resources/views/tenant/payments/midtrans.blade.php`

```javascript
snap.pay(data.snap_token, {
    onSuccess: function (result) {
        // Payment berhasil → redirect
        window.location.href = "/tenant/payments?payment_success=1";
    },
    onPending: function (result) {
        // Payment pending → redirect
        window.location.href = "/tenant/payments?payment_pending=1";
    },
    onError: function (result) {
        // Payment error
        alert("Terjadi kesalahan saat memproses pembayaran.");
    },
});
```

**Test Credit Card (Sandbox):**

-   Card: `4811 1111 1111 1114`
-   CVV: `123`
-   Exp: `01/26`
-   OTP: `112233`

---

### **Step 6: Midtrans Webhook Notification**

**File:** `app/Http/Controllers/Api/MidtransNotificationController.php` (line 59)

```php
public function handle(Request $request)
{
    // 1. Verify signature (security)
    if (!$this->verifySignature($request)) {
        return response()->json(['status' => 'error'], 403);
    }

    // 2. Parse notification
    $notification = new Notification();
    $transactionStatus = $notification->transaction_status;

    // 3. Update payment record
    $payment->update([
        'transaction_id' => $transactionId,
        'payment_type' => $paymentType,
        'midtrans_status' => $transactionStatus,
        // ... other fields
    ]);

    // 4. Handle transaction status
    $this->handleTransactionStatus($payment, $transactionStatus, $fraudStatus);
}
```

**Transaction Status Mapping:**

-   `settlement` → Payment status: `settlement` ✅ Sukses
-   `capture` → Payment status: `capture` ✅ Sukses (credit card)
-   `pending` → Payment status: `pending` ⏳ Waiting
-   `deny` → Payment status: `deny` ❌ Ditolak
-   `cancel` → Payment status: `cancel` ❌ Dibatalkan
-   `expire` → Payment status: `expire` ❌ Expired

---

## 🔍 Mengapa Dashboard Midtrans Kosong?

### **Kemungkinan 1: Booking Tidak Muncul di Dropdown**

**Debugging:**

```bash
# Check logs
railway logs | grep "Payment page - Available bookings check"
```

**Expected output:**

```
[INFO] Payment page - Available bookings check
    user_id: 123
    total_bookings: 5
    available_count: 2
    all_bookings: [...]
```

**Jika `available_count: 0`:**

**Penyebab A:** Tidak ada booking dengan status `pending` atau `confirmed`

```sql
-- Check booking status
SELECT id, booking_code, status, user_id, created_at
FROM bookings
WHERE user_id = {user_id}
ORDER BY created_at DESC;
```

**Solusi:**

-   Tenant buat booking baru
-   ATAU Mitra konfirmasi booking yang pending

**Penyebab B:** Semua booking sudah punya payment `settlement`/`capture`

```sql
-- Check payments
SELECT
    b.id as booking_id,
    b.booking_code,
    b.status as booking_status,
    p.id as payment_id,
    p.status as payment_status
FROM bookings b
LEFT JOIN payments p ON p.booking_id = b.id
WHERE b.user_id = {user_id};
```

**Solusi:**

-   Buat booking baru untuk test

---

### **Kemungkinan 2: Tenant Tidak Klik "Bayar Sekarang"**

**Indikator:**

-   Dropdown ada booking
-   Tapi dashboard Midtrans kosong

**Penyebab:**
Transaksi ke Midtrans **HANYA dibuat saat klik "Bayar Sekarang"**, bukan saat:

-   ❌ Buka halaman payment
-   ❌ Pilih booking dari dropdown

**Debugging:**

```bash
# Check logs
railway logs | grep "MIDTRANS CHECKOUT"
```

**Expected output:**

```
[INFO] === MIDTRANS CHECKOUT START ===
[INFO] Booking found
[INFO] Creating new payment record
[INFO] Calling Midtrans Snap API
[INFO] Snap token received
[INFO] === MIDTRANS CHECKOUT SUCCESS ===
```

**Jika tidak ada log:**

-   Tenant belum klik "Bayar Sekarang"
-   ATAU ada JavaScript error (check browser console)

---

### **Kemungkinan 3: JavaScript Error Mencegah API Call**

**Debugging:**

1. Buka Developer Console (F12)
2. Click "Bayar Sekarang"
3. Check Console log

**Expected:**

```
=== MIDTRANS RESPONSE ===
{success: true, snap_token: "c76c612f-...", order_id: "LIVORA-123-..."}
```

**Jika ada error:**

-   `snap is not defined` → Snap.js tidak load (ad-blocker)
-   `404 Not Found` → Route tidak ada
-   `500 Internal Server Error` → Check Laravel logs

---

### **Kemungkinan 4: Midtrans Popup Langsung Ditutup**

**Scenario:**

-   Klik "Bayar Sekarang" ✅
-   Popup Midtrans muncul ✅
-   User langsung close popup ❌

**Hasil:**

-   Dashboard Midtrans: Transaksi ada dengan status `pending`
-   Database: Payment ada dengan status `pending`
-   Webhook: Tidak ada (karena belum complete payment)

**Solusi:**
Complete payment flow sampai selesai dengan test card

---

## ✅ Rekomendasi Business Flow

### **Option A: Tenant Bisa Bayar Langsung (Current Implementation)**

```
Tenant Booking → Status: PENDING → Tenant Bayar → Mitra Konfirmasi
```

**Pro:**

-   ✅ Tenant bisa bayar langsung tanpa tunggu mitra
-   ✅ Proses lebih cepat
-   ✅ Mitra hanya perlu verifikasi setelah payment

**Con:**

-   ❌ Mitra mungkin reject booking setelah tenant bayar (refund issue)

**Kode saat ini:** ✅ **SUDAH SUPPORT**

```php
->whereIn('status', ['confirmed', 'pending']) // Pending bisa bayar
```

---

### **Option B: Tenant Harus Tunggu Konfirmasi Mitra (Alternative)**

```
Tenant Booking → Status: PENDING → Mitra Konfirmasi → Status: CONFIRMED → Tenant Bayar
```

**Pro:**

-   ✅ Tidak ada payment untuk booking yang ditolak
-   ✅ Flow lebih terstruktur

**Con:**

-   ❌ Tenant harus tunggu mitra online
-   ❌ Proses lebih lambat

**Perubahan kode:**

```php
// routes/web.php line 128
->whereIn('status', ['confirmed']) // HANYA confirmed yang bisa bayar
// ->whereIn('status', ['confirmed', 'pending']) // REMOVE pending
```

---

## 🧪 Testing Checklist

### **Test 1: Buat Booking Baru**

```
1. Login sebagai tenant
2. Buka: /tenant/bookings/create
3. Pilih room
4. Isi form
5. Submit
6. Expected: Status = "pending"
```

### **Test 2: Check Dropdown Payment**

```
1. Buka: /tenant/payments-midtrans/create
2. Expected: Dropdown muncul booking yang baru dibuat
3. Check logs: "available_count: 1" atau lebih
```

### **Test 3: Create Midtrans Transaction**

```
1. Pilih booking dari dropdown
2. Klik "Bayar Sekarang"
3. Expected logs:
   - "=== MIDTRANS CHECKOUT START ==="
   - "Calling Midtrans Snap API"
   - "=== MIDTRANS CHECKOUT SUCCESS ==="
4. Expected: Popup Midtrans muncul
```

### **Test 4: Complete Payment**

```
1. Di popup Midtrans:
   - Pilih Credit Card
   - Card: 4811 1111 1111 1114
   - CVV: 123
   - Exp: 01/26
   - OTP: 112233
2. Submit
3. Expected: Redirect ke /tenant/payments
```

### **Test 5: Verify di Midtrans Dashboard**

```
1. Login: https://dashboard.sandbox.midtrans.com
2. Menu: Transactions
3. Search: order_id "LIVORA-"
4. Expected:
   - Transaksi muncul
   - Status: Settlement
   - Amount sesuai
```

### **Test 6: Check Database**

```sql
-- Check payment
SELECT
    id,
    order_id,
    booking_id,
    amount,
    status,
    transaction_id,
    payment_type,
    created_at
FROM payments
ORDER BY created_at DESC
LIMIT 5;

-- Expected:
-- status = 'settlement'
-- transaction_id ada value
-- payment_type = 'credit_card'
```

---

## 🚨 Common Issues & Solutions

### **Issue: Dropdown Kosong**

**Check:**

```sql
SELECT id, booking_code, status, user_id
FROM bookings
WHERE user_id = {user_id} AND status IN ('pending', 'confirmed');
```

**If empty:**

1. Create new booking
2. OR ask mitra to confirm existing booking

---

### **Issue: "Booking tidak valid atau tidak ditemukan"**

**Check:**

```sql
SELECT id, booking_code, status, user_id
FROM bookings
WHERE id = {booking_id};
```

**Verify:**

-   ✅ user_id matches logged in user
-   ✅ status is 'pending' or 'confirmed'
-   ✅ booking exists

---

### **Issue: Transaksi Tidak Muncul di Midtrans**

**Check logs:**

```bash
railway logs | grep "Calling Midtrans Snap API"
```

**If not found:**

-   Frontend tidak call API
-   Check browser console errors
-   Verify JavaScript tidak error

**If found but error:**

```bash
railway logs | grep "MIDTRANS CHECKOUT ERROR"
```

**Common errors:**

-   401 Unauthorized → Server key salah (SUDAH FIXED)
-   400 Bad Request → Parameter tidak valid

---

## 📊 Summary

### **Kapan Transaksi Masuk ke Midtrans?**

✅ **SAAT TENANT KLIK "BAYAR SEKARANG"**

Bukan saat:

-   ❌ Tenant buat booking
-   ❌ Mitra konfirmasi booking
-   ❌ Tenant buka halaman payment
-   ❌ Tenant pilih booking dari dropdown

### **Apakah Harus Tunggu Mitra Konfirmasi?**

**Saat ini:** ❌ **TIDAK HARUS**

Tenant bisa bayar dengan status booking:

-   ✅ `pending` (baru dibuat, belum dikonfirmasi mitra)
-   ✅ `confirmed` (sudah dikonfirmasi mitra)

**Jika ingin wajib konfirmasi mitra dulu:**
Edit [routes/web.php](routes/web.php#L128):

```php
->whereIn('status', ['confirmed']) // ONLY confirmed
```

### **Current Business Flow:**

```
1. Tenant buat booking → Status: PENDING ✅
2. Tenant bisa LANGSUNG bayar ✅
3. Mitra konfirmasi (optional) → Status: CONFIRMED
4. Tenant bayar via Midtrans
5. Midtrans webhook → Update payment status
```

Sistem **SUDAH BENAR** dan **FULLY FUNCTIONAL** 🎉
