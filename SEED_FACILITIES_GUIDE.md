# CARA MENJALANKAN SEEDER FACILITIES

## Masalah

Facilities tidak muncul di halaman create room karena data facilities belum ada di database production (Anymhost).

## Solusi

### Option 1: Via SSH (Recommended)

Jika punya akses SSH ke Anymhost:

```bash
cd /path/to/your/project
php artisan db:seed --class=Database\\Seeders\\FacilitySeeder --force
```

### Option 2: Via cPanel Terminal

1. Login ke cPanel Anymhost
2. Buka "Terminal"
3. Jalankan:

```bash
cd public_html  # atau folder project Anda
php artisan db:seed --class=FacilitySeeder --force
```

### Option 3: Via Script (Jika tidak ada SSH)

1. Upload file `seed_facilities.php` ke root project di Anymhost
2. Akses via browser: `https://arkanta.my.id/seed_facilities.php`
3. Script akan otomatis jalankan seeder
4. **PENTING**: Hapus file tersebut setelah selesai untuk keamanan!

### Option 4: Manual Insert via phpMyAdmin

Jika semua cara di atas tidak bisa, jalankan SQL ini di phpMyAdmin:

```sql
INSERT INTO `facilities` (`name`, `icon`, `description`, `created_at`, `updated_at`) VALUES
('WiFi', '📶', 'Koneksi internet WiFi gratis', NOW(), NOW()),
('AC', '❄️', 'Air Conditioner / Pendingin ruangan', NOW(), NOW()),
('Kasur', '🛏️', 'Tempat tidur dengan kasur', NOW(), NOW()),
('Lemari', '🚪', 'Lemari pakaian', NOW(), NOW()),
('Meja Belajar', '📚', 'Meja dan kursi untuk belajar/bekerja', NOW(), NOW()),
('Kamar Mandi Dalam', '🚿', 'Kamar mandi pribadi di dalam kamar', NOW(), NOW()),
('Jendela', '🪟', 'Jendela untuk ventilasi dan cahaya alami', NOW(), NOW()),
('TV', '📺', 'Televisi', NOW(), NOW()),
('Kulkas', '🧊', 'Lemari es / kulkas', NOW(), NOW()),
('Kipas Angin', '💨', 'Kipas angin', NOW(), NOW()),
('Water Heater', '♨️', 'Pemanas air untuk mandi', NOW(), NOW()),
('Balkon', '🏞️', 'Balkon atau teras pribadi', NOW(), NOW());
```

## Verifikasi

Setelah seeder berhasil, cek kembali:

1. Buka https://arkanta.my.id/mitra/properties/3/rooms/create
2. Facilities seharusnya sudah muncul dan bisa dipilih
3. Jika masih belum muncul, clear cache: `php artisan cache:clear`

## Troubleshooting

- Jika error "Class not found", pastikan autoload sudah di-update: `composer dump-autoload`
- Jika error permission, pastikan user MySQL punya akses INSERT ke tabel facilities
- Jika masih gagal, gunakan Option 4 (manual SQL insert)
