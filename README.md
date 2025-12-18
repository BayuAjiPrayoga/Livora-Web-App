# LIVORA - Live Better, Stay Better

> Platform management kost/boarding house berbasis web dengan sistem multi-tenant (Tenant, Owner/Mitra, Admin).

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql)
![Tailwind](https://img.shields.io/badge/Tailwind-CSS-38B2AC?logo=tailwind-css)

---

## 📋 Table of Contents

1. [Tentang LIVORA](#tentang-livora)
2. [Fitur Utama](#fitur-utama)
3. [Teknologi](#teknologi)
4. [Instalasi](#instalasi)
5. [Konfigurasi](#konfigurasi)
6. [Database Setup](#database-setup)
7. [Menjalankan Aplikasi](#menjalankan-aplikasi)
8. [User Roles](#user-roles)
9. [Dokumentasi](#dokumentasi)
10. [Troubleshooting](#troubleshooting)

---

## 🏠 Tentang LIVORA

**LIVORA (Live Better, Stay Better)** adalah platform management kost/boarding house berbasis web yang dibangun dengan Laravel 11. Aplikasi ini dirancang dengan arsitektur multi-tenant yang mendukung 3 role utama: **Tenant** (pencari kost), **Owner/Mitra** (pemilik kost), dan **Admin** (administrator sistem).

Platform ini menyediakan solusi lengkap untuk pengelolaan kost mulai dari pencarian properti, booking, pembayaran, hingga manajemen kompleks seperti laporan keuangan dan tiket layanan pelanggan.

### 🎯 Tujuan Proyek

-   Memudahkan pencari kost menemukan dan memesan properti
-   Memberikan tools manajemen yang efisien untuk pemilik kost
-   Menyediakan sistem kontrol dan monitoring untuk administrator
-   Mengotomasi proses booking, pembayaran, dan komunikasi

---

## ✨ Fitur Utama

### 🌐 Public (Guest)

-   **Landing Page**: Halaman beranda dengan statistik dan properti unggulan
-   **Browse Properties**: Pencarian dan filter properti (kota, harga, kapasitas, fasilitas)
-   **Property Detail**: Informasi lengkap properti dengan galeri foto dan kamar tersedia
-   **Pagination & Sorting**: Sorting berdasarkan harga, rating, kapasitas
-   **About & Contact**: Informasi platform dan formulir kontak

### 👤 Tenant (Pencari Kost)

-   **Dashboard**: Statistik booking, pembayaran, dan aktivitas terkini
-   **Booking Management**: Buat, lihat, dan batalkan booking
-   **Payment Management**: Upload bukti pembayaran, download receipt
-   **Ticket System**: Buat dan kelola tiket keluhan/permintaan
-   **Profile Management**: Update profil dan informasi akun
-   **Notification System**: Notifikasi real-time untuk booking, pembayaran, dan tiket

### 🏢 Owner/Mitra (Pemilik Kost)

-   **Dashboard**: Statistik revenue, okupansi, dan performa properti
-   **Property Management**: CRUD properti dengan upload gambar dan verifikasi status
-   **Room Management**: CRUD kamar dengan fasilitas, harga, dan ketersediaan
-   **Booking Management**: Kelola booking (konfirmasi, check-in, check-out, cancel)
-   **Payment Management**: Verifikasi pembayaran, reject, bulk action, download receipt
-   **Ticket Management**: Kelola tiket dari tenant dengan status dan prioritas
-   **Report System**: Laporan revenue dan okupansi dengan grafik dan filter

### ⚙️ Admin (Administrator)

-   **Dashboard**: Monitoring sistem secara keseluruhan
-   **User Management**: CRUD users, aktivasi/deaktivasi, bulk operations
-   **Property Management**: CRUD dan verifikasi properti, suspend, bulk operations
-   **Booking Management**: Monitor dan kelola semua booking sistem
-   **Payment Management**: Verifikasi pembayaran, bulk approval/rejection
-   **Ticket Management**: Assign tiket, kelola prioritas dan status
-   **Notification System**: Broadcast notifikasi ke semua user atau role tertentu
-   **Report System**: Laporan lengkap (revenue, ocupancy, performance, users) dengan export
-   **Settings**: Konfigurasi general, email, maintenance mode, clear cache

---

## 🛠️ Teknologi

### Backend

-   **Laravel 11**: Framework PHP
-   **PHP 8.2+**: Language
-   **MySQL 8.0+**: Database
-   **Eloquent ORM**: Database management

### Frontend

-   **Blade**: Templating engine
-   **Tailwind CSS**: Styling framework
-   **Alpine.js**: JavaScript framework (minimal)
-   **Vite**: Build tool

### Tools & Libraries

-   **Laravel Sanctum**: API authentication
-   **Laravel Mix/Vite**: Asset compilation
-   **Intervention Image**: Image processing
-   **Carbon**: Date/time manipulation

---

## 📦 Instalasi

### Requirements

-   PHP >= 8.3
-   Composer
-   Node.js >= 18 & NPM
-   MySQL >= 8.0
-   Git

### Steps

```bash
# 1. Clone repository
git clone https://github.com/BayuAjiPrayoga/Livora-Web-App.git
cd Livora

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Configure database di .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=livora
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Run migrations
php artisan migrate

# 7. Seed database (recommended)
php artisan db:seed --class=LightweightDataSeeder

# 8. Create storage link
php artisan storage:link

# 9. Build assets
npm run build

# 10. Recalculate booking amounts (if needed)
php artisan bookings:recalculate-amounts
```

---

## ⚙️ Konfigurasi

### Environment Variables

```env
APP_NAME=LIVORA
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=livora
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@livora.com"
MAIL_FROM_NAME="${APP_NAME}"

FILESYSTEM_DISK=public
```

### Storage Configuration

```bash
# Create symbolic link for storage
php artisan storage:link

# Storage folders:
# - storage/app/public/properties (property images)
# - storage/app/public/rooms (room images)
# - storage/app/public/payments (payment proofs)
# - storage/app/public/profiles (user avatars)
```

---

## 💾 Database Setup

### Migration Files

1. `create_users_table`: User accounts dengan role (tenant, owner, admin)
2. `create_boarding_houses_table`: Properti kost dengan owner
3. `create_rooms_table`: Kamar dengan fasilitas dan harga
4. `create_bookings_table`: Booking dengan status dan durasi
5. `create_payments_table`: Pembayaran dengan verifikasi
6. `create_tickets_table`: Tiket support dengan prioritas
7. `create_notifications_table`: Notifikasi sistem
8. `create_facilities_table`: Fasilitas (many-to-many dengan rooms)

### Seeding Database

```bash
# Seed default users and sample data
php artisan db:seed

# Seed specific seeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=BoardingHouseSeeder
```

### Fresh Migration (Reset Database)

```bash
# WARNING: This will delete all data
php artisan migrate:fresh --seed
```

---

## 🚀 Menjalankan Aplikasi

### Development

```bash
# Terminal 1: Run Laravel server
php artisan serve

# Terminal 2: Run Vite dev server
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

### Production

```bash
# Build assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Setup web server (Apache/Nginx)
# Point document root ke /public
```

### Default Users (After Seeding)

| Email             | Password | Role        |
| ----------------- | -------- | ----------- |
| admin@livora.com  | password | Admin       |
| mitra@livora.com  | password | Owner/Mitra |
| tenant@livora.com | password | Tenant      |

**Note**: Role "owner" telah diganti menjadi "mitra" di seluruh sistem.

---

## 👥 User Roles

| Role       | Access       | Permissions                                                                                            |
| ---------- | ------------ | ------------------------------------------------------------------------------------------------------ |
| **Guest**  | Public pages | Browse, search, view properties                                                                        |
| **Tenant** | `/tenant/*`  | Booking, payment, tickets, profile                                                                     |
| **Mitra**  | `/mitra/*`   | Property management, room management, booking approval, payment verification, ticket handling, reports |
| **Admin**  | `/admin/*`   | Full system control, user management, property verification, system settings, global reports           |

### Role-Based Routing

```php
// Public routes (no auth)
Route::get('/', [HomeController::class, 'index']);
Route::get('/browse', [HomeController::class, 'browse']);

// Tenant routes (auth + role:tenant)
Route::middleware(['auth', 'role:tenant'])->prefix('tenant')->group(...);

// Mitra routes (auth + role:mitra)
Route::middleware(['auth', 'role:mitra'])->prefix('mitra')->group(...);

// Admin routes (auth + role:admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(...);
```

---

## 📚 Dokumentasi

Dokumentasi lengkap proyek tersedia di folder `/docs`:

1. **[docs/MODELS.md](docs/MODELS.md)** - Database models, fields, relationships, methods
2. **[docs/CONTROLLERS.md](docs/CONTROLLERS.md)** - Controllers, methods, variables, logic flow
3. **[docs/ROUTES.md](docs/ROUTES.md)** - Routing structure, architecture, user flows
4. **[docs/VARIABLES.md](docs/VARIABLES.md)** - Variable reference per file/controller
5. **[docs/ENVIRONMENT.md](docs/ENVIRONMENT.md)** - Environment setup, artisan commands, deployment

### Quick Reference

-   **Models**: User, BoardingHouse, Room, Booking, Payment, Ticket, Notification, Facility
-   **Main Controllers**: HomeController, DashboardController (Tenant/Mitra/Admin), PropertyController, RoomController, BookingController, PaymentController, TicketController
-   **Key Routes**: `/`, `/browse`, `/tenant/dashboard`, `/mitra/dashboard`, `/admin/dashboard`

---

## 🔧 Troubleshooting

### Common Issues

**1. "No application encryption key"**

```bash
php artisan key:generate
```

**2. Storage symlink not working**

```bash
php artisan storage:link
```

**3. Permission errors**

```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows: Run as Administrator
```

**4. Migration errors**

```bash
# Reset database
php artisan migrate:fresh

# With seeding
php artisan migrate:fresh --seed --class=LightweightDataSeeder
```

**5. Booking amounts showing Rp 0**

```bash
# Recalculate all booking final amounts
php artisan bookings:recalculate-amounts
```

**6. Vite not building**

```bash
# Clear cache
npm cache clean --force

# Reinstall
rm -rf node_modules package-lock.json
npm install
```

**7. Images not showing**

-   Pastikan `storage:link` sudah dijalankan
-   Cek path di `.env`: `FILESYSTEM_DISK=public`
-   Cek permission folder `storage/app/public`

**8. API authentication errors**

-   Pastikan menggunakan `Bearer token` di header
-   Token format: `Authorization: Bearer {token}`
-   Gunakan `$request->user()` bukan `auth()->user()` di API controllers

### Database Schema Changes (Important!)

**Booking table columns have been renamed:**

-   `start_date` → `check_in_date`
-   `end_date` → `check_out_date`
-   `duration` → `duration_months` / `duration_days`
-   `total_price` / `total_amount` → `final_amount`

**User roles updated:**

-   `owner` → `mitra`

Jika migrate dari versi lama, jalankan migration dan recalculate:

```bash
php artisan migrate
php artisan bookings:recalculate-amounts
```

---

## 📞 Support & Contact

-   **Production**: https://livora-web-app-production.up.railway.app
-   **Repository**: https://github.com/BayuAjiPrayoga/Livora-Web-App
-   **Documentation**: `/docs`
-   **Issues**: GitHub Issues

---

## 📄 License

This project is licensed under the MIT License.

---

## 🙏 Acknowledgments

-   Laravel Framework by Taylor Otwell
-   Tailwind CSS by Tailwind Labs
-   Icons by Heroicons
-   Deployed on Railway.app

---

**Last Updated**: December 19, 2025

**LIVORA** - Live Better, Stay Better 🏠✨
