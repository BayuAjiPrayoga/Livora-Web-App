-- Livora Database - Data Minimal (5 Records per tabel)
-- Generated: 2025-12-12

USE livora;

-- Hapus data lama
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE tickets;
TRUNCATE TABLE notifications;
TRUNCATE TABLE payments;
TRUNCATE TABLE bookings;
TRUNCATE TABLE facility_room;
TRUNCATE TABLE rooms;
TRUNCATE TABLE facilities;
TRUNCATE TABLE boarding_houses;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS=1;

-- Insert Users (Admin, Owner, Tenant)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `address`, `date_of_birth`, `gender`, `is_active`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Livora', 'admin@livora.com', '2025-12-12 07:00:00', '$2y$12$tpc9N4Crei54AEOkMPys/eHdI2FCvBao/66PjS4jI/5L1HDH3MbTC', 'admin', '081234567890', 'Jl. Admin Raya No. 1, Jakarta', '1990-01-15', 'male', 1, NULL, NULL, '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(2, 'Budi Santoso', 'owner@livora.com', '2025-12-12 07:00:00', '$2y$12$K8uXTFGRG1kvSO9h6Fc.juS9Ake7jk3JmwHNJTqIXQu07k6WCUOWi', 'owner', '081234567891', 'Jl. Mitra No. 10, Bandung', '1985-05-20', 'male', 1, NULL, NULL, '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(3, 'Siti Aminah', 'owner2@livora.com', '2025-12-12 07:00:00', '$2y$12$K8uXTFGRG1kvSO9h6Fc.juS9Ake7jk3JmwHNJTqIXQu07k6WCUOWi', 'owner', '081234567892', 'Jl. Sudirman No. 25, Jakarta', '1988-08-10', 'female', 1, NULL, NULL, '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(4, 'Andi Wijaya', 'tenant@livora.com', '2025-12-12 07:00:00', '$2y$12$tpc9N4Crei54AEOkMPys/eHdI2FCvBao/66PjS4jI/5L1HDH3MbTC', 'tenant', '081234567893', 'Jl. Mahasiswa No. 15, Bandung', '2000-03-12', 'male', 1, NULL, NULL, '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(5, 'Dewi Lestari', 'tenant2@livora.com', '2025-12-12 07:00:00', '$2y$12$tpc9N4Crei54AEOkMPys/eHdI2FCvBao/66PjS4jI/5L1HDH3MbTC', 'tenant', '081234567894', 'Jl. Gatot Subroto No. 88, Jakarta', '2001-07-25', 'female', 1, NULL, NULL, '2025-12-12 07:00:00', '2025-12-12 07:00:00');

-- Insert Facilities
INSERT INTO `facilities` (`id`, `name`, `icon`, `description`, `created_at`, `updated_at`) VALUES
(1, 'WiFi Gratis', 'wifi-icon.svg', 'Internet WiFi berkecepatan tinggi', '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(2, 'AC', 'ac-icon.svg', 'AC pendingin ruangan', '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(3, 'Kamar Mandi Dalam', 'bathroom-icon.svg', 'Kamar mandi di dalam kamar', '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(4, 'Kasur', 'bed-icon.svg', 'Kasur spring bed', '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(5, 'Lemari', 'wardrobe-icon.svg', 'Lemari pakaian', '2025-12-12 07:00:00', '2025-12-12 07:00:00');

-- Insert Boarding Houses
INSERT INTO `boarding_houses` (`id`, `user_id`, `name`, `slug`, `address`, `city`, `description`, `latitude`, `longitude`, `images`, `is_active`, `price_range_start`, `price_range_end`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 2, 'Kost Melati Bandung', 'kost-melati-bandung', 'Jl. Dago No. 123, Bandung', 'Bandung', 'Kost strategis dekat ITB dan kampus lainnya. Lingkungan aman dan nyaman.', -6.900000, 107.620000, '["properties/house-1.jpg", "properties/house-1-2.jpg"]', 1, 800000.00, 1500000.00, 1, '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(2, 2, 'Kost Mawar Jakarta', 'kost-mawar-jakarta', 'Jl. Sudirman No. 45, Jakarta Pusat', 'Jakarta', 'Kost eksklusif di pusat kota. Dekat MRT dan pusat bisnis.', -6.200000, 106.820000, '["properties/house-2.jpg", "properties/house-2-2.jpg"]', 1, 1500000.00, 2500000.00, 1, '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(3, 3, 'Kost Anggrek Surabaya', 'kost-anggrek-surabaya', 'Jl. Pemuda No. 88, Surabaya', 'Surabaya', 'Kost modern dengan fasilitas lengkap. Dekat kampus dan mall.', -7.250000, 112.750000, '["properties/house-3.jpg"]', 1, 900000.00, 1800000.00, 1, '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(4, 3, 'Kost Kenanga Yogyakarta', 'kost-kenanga-yogyakarta', 'Jl. Kaliurang Km 5, Yogyakarta', 'Yogyakarta', 'Kost dekat UGM dan kampus lain. Suasana tenang dan nyaman.', -7.780000, 110.370000, '["properties/house-4.jpg"]', 1, 700000.00, 1200000.00, 1, '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(5, 3, 'Kost Seruni Semarang', 'kost-seruni-semarang', 'Jl. Pandanaran No. 100, Semarang', 'Semarang', 'Kost bersih dan rapi. Fasilitas lengkap dengan harga terjangkau.', -6.990000, 110.420000, '["properties/house-5.jpg"]', 1, 750000.00, 1300000.00, 0, '2025-12-12 07:00:00', '2025-12-12 07:00:00');

-- Insert Rooms
INSERT INTO `rooms` (`id`, `boarding_house_id`, `name`, `description`, `price`, `capacity`, `size`, `images`, `is_available`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kamar A1', 'Kamar standar dengan AC dan WiFi', 900000.00, 1, 12.00, '["rooms/room-1.jpg"]', 1, '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(2, 1, 'Kamar A2', 'Kamar deluxe dengan kamar mandi dalam', 1200000.00, 1, 15.00, '["rooms/room-2.jpg"]', 1, '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(3, 2, 'Kamar B1', 'Kamar VIP dengan balkon', 2000000.00, 1, 20.00, '["rooms/room-3.jpg"]', 1, '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(4, 3, 'Kamar C1', 'Kamar ekonomis untuk mahasiswa', 850000.00, 1, 10.00, '["rooms/room-4.jpg"]', 1, '2025-12-12 07:00:00', '2025-12-12 07:00:00'),
(5, 4, 'Kamar D1', 'Kamar nyaman dekat kampus', 750000.00, 1, 12.00, '["rooms/room-5.jpg"]', 0, '2025-12-12 07:00:00', '2025-12-12 07:00:00');

-- Insert Facility_Room (Pivot)
INSERT INTO `facility_room` (`id`, `room_id`, `facility_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 4, NULL, NULL),
(4, 2, 1, NULL, NULL),
(5, 2, 2, NULL, NULL),
(6, 2, 3, NULL, NULL),
(7, 2, 4, NULL, NULL),
(8, 2, 5, NULL, NULL),
(9, 3, 1, NULL, NULL),
(10, 3, 2, NULL, NULL),
(11, 3, 3, NULL, NULL),
(12, 3, 4, NULL, NULL),
(13, 3, 5, NULL, NULL),
(14, 4, 1, NULL, NULL),
(15, 4, 4, NULL, NULL),
(16, 5, 1, NULL, NULL),
(17, 5, 2, NULL, NULL),
(18, 5, 4, NULL, NULL),
(19, 5, 5, NULL, NULL);

-- Insert Bookings
INSERT INTO `bookings` (`id`, `user_id`, `room_id`, `start_date`, `duration`, `end_date`, `total_price`, `final_amount`, `status`, `notes`, `tenant_identity_number`, `ktp_image`, `created_at`, `updated_at`) VALUES
(1, 4, 1, '2025-12-01', 6, '2026-06-01', 5400000.00, 5400000.00, 'confirmed', 'Booking pertama saya', '3201234567890001', 'ktp-images/ktp1.jpg', '2025-12-01 08:00:00', '2025-12-02 10:30:00'),
(2, 4, 2, '2025-11-15', 3, '2026-02-15', 3600000.00, 3600000.00, 'active', NULL, '3201234567890001', 'ktp-images/ktp2.jpg', '2025-11-15 09:00:00', '2025-11-16 14:00:00'),
(3, 5, 3, '2025-12-10', 12, '2026-12-10', 24000000.00, 24000000.00, 'pending', 'Mohon segera dikonfirmasi', '3301234567890002', 'ktp-images/ktp3.jpg', '2025-12-10 10:00:00', '2025-12-10 10:00:00'),
(4, 5, 4, '2025-10-01', 6, '2026-04-01', 5100000.00, 5100000.00, 'completed', NULL, '3301234567890002', 'ktp-images/ktp4.jpg', '2025-10-01 07:00:00', '2026-04-05 12:00:00'),
(5, 4, 5, '2025-09-01', 3, '2025-12-01', 2250000.00, 2250000.00, 'cancelled', 'Membatalkan karena ada urusan keluarga', '3201234567890001', 'ktp-images/ktp5.jpg', '2025-09-01 11:00:00', '2025-09-15 16:00:00');

-- Insert Payments
INSERT INTO `payments` (`id`, `booking_id`, `amount`, `proof_image`, `status`, `notes`, `verified_at`, `created_at`, `updated_at`) VALUES
(1, 1, 5400000.00, 'payment-proofs/proof1.jpg', 'verified', 'Pembayaran lunas', '2025-12-02 14:00:00', '2025-12-01 10:00:00', '2025-12-02 14:00:00'),
(2, 2, 3600000.00, 'payment-proofs/proof2.jpg', 'verified', NULL, '2025-11-16 09:00:00', '2025-11-15 11:00:00', '2025-11-16 09:00:00'),
(3, 3, 24000000.00, 'payment-proofs/proof3.jpg', 'pending', NULL, NULL, '2025-12-10 12:00:00', '2025-12-10 12:00:00'),
(4, 4, 5100000.00, 'payment-proofs/proof4.jpg', 'verified', 'Lunas', '2025-10-02 10:00:00', '2025-10-01 09:00:00', '2025-10-02 10:00:00'),
(5, 2, 1200000.00, 'payment-proofs/proof5.jpg', 'rejected', 'Bukti transfer tidak jelas, mohon upload ulang', NULL, '2025-11-20 15:00:00', '2025-11-21 09:00:00');

-- Insert Tickets
INSERT INTO `tickets` (`id`, `user_id`, `room_id`, `subject`, `message`, `status`, `priority`, `response`, `resolved_at`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'AC tidak dingin', 'AC di kamar A1 kurang dingin, mohon diperbaiki', 'resolved', 'high', 'Sudah diperbaiki oleh teknisi. Terima kasih atas laporannya.', '2025-12-03 16:00:00', '2025-12-02 14:00:00', '2025-12-03 16:00:00'),
(2, 4, 2, 'WiFi lemot', 'Koneksi internet sangat lambat sejak 2 hari lalu', 'in_progress', 'medium', 'Sedang kami tindaklanjuti dengan provider. Terima kasih.', NULL, '2025-12-10 09:00:00', '2025-12-10 15:00:00'),
(3, 5, 3, 'Lampu kamar mati', 'Lampu kamar tidak menyala, mohon diperbaiki segera', 'open', 'urgent', NULL, NULL, '2025-12-11 20:00:00', '2025-12-11 20:00:00'),
(4, 5, 4, 'Request tambahan kipas angin', 'Boleh minta tambahan kipas angin untuk kamar?', 'resolved', 'low', 'Kipas angin sudah disediakan di kamar.', '2025-10-15 10:00:00', '2025-10-10 11:00:00', '2025-10-15 10:00:00'),
(5, 4, 1, 'Keran air rusak', 'Keran air di kamar mandi bocor dan susah ditutup', 'closed', 'medium', 'Sudah diganti dengan yang baru.', '2025-11-25 14:00:00', '2025-11-20 08:00:00', '2025-11-26 09:00:00');

-- Insert Notifications
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `data`, `read_at`, `is_email_sent`, `is_push_sent`, `priority`, `action_url`, `created_at`, `updated_at`) VALUES
(1, 4, 'booking_confirmed', 'Booking Dikonfirmasi', 'Booking Anda untuk Kamar A1 telah dikonfirmasi oleh pemilik kost.', NULL, '2025-12-02 11:00:00', 1, 0, 'high', '/tenant/bookings/1', '2025-12-02 10:30:00', '2025-12-02 11:00:00'),
(2, 4, 'payment_verified', 'Pembayaran Diverifikasi', 'Pembayaran Anda sebesar Rp 5.400.000 telah diverifikasi.', NULL, '2025-12-02 15:00:00', 1, 0, 'high', '/tenant/payments/1', '2025-12-02 14:00:00', '2025-12-02 15:00:00'),
(3, 5, 'booking_created', 'Booking Berhasil', 'Booking Anda telah berhasil dibuat. Menunggu konfirmasi pemilik kost.', NULL, NULL, 0, 0, 'medium', '/tenant/bookings/3', '2025-12-10 10:00:00', '2025-12-10 10:00:00'),
(4, 4, 'ticket_resolved', 'Tiket Diselesaikan', 'Tiket "AC tidak dingin" telah diselesaikan.', NULL, '2025-12-04 08:00:00', 1, 0, 'medium', '/tenant/tickets/1', '2025-12-03 16:00:00', '2025-12-04 08:00:00'),
(5, 2, 'new_booking', 'Booking Baru', 'Ada booking baru untuk Kamar A1 dari Andi Wijaya.', NULL, '2025-12-01 09:00:00', 1, 0, 'high', '/mitra/bookings/1', '2025-12-01 08:00:00', '2025-12-01 09:00:00');
