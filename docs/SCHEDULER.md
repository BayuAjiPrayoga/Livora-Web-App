# Laravel Scheduler - Booking Status Automation

**Last Updated**: December 21, 2025  
**Laravel Version**: 12  
**Production**: Railway Deployment  
**Status**: ✅ Active & Configured

## Overview

Sistem ini menggunakan Laravel Scheduler untuk **otomatis mengubah status booking dari `confirmed` menjadi `active`** saat tanggal check-in tiba.

## Cara Kerja

-   **Command**: `booking:update-status`
-   **Schedule**: Setiap hari jam **00:01 WIB** (waktu Jakarta)
-   **Logic**: Mengecek semua booking dengan status `confirmed` yang tanggal check-in (`start_date`) adalah hari ini atau sudah lewat
-   **Action**: Mengubah status dari `confirmed` → `active` secara otomatis
-   **Logging**: Semua perubahan status dicatat di `storage/logs/laravel.log`

## Setup di Production (Wajib!)

### 1. Tambahkan Cron Job di Server

Untuk menjalankan scheduler, tambahkan **1 baris cron job** di server:

```bash
* * * * * cd /path/to/livora && php artisan schedule:run >> /dev/null 2>&1
```

**Cara setting cron:**

#### Linux/Ubuntu (via crontab):

```bash
# Edit crontab
crontab -e

# Tambahkan baris ini (ganti /path/to/livora dengan path project):
* * * * * cd /var/www/livora && php artisan schedule:run >> /dev/null 2>&1
```

#### cPanel (via Cron Jobs):

1. Buka cPanel → **Cron Jobs**
2. Pilih **Common Settings**: "Once Per Minute (\* \* \* \* \*)"
3. Masukkan command:
    ```bash
    cd /home/username/public_html && php artisan schedule:run >> /dev/null 2>&1
    ```
4. Klik **Add New Cron Job**

#### Plesk:

1. Buka **Tools & Settings** → **Scheduled Tasks**
2. Klik **Add Task**
3. Run: "Custom script"
4. Schedule: Every minute (\* \* \* \* \*)
5. Command:
    ```bash
    cd /var/www/vhosts/domain.com && php artisan schedule:run
    ```

### 2. Verifikasi Cron Berjalan

```bash
# Cek log scheduler
tail -f storage/logs/laravel.log | grep "Booking"

# Manual test command (tidak perlu cron)
php artisan booking:update-status
```

## Development/Testing

### Manual Run Command

```bash
# Jalankan manual untuk testing
php artisan booking:update-status
```

Output contoh:

```
Booking #BK-000006 status changed to active.
Total bookings updated: 1
```

### Lihat Schedule List

```bash
# Lihat semua scheduled tasks
php artisan schedule:list
```

### Test Scheduler Tanpa Cron (Development Only)

```bash
# Jalankan scheduler setiap 1 menit (untuk development/testing)
php artisan schedule:work
```

## Monitoring

### Cek Log

Semua aktivitas scheduler tercatat di log:

```bash
tail -100 storage/logs/laravel.log | grep "Booking"
```

Format log:

```
[2025-11-24 00:01:00] local.INFO: Booking #BK-000006 automatically activated. {"booking_id":6,"start_date":"2025-11-24","tenant":"Sandi","room":"kamar premium B1"}
[2025-11-24 00:01:00] local.INFO: Booking status update completed. 1 bookings activated.
```

### Database Check

```sql
-- Cek booking yang akan diaktifkan hari ini
SELECT booking_number, status, start_date, created_at
FROM bookings
WHERE status = 'confirmed'
AND start_date <= CURDATE();

-- Cek booking yang sudah active
SELECT booking_number, status, start_date, created_at
FROM bookings
WHERE status = 'active';
```

## Flow Status Booking

```
pending → confirmed → active → completed
   ↓          ↓          ↓
cancelled  cancelled  cancelled
```

### Status Transitions:

1. **pending → confirmed**: Manual oleh **Mitra** (tombol "Konfirmasi")
2. **confirmed → active**: **Otomatis** oleh scheduler saat `start_date` tiba
3. **active → completed**: Manual oleh **Mitra** saat tenant check-out (fitur masa depan)
4. **any → cancelled**: Manual oleh **Mitra** atau **Admin** (tombol "Batalkan")

## Troubleshooting

### Scheduler Tidak Jalan

**Problem**: Booking tidak otomatis berubah ke active

**Solusi**:

1. Pastikan cron job sudah ditambahkan:
    ```bash
    crontab -l
    ```
2. Pastikan path project benar
3. Cek permission folder:
    ```bash
    chmod -R 775 storage bootstrap/cache
    ```
4. Test manual command:
    ```bash
    php artisan booking:update-status
    ```

### Error: Command Not Found

**Problem**: `bash: php: command not found`

**Solusi**: Gunakan full path PHP

```bash
# Cari path PHP
which php

# Update cron dengan full path
* * * * * cd /path/to/livora && /usr/bin/php artisan schedule:run
```

### Timezone Berbeda

**Problem**: Scheduler jalan di waktu yang salah

**Solusi**: Cek timezone di `config/app.php`

```php
'timezone' => 'Asia/Jakarta', // Pastikan ini
```

## Notes untuk Developer

-   Command ada di: `app/Console/Commands/UpdateBookingStatus.php`
-   Schedule config di: `routes/console.php`
-   Schedule jalan setiap hari jam 00:01 WIB
-   Tidak perlu manual trigger, cron job akan menjalankannya otomatis
-   Log level: INFO (bisa dilihat di production)

## Future Enhancements

Fitur yang bisa ditambahkan:

1. **Auto Complete**: Status `active` → `completed` saat `end_date` lewat
2. **Reminder Notification**: Kirim email/notif H-1 sebelum check-in
3. **Late Check-in Alert**: Notif ke mitra jika tenant belum check-in H+1
4. **Payment Reminder**: Notif otomatis untuk pembayaran yang tertunda
