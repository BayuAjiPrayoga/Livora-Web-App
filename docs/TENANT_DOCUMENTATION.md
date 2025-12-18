# LIVORA - Tenant Documentation

Dokumentasi lengkap untuk fitur, fungsi, dan method yang digunakan pada bagian Tenant (Penyewa) di aplikasi Livora. Dokumen ini ditujukan sebagai acuan untuk pengembangan aplikasi mobile (Flutter).

---

## 📋 Daftar Isi

1. [Dashboard](#1-dashboard)
2. [Booking Management](#2-booking-management)
3. [Payment Management](#3-payment-management)
4. [Ticket Management](#4-ticket-management)
5. [Profile Management](#5-profile-management)
6. [Models & Data Structure](#6-models--data-structure)

---

## 1. Dashboard

**Route:** `/tenant/dashboard`  
**Controller:** `Tenant\DashboardController@index`

### Fitur Utama

-   Menampilkan ringkasan statistik tenant
-   Menampilkan aktivitas terbaru (booking, payment, ticket)
-   Menampilkan booking yang sedang aktif (checked-in)
-   Menampilkan booking yang akan datang (confirmed)
-   Menampilkan tagihan/pembayaran yang pending
-   Menampilkan tiket komplain yang masih open

### Data Statistik

API/Controller mengirimkan data `statistics` dengan format:

```json
{
    "total_bookings": "int (Total semua booking)",
    "active_bookings": "int (Status: checked_in)",
    "completed_bookings": "int (Status: checked_out)",
    "cancelled_bookings": "int (Status: cancelled)",
    "total_payments": "int (Total riwayat pembayaran)",
    "verified_payments": "int (Status: verified)",
    "pending_payments": "int (Status: pending)",
    "total_spent": "decimal (Total pengeluaran verified)",
    "monthly_spent": "decimal (Pengeluaran bulan ini)",
    "open_tickets": "int (Tiket status open)",
    "resolved_tickets": "int (Tiket status resolved)"
}
```

### Recent Activities

List gabungan dari Booking, Payment, dan Ticket terbaru, diurutkan berdasarkan waktu (descending).
Format item activity:

```json
{
    "type": "booking|payment|ticket",
    "icon": "calendar|credit-card|chat",
    "title": "string (Judul aktivitas)",
    "description": "string (Detail singkat)",
    "time": "datetime (Waktu kejadian)",
    "status": "string (Status terkini)",
    "link": "string (URL detail)"
}
```

---

## 2. Booking Management

**Controller:** `Tenant\BookingController`

### Status Booking

-   `pending`: Menunggu konfirmasi owner/mitra
-   `confirmed`: Disetujui, menunggu check-in
-   `active`: Sedang menginap (Checked-in)
-   `completed`: Selesai menginap (Checked-out)
-   `cancelled`: Dibatalkan

### Fitur & Method

#### A. List Bookings (`index`)

-   **Filter:** None (default all bookings user)
-   **Sort:** `created_at` DESC
-   **Relations:** `room.boardingHouse`, `payments`

#### B. Create Booking (`store`)

-   **Input:**
    -   `room_id`: ID Kamar
    -   `start_date`: Tanggal mulai (Format: YYYY-MM-DD)
    -   `duration`: Durasi sewa (bulan)
    -   `tenant_identity_number`: No KTP (16 digit)
    -   `ktp_image`: Foto KTP (File image)
    -   `notes`: Catatan tambahan (Optional)
-   **Validasi:**
    -   Room harus available pada range tanggal (`start_date` s/d `start_date + duration`)
    -   KTP wajib upload
-   **Logic:**
    -   Hitung `end_date` = `start_date` + `duration` (months)
    -   Hitung `total_price` = `duration` \* `room_price`
    -   Set status awal = `pending`

#### C. Cancel Booking (`cancel`)

-   **Syarat:** Status booking harus `pending` atau `confirmed`
-   **Input:** `cancellation_reason` (string)
-   **Logic:** Update status ke `cancelled`

#### D. Check Room Availability (`getRooms`)

-   **Endpoint:** `/tenant/bookings/rooms/{boardingHouseId}`
-   **Method:** GET
-   **Return:** List kamar yang `is_available = true` pada boarding house tersebut.

---

## 3. Payment Management

**Controller:** `Tenant\PaymentController`

### Status Payment

-   `pending`: Menunggu verifikasi
-   `verified`: Pembayaran diterima
-   `rejected`: Pembayaran ditolak

### Fitur & Method

#### A. List Payments (`index`)

-   **Filter:**
    -   `status`: all, pending, verified, rejected
    -   `date_from` & `date_to`: Range tanggal
-   **Sort:** `created_at` DESC

#### B. Create Payment (`store`)

-   **Syarat:** Hanya bisa bayar untuk booking status `confirmed` atau `pending` yang belum lunas.
-   **Input:**
    -   `booking_id`: ID Booking
    -   `amount`: Jumlah bayar
    -   `proof_image`: Bukti transfer (Image)
-   **Validasi:**
    -   Tidak boleh ada pembayaran `pending` untuk booking yang sama (harus selesai satu per satu)
    -   User harus pemilik booking

#### C. Download Receipt (`downloadReceipt`)

-   **Syarat:** Status payment harus `verified`
-   **Output:** File PDF kwitansi pembayaran

---

## 4. Ticket Management

**Controller:** `Tenant\TicketController`

### Status Ticket

-   `open`: Baru dibuat
-   `in_progress`: Sedang ditangani
-   `resolved`: Selesai
-   `closed`: Ditutup

### Priority Levels

-   `low`, `medium`, `high`, `urgent`

### Fitur & Method

#### A. List Tickets (`index`)

-   **Filter:** `status`, `search` (subject/message)
-   **Sort:** `created_at` DESC

#### B. Create Ticket (`store`)

-   **Syarat:** User harus punya booking (`pending` atau `confirmed`) di kamar terkait.
-   **Input:**
    -   `room_id`: ID Kamar
    -   `subject`: Judul keluhan
    -   `message`: Detail keluhan
    -   `priority`: Level prioritas

#### C. Update Ticket (`update`)

-   **Syarat:** Status ticket masih `open`
-   **Input:** Sama dengan create

---

## 5. Profile Management

**Controller:** `Tenant\ProfileController`

### Fitur

-   **Update Profile:** Nama, Email, No HP, Tgl Lahir, Alamat, Foto Profil
-   **Change Password:** Validasi password lama
-   **Notification Settings:** Toggle Email/SMS/Marketing notif
-   **Deactivate Account:** Nonaktifkan akun sementara
-   **Delete Account:** Hapus akun permanen

---

## 6. Models & Data Structure

### User (Tenant)

-   `role`: 'tenant'
-   `is_active`: boolean

### Booking

-   `user_id`: FK to Users
-   `room_id`: FK to Rooms
-   `start_date`: Date
-   `end_date`: Date
-   `duration`: Integer (bulan/hari/tahun)
-   `total_price`: Decimal
-   `status`: Enum (pending, confirmed, active, completed, cancelled)
-   `tenant_identity_number`: String
-   `ktp_image`: Path string

### Payment

-   `booking_id`: FK to Bookings
-   `amount`: Decimal
-   `proof_image`: Path string
-   `status`: Enum (pending, verified, rejected)
-   `verified_at`: Datetime

### Ticket

-   `user_id`: FK to Users
-   `room_id`: FK to Rooms
-   `subject`: String
-   `message`: Text
-   `priority`: Enum (low, medium, high, urgent)
-   `status`: Enum (open, in_progress, resolved, closed)
-   `response`: Text (tanggapan owner)
