# Database Setup Guide

## Production Database

**Platform:** Railway MySQL  
**Connection:** Managed by Railway (see `.env` for credentials)  
**Status:** Live at https://livora-web-app-production.up.railway.app

## Local Development Database

**Database:** `livora`  
**User:** `root`  
**Password:** (kosong)

---

## Users Login

### Admin

-   **Email:** `admin@livora.com`
-   **Password:** `password`
-   **Role:** admin

### Mitra (Property Owner)

-   **Email:** `mitra@livora.com`
-   **Password:** `password`
-   **Role:** mitra

### Tenant (Penyewa)

-   **Email:** `tenant@livora.com`
-   **Password:** `password`
-   **Role:** tenant

**Note**: Role "owner" telah diganti menjadi "mitra" di seluruh sistem.

---

## Database Schema Updates

### Booking Table Changes

Kolom-kolom berikut telah diubah namanya:

| Old Column     | New Column        | Type    |
| -------------- | ----------------- | ------- |
| `start_date`   | `check_in_date`   | date    |
| `end_date`     | `check_out_date`  | date    |
| `duration`     | `duration_months` | integer |
| -              | `duration_days`   | integer |
| `total_price`  | `final_amount`    | decimal |
| `total_amount` | `final_amount`    | decimal |

### New Columns Added

-   `boarding_house_id`: Foreign key ke boarding house
-   `booking_code`: Unique booking code
-   `monthly_price`: Harga per bulan
-   `deposit_amount`: Jumlah deposit
-   `admin_fee`: Biaya admin
-   `discount_amount`: Diskon
-   `booking_type`: Type (daily/monthly)

---

## Setup Database

### Fresh Install

```bash
# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed --class=LightweightDataSeeder

# Recalculate booking amounts
php artisan bookings:recalculate-amounts
```

### Reset Database

```bash
# Reset database with fresh migrations and seeding
php artisan migrate:fresh --seed --class=LightweightDataSeeder

# Recalculate amounts after seeding
php artisan bookings:recalculate-amounts
```

### Seeder Options

1. **LightweightDataSeeder** - Data minimal untuk development (Recommended)
    - 10 users, 8 properties, 40 rooms, 25 bookings
2. **CompleteDatabaseSeeder** - Data dasar untuk testing
    - Users, properties, rooms, bookings, payments, tickets
3. **MassiveDataSeeder** - Data besar untuk load testing
    - 1000+ users, 200 properties, 800 bookings

---

## Artisan Commands

### Booking Management

```bash
# Recalculate all booking amounts (fix Rp 0 issue)
php artisan bookings:recalculate-amounts

# Update booking status (runs daily via scheduler)
php artisan bookings:update-status
```

### Database Maintenance

```bash
# Clear all caches
php artisan optimize:clear

# Optimize application
php artisan optimize

# Create storage symlink
php artisan storage:link
```

---

## Data yang Tersedia (LightweightDataSeeder)

-   **10 Users** (1 Admin, 3 Mitra, 6 Tenant)
-   **8 Boarding Houses** dengan berbagai lokasi
-   **40 Rooms** dengan fasilitas lengkap
-   **25 Bookings** dengan berbagai status
-   **Payments** untuk setiap booking
-   **15 Tickets** support
-   **10 Facilities** (WiFi, AC, Kamar Mandi, dll)

---

## Production Deployment (Railway)

```bash
# Run migrations on Railway
railway run php artisan migrate

# Seed production database
railway run php artisan db:seed --class=LightweightDataSeeder

# Recalculate amounts
railway run php artisan bookings:recalculate-amounts
```
