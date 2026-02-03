-- =====================================================
-- INSERT FACILITIES DATA
-- Jalankan di phpMyAdmin atau MySQL console
-- =====================================================

-- Cek apakah sudah ada data
SELECT COUNT(*) as total_facilities FROM facilities;

-- Jika hasil 0, jalankan INSERT di bawah:
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
('Balkon', '🏞️', 'Balkon atau teras pribadi', NOW(), NOW()),
('Kunci Pribadi', '🔐', 'Kunci kamar pribadi', NOW(), NOW()),
('Parkir Motor', '🏍️', 'Tempat parkir motor', NOW(), NOW()),
('Parkir Mobil', '🚗', 'Tempat parkir mobil', NOW(), NOW()),
('CCTV', '📹', 'Keamanan CCTV 24 jam', NOW(), NOW()),
('Penjaga Kost', '👮', 'Penjaga kost / security', NOW(), NOW()),
('Laundry', '🧺', 'Layanan laundry', NOW(), NOW()),
('Dapur Bersama', '🍳', 'Dapur bersama untuk memasak', NOW(), NOW()),
('Ruang Tamu', '🛋️', 'Ruang tamu bersama', NOW(), NOW());

-- Verifikasi hasil
SELECT * FROM facilities ORDER BY name;
