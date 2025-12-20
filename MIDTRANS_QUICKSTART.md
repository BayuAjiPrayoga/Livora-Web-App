# 🚀 Quick Start - Midtrans Integration

## Setup Cepat (5 Langkah)

### 1️⃣ Install SDK

```bash
composer require midtrans/midtrans-php
```

### 2️⃣ Konfigurasi .env

```env
MIDTRANS_SERVER_KEY=your-server-key-here
MIDTRANS_CLIENT_KEY=your-client-key-here
MIDTRANS_MERCHANT_ID=your-merchant-id-here
MIDTRANS_IS_PRODUCTION=false
```

### 3️⃣ Run Migration

```bash
php artisan migrate
```

### 4️⃣ Set Webhook URL di Midtrans Dashboard

```
https://your-app.railway.app/api/payment/notification
```

### 5️⃣ Test!

```
Akses: /tenant/payments-midtrans/create
```

---

## 🔐 Keamanan CNS

**Signature Verification Algorithm:**

```
SHA512(order_id + status_code + gross_amount + ServerKey)
```

**File**: `app/Http/Controllers/Api/MidtransNotificationController.php`

**Features:**

-   ✅ Double layer verification
-   ✅ Constant-time comparison
-   ✅ Complete audit logging
-   ✅ MITM attack prevention
-   ✅ Data tampering protection

---

## 📍 Endpoints

### User Endpoints

-   `GET /tenant/payments-midtrans/create` - Halaman checkout
-   `POST /tenant/payments/midtrans/checkout` - Create transaction
-   `GET /tenant/payments/finish` - Callback after payment

### Webhook Endpoint

-   `POST /api/payment/notification` - Midtrans notification handler

---

## 🧪 Testing (Sandbox)

**Test Card Number:** `4811 1111 1111 1114`
**Expiry:** `01/25`
**CVV:** `123`

---

## 📖 Dokumentasi Lengkap

Lihat [MIDTRANS_INTEGRATION.md](MIDTRANS_INTEGRATION.md) untuk dokumentasi detail.

---

## ⚠️ Production Checklist

-   [ ] Ganti ke Production Keys
-   [ ] Set `MIDTRANS_IS_PRODUCTION=true`
-   [ ] Update Notification URL di Dashboard
-   [ ] Test webhook connectivity
-   [ ] Monitor logs
