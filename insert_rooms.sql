-- =====================================================
-- INSERT ROOMS DATA (SAFE VERSION - Only for existing properties)
-- Jalankan di phpMyAdmin setelah facilities di-insert
-- =====================================================

-- STEP 1: CEK PROPERTY YANG ADA (WAJIB JALANKAN INI DULU!)
SELECT id, name, user_id FROM boarding_houses ORDER BY id;
-- Catat property ID mana saja yang ada!

-- STEP 2: CEK APAKAH SUDAH ADA ROOMS
SELECT COUNT(*) as total_rooms FROM rooms;

-- =====================================================
-- STEP 3: INSERT ROOMS (Pilih yang sesuai property Anda)
-- Hanya jalankan INSERT untuk property ID yang ADA!
-- =====================================================

-- OPTION A: Jika punya Property ID 1, 2, 3 (PALING AMAN)
-- Jalankan ini jika hasil SELECT di atas menunjukkan property 1, 2, 3 ada:

INSERT INTO `rooms` (`boarding_house_id`, `name`, `description`, `price`, `capacity`, `size`, `is_available`, `created_at`, `updated_at`) VALUES
-- Property ID 1
(1, 'Kamar A1', 'Kamar standar dengan AC dan WiFi', 900000, 1, 12, 1, NOW(), NOW()),
(1, 'Kamar A2', 'Kamar deluxe dengan kamar mandi dalam', 1200000, 1, 15, 1, NOW(), NOW()),
(1, 'Kamar A3', 'Kamar premium dengan balkon', 1500000, 1, 18, 1, NOW(), NOW()),
-- Property ID 2
(2, 'Kamar B1', 'Kamar VIP dengan balkon dan city view', 2000000, 1, 20, 1, NOW(), NOW()),
(2, 'Kamar B2', 'Kamar executive dengan workspace', 1800000, 1, 18, 1, NOW(), NOW()),
(2, 'Kamar B3', 'Kamar standar lokasi strategis', 1500000, 1, 15, 1, NOW(), NOW()),
-- Property ID 3
(3, 'Kamar C1', 'Kamar ekonomis untuk mahasiswa', 850000, 1, 10, 1, NOW(), NOW()),
(3, 'Kamar C2', 'Kamar nyaman dengan WiFi', 900000, 1, 12, 1, NOW(), NOW()),
(3, 'Kamar C3', 'Kamar luas dengan AC', 1100000, 1, 14, 1, NOW(), NOW());

-- =====================================================
-- OPTION B: Jika HANYA punya property tertentu
-- Uncomment dan sesuaikan dengan property ID yang ADA
-- =====================================================

-- Jika HANYA ada Property ID 1:
-- INSERT INTO `rooms` (`boarding_house_id`, `name`, `description`, `price`, `capacity`, `size`, `is_available`, `created_at`, `updated_at`) VALUES
-- (1, 'Kamar A1', 'Kamar standar dengan AC dan WiFi', 900000, 1, 12, 1, NOW(), NOW()),
-- (1, 'Kamar A2', 'Kamar deluxe dengan kamar mandi dalam', 1200000, 1, 15, 1, NOW(), NOW()),
-- (1, 'Kamar A3', 'Kamar premium dengan balkon', 1500000, 1, 18, 1, NOW(), NOW());

-- Jika HANYA ada Property ID 2:
-- INSERT INTO `rooms` (`boarding_house_id`, `name`, `description`, `price`, `capacity`, `size`, `is_available`, `created_at`, `updated_at`) VALUES
-- (2, 'Kamar B1', 'Kamar VIP dengan balkon', 2000000, 1, 20, 1, NOW(), NOW()),
-- (2, 'Kamar B2', 'Kamar executive', 1800000, 1, 18, 1, NOW(), NOW()),
-- (2, 'Kamar B3', 'Kamar standar', 1500000, 1, 15, 1, NOW(), NOW());

-- (2, 'Kamar B3', 'Kamar standar', 1500000, 1, 15, 1, NOW(), NOW());

-- =====================================================
-- STEP 4: VERIFIKASI HASIL
-- =====================================================

-- Cek semua rooms yang berhasil di-insert
SELECT r.id, r.name, r.price, r.boarding_house_id, bh.name as property_name, bh.user_id
FROM rooms r
JOIN boarding_houses bh ON r.boarding_house_id = bh.id
ORDER BY r.boarding_house_id, r.id;

-- Cek total rooms per property
SELECT bh.id, bh.name, COUNT(r.id) as total_rooms
FROM boarding_houses bh
LEFT JOIN rooms r ON bh.id = r.boarding_house_id
GROUP BY bh.id, bh.name
ORDER BY bh.id;

-- =====================================================
-- TROUBLESHOOTING
-- =====================================================
-- Jika masih error foreign key constraint:
-- 1. Property dengan ID tersebut TIDAK ADA di tabel boarding_houses
-- 2. Jalankan query SELECT di STEP 1 untuk cek property yang ada
-- 3. Hanya insert rooms untuk property ID yang muncul di hasil SELECT
-- =====================================================
