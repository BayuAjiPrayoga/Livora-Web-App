-- =====================================================
-- CEK PROPERTY YANG ADA DI DATABASE
-- Jalankan ini dulu untuk tahu property ID mana yang ada
-- =====================================================

SELECT id, name, slug, user_id, city 
FROM boarding_houses 
ORDER BY id;

-- Cek juga total properties
SELECT COUNT(*) as total_properties FROM boarding_houses;
