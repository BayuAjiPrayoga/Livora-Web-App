# 📋 Panduan Sistem Pembayaran Manual LIVORA

## ✅ SUDAH AKTIF - Simple & Works!

Sistem pembayaran manual sudah **100% AKTIF**. Tidak lagi pakai Midtrans yang ribet dan sering error.

---

## 🔄 Alur Pembayaran

### 1️⃣ **TENANT (Pengguna Kos)**

**Langkah Upload Bukti Pembayaran:**

1. **Login** sebagai tenant
2. Klik menu **"Pembayaran"** di dashboard
3. Klik tombol **"Buat Pembayaran Baru"**
4. **Pilih booking** yang mau dibayar dari dropdown
5. **Jumlah pembayaran** otomatis terisi sesuai booking
6. **Upload foto/screenshot** bukti transfer:
   - Format: JPG, JPEG, PNG
   - Ukuran maksimal: **2MB**
   - Pastikan bukti transfer jelas dan terbaca
7. Klik **"Submit Pembayaran"**
8. Status pembayaran: **"Pending"** (menunggu verifikasi mitra)

**Dimana Upload Bukti?**
```
URL: https://arkanta.my.id/tenant/payments/create
Route Name: tenant.payments.create
```

**Syarat Upload Bukti:**
- ✅ Booking status = `confirmed` ATAU `pending`
- ✅ Booking belum punya pembayaran yang `verified`
- ✅ File bukti berformat gambar (JPG/PNG)
- ✅ Ukuran file max 2MB

---

### 2️⃣ **MITRA (Pemilik Kos)**

**Langkah Verifikasi Pembayaran:**

1. **Login** sebagai mitra
2. Klik menu **"Verifikasi Pembayaran"**
3. Lihat daftar pembayaran dengan status **"Pending"**
4. Klik **"Lihat Detail"** untuk melihat bukti transfer
5. Periksa bukti transfer:
   - ✅ Nominal sesuai?
   - ✅ Transfer ke rekening yang benar?
   - ✅ Waktu transfer sesuai booking?
6. Pilih aksi:
   - **Verifikasi** ✅ → Status jadi `verified`, booking otomatis `paid`
   - **Tolak** ❌ → Status jadi `rejected`, tenant harus upload ulang

**Dimana Verifikasi?**
```
URL: https://arkanta.my.id/mitra/payments
Route Name: mitra.payments.index
```

---

## 📁 File yang Dimodifikasi

### 1. `routes/web.php`
```php
// Payment Routes - SIMPLE MANUAL PAYMENT
Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create'); // NEW!
Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store'); // NEW!
Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
```

**Perubahan:**
- ✅ Aktifkan route `create` dan `store`
- ❌ Hapus semua route Midtrans yang ribet

---

### 2. `app/Http/Controllers/Tenant/PaymentController.php`

**Method Aktif:**
- ✅ `create()` - Show form upload bukti transfer
- ✅ `store()` - Proses upload dan simpan ke database
- ✅ `index()` - Lihat history pembayaran
- ✅ `show()` - Detail pembayaran

**Code Highlights:**

```php
public function create()
{
    // Get bookings yang belum dibayar (status: confirmed/pending)
    $availableBookings = Booking::with(['room.boardingHouse'])
        ->where('user_id', Auth::id())
        ->whereIn('status', ['confirmed', 'pending'])
        ->whereDoesntHave('payments', function ($query) {
            $query->where('status', 'verified');
        })
        ->get();

    return view('tenant.payments.create', compact('availableBookings'));
}
```

```php
public function store(Request $request)
{
    // Validasi
    $request->validate([
        'booking_id' => 'required|exists:bookings,id',
        'amount' => 'required|numeric|min:1',
        'proof_image' => 'required|image|mimes:jpeg,jpg,png|max:2048'
    ]);

    // Upload file bukti
    $proofPath = $request->file('proof_image')->store('payment-proofs', 'public');

    // Simpan payment
    Payment::create([
        'booking_id' => $booking->id,
        'amount' => $request->amount,
        'proof_image' => $proofPath,
        'status' => 'pending'
    ]);

    return redirect()->route('tenant.payments.index')
        ->with('success', 'Pembayaran berhasil disubmit! Menunggu verifikasi dari mitra.');
}
```

---

## 💾 Database Schema

### Table: `payments`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `booking_id` | bigint | Foreign key ke `bookings` |
| `amount` | decimal(12,2) | Jumlah pembayaran |
| `proof_image` | varchar | Path file bukti transfer |
| `status` | varchar | **pending** / **verified** / **rejected** |
| `snap_token` | varchar | NULL (tidak dipakai) |
| `transaction_id` | varchar | NULL (tidak dipakai) |
| `created_at` | timestamp | Waktu submit |
| `updated_at` | timestamp | Waktu update |

