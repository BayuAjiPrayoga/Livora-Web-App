-- =====================================================
-- INSERT MISSING PROPERTIES (OPTIONAL)
-- Hanya jalankan jika property ID 4, 5 tidak ada dan Anda ingin menambahkannya
-- =====================================================

-- CEK DULU user_id yang ada
SELECT id, name, email, role FROM users WHERE role = 'owner';

-- Jika sudah ada user dengan role owner, Anda bisa insert properties untuk mereka
-- Sesuaikan user_id dengan ID owner yang ada di database Anda!

-- HATI-HATI: Ganti user_id sesuai dengan owner yang ADA di database!
-- Contoh: Jika owner ada dengan ID 2 dan 3, gunakan ID tersebut

-- Property ID 4 (untuk user_id 3 atau sesuaikan)
INSERT INTO `boarding_houses` (`id`, `user_id`, `name`, `slug`, `address`, `city`, `description`, `latitude`, `longitude`, `images`, `is_active`, `price_range_start`, `price_range_end`, `is_verified`, `created_at`, `updated_at`) VALUES
(4, 3, 'Kost Kenanga Yogyakarta', 'kost-kenanga-yogyakarta', 'Jl. Kaliurang Km 5, Yogyakarta', 'Yogyakarta', 'Kost dekat UGM dan kampus lain. Suasana tenang dan nyaman.', -7.780000, 110.370000, '[]', 1, 700000.00, 1200000.00, 1, NOW(), NOW());

-- Property ID 5 (untuk user_id 3 atau sesuaikan)
INSERT INTO `boarding_houses` (`id`, `user_id`, `name`, `slug`, `address`, `city`, `description`, `latitude`, `longitude`, `images`, `is_active`, `price_range_start`, `price_range_end`, `is_verified`, `created_at`, `updated_at`) VALUES
(5, 3, 'Kost Seruni Semarang', 'kost-seruni-semarang', 'Jl. Pandanaran No. 100, Semarang', 'Semarang', 'Kost bersih dan rapi. Fasilitas lengkap dengan harga terjangkau.', -6.990000, 110.420000, '[]', 1, 750000.00, 1300000.00, 0, NOW(), NOW());

-- Verifikasi
SELECT id, name, user_id, city FROM boarding_houses ORDER BY id;

-- =====================================================
-- CATATAN PENTING:
-- =====================================================
-- 1. Pastikan user_id yang Anda gunakan ADA di tabel users dengan role = 'owner'
-- 2. Jika auto_increment sudah jalan, hapus field `id` dari INSERT
-- 3. Setelah properties ditambahkan, baru jalankan insert_rooms.sql
-- =====================================================
