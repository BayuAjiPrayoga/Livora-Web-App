-- =====================================================
-- INSERT ROOMS DATA (Sample/Testing)
-- Jalankan di phpMyAdmin setelah facilities di-insert
-- =====================================================

-- Cek boarding houses yang ada
SELECT id, name, user_id FROM boarding_houses ORDER BY id;

-- Cek apakah sudah ada rooms
SELECT COUNT(*) as total_rooms FROM rooms;

-- Jika result 0, jalankan INSERT di bawah:

-- ROOMS untuk Property ID 1 (Kost Melati Bandung - Owner: user_id 2)
INSERT INTO `rooms` (`boarding_house_id`, `name`, `description`, `price`, `capacity`, `size`, `is_available`, `created_at`, `updated_at`) VALUES
(1, 'Kamar A1', 'Kamar standar dengan AC dan WiFi', 900000, 1, 12, 1, NOW(), NOW()),
(1, 'Kamar A2', 'Kamar deluxe dengan kamar mandi dalam', 1200000, 1, 15, 1, NOW(), NOW()),
(1, 'Kamar A3', 'Kamar premium dengan balkon', 1500000, 1, 18, 1, NOW(), NOW());

-- ROOMS untuk Property ID 2 (Kost Mawar Jakarta - Owner: user_id 2)
INSERT INTO `rooms` (`boarding_house_id`, `name`, `description`, `price`, `capacity`, `size`, `is_available`, `created_at`, `updated_at`) VALUES
(2, 'Kamar B1', 'Kamar VIP dengan balkon dan city view', 2000000, 1, 20, 1, NOW(), NOW()),
(2, 'Kamar B2', 'Kamar executive dengan workspace', 1800000, 1, 18, 1, NOW(), NOW()),
(2, 'Kamar B3', 'Kamar standar lokasi strategis', 1500000, 1, 15, 1, NOW(), NOW());

-- ROOMS untuk Property ID 3 (Kost Anggrek Surabaya - Owner: user_id 3)
INSERT INTO `rooms` (`boarding_house_id`, `name`, `description`, `price`, `capacity`, `size`, `is_available`, `created_at`, `updated_at`) VALUES
(3, 'Kamar C1', 'Kamar ekonomis untuk mahasiswa', 850000, 1, 10, 1, NOW(), NOW()),
(3, 'Kamar C2', 'Kamar nyaman dengan WiFi', 900000, 1, 12, 1, NOW(), NOW()),
(3, 'Kamar C3', 'Kamar luas dengan AC', 1100000, 1, 14, 1, NOW(), NOW());

-- ROOMS untuk Property ID 4 (Kost Kenanga Yogyakarta - Owner: user_id 3)
INSERT INTO `rooms` (`boarding_house_id`, `name`, `description`, `price`, `capacity`, `size`, `is_available`, `created_at`, `updated_at`) VALUES
(4, 'Kamar D1', 'Kamar nyaman dekat kampus', 750000, 1, 12, 1, NOW(), NOW()),
(4, 'Kamar D2', 'Kamar dengan view gunung', 800000, 1, 13, 1, NOW(), NOW());

-- ROOMS untuk Property ID 5 (Kost Seruni Semarang - Owner: user_id 3)
INSERT INTO `rooms` (`boarding_house_id`, `name`, `description`, `price`, `capacity`, `size`, `is_available`, `created_at`, `updated_at`) VALUES
(5, 'Kamar E1', 'Kamar bersih dan rapi', 750000, 1, 11, 1, NOW(), NOW()),
(5, 'Kamar E2', 'Kamar dengan kamar mandi dalam', 900000, 1, 13, 1, NOW(), NOW());

-- Verifikasi hasil
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