**Status Flow:**
```
pending → verified ✅ (Mitra approve)
pending → rejected ❌ (Mitra tolak)
```

---

## 🔧 Troubleshooting

### ❌ Error: "Tidak ada booking aktif yang memerlukan pembayaran"

**Penyebab:**
- Semua booking sudah punya payment `verified`
- Booking status bukan `confirmed` atau `pending`

**Solusi:**
1. Cek status booking di database:
   ```sql
   SELECT id, booking_code, status FROM bookings WHERE user_id = <user_id>;
   ```
2. Pastikan booking status = `confirmed` atau `pending`
3. Pastikan belum ada payment dengan status `verified`

---

### ❌ Error: "Booking ini sudah memiliki pembayaran yang telah diverifikasi"

**Penyebab:**
- Booking sudah dibayar sebelumnya

**Solusi:**
- Pilih booking lain yang belum dibayar

---

### ❌ File Upload Gagal

**Penyebab:**
- File lebih dari 2MB
- Format bukan JPG/PNG
- Folder `storage/app/public` tidak bisa diakses

**Solusi:**
1. Kompres gambar dulu sebelum upload
2. Gunakan format JPG atau PNG
3. Pastikan symbolic link storage sudah dibuat:
   ```bash
   php artisan storage:link
   ```

---

## 📊 Status Pembayaran

| Status | Deskripsi | Aksi Tenant | Aksi Mitra |
|--------|-----------|-------------|-----------|
| **pending** 🟡 | Menunggu verifikasi | Tunggu | Verifikasi/Tolak |
| **verified** ✅ | Pembayaran diterima | Selesai | - |
| **rejected** ❌ | Pembayaran ditolak | Upload ulang | - |

---

## 🎯 Testing Checklist

### ✅ Test Tenant Flow:
- [ ] Login sebagai tenant
- [ ] Akses `/tenant/payments/create`
- [ ] Pilih booking dari dropdown
- [ ] Upload file JPG/PNG < 2MB
- [ ] Submit form
- [ ] Cek flash message: "Pembayaran berhasil disubmit!"
- [ ] Cek database: `SELECT * FROM payments WHERE status='pending';`
- [ ] Cek file: `storage/app/public/payment-proofs/<filename>`

### ✅ Test Mitra Flow:
- [ ] Login sebagai mitra
- [ ] Akses `/mitra/payments`
- [ ] Lihat daftar pending payments
- [ ] Klik detail payment
- [ ] Lihat bukti transfer
- [ ] Klik "Verifikasi"
- [ ] Cek database: `status='verified'`
- [ ] Cek booking: `status='paid'`

---

## 📸 Lokasi File Bukti Transfer

**Storage Path:**
```
storage/app/public/payment-proofs/
```

**Public URL:**
```
https://arkanta.my.id/storage/payment-proofs/<filename>
```

**Symbolic Link (Harus Dibuat):**
```bash
php artisan storage:link
```

Ini akan create symlink:
```
public/storage → storage/app/public
```

---

## 🚀 Deployment Notes

**Railway Auto-Deploy:**
- Setiap push ke `main` branch → otomatis deploy
- Build time: ~2-3 menit
- Check logs: Railway dashboard

**Post-Deploy Checklist:**
- ✅ Pastikan `storage/app/public` folder ada
- ✅ Pastikan symbolic link `public/storage` aktif
- ✅ Pastikan folder permissions benar (writable)
- ✅ Test upload file bukti transfer

---

## 📞 Support

**Jika Ada Error:**
1. Cek Laravel logs: `storage/logs/laravel.log`
2. Cek Railway logs di dashboard
3. Test di local dulu sebelum push ke production

**Database Issue:**
```bash
php artisan migrate:status
php artisan migrate:fresh --seed  # WARNING: Hapus semua data!
```

---

## 🎉 Keuntungan Simple Payment

✅ **Tidak Perlu Midtrans** (no more 401 errors!)  
✅ **Tenant Upload Manual** (simple & straightforward)  
✅ **Mitra Verifikasi** (full control)  
✅ **No External Dependencies** (100% in-house)  
✅ **Mudah Debug** (semua ada di database)  
✅ **Works Immediately** (no setup headache)

---

**Made with ❤️ by GitHub Copilot**  
**Tanggal:** 2024-01-XX  
**Version:** Simple & Working v1.0
