-- Query untuk check payment detail dengan snap_token
-- Jalankan di Railway MySQL atau TablePlus

SELECT 
    p.id,
    p.order_id,
    p.booking_id,
    p.amount,
    p.status,
    SUBSTRING(p.snap_token, 1, 20) AS snap_token_preview,
    p.transaction_id,
    p.payment_type,
    p.payment_method,
    p.created_at,
    b.booking_code,
    b.status AS booking_status,
    u.name AS tenant_name,
    u.email AS tenant_email
FROM payments p
LEFT JOIN bookings b ON b.id = p.booking_id
LEFT JOIN users u ON u.id = b.user_id
WHERE p.snap_token IS NOT NULL
ORDER BY p.created_at DESC;
