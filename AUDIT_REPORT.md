# Livora Database Column Audit Report

Generated: December 19, 2025

## Database Schema Changes (from migration: enhance_bookings_table_for_booking_engine)

### Bookings Table Column Renames:

-   ❌ `start_date` → ✅ `check_in_date`
-   ❌ `end_date` → ✅ `check_out_date`
-   ❌ `duration` → ✅ `duration_months`
-   ❌ `total_price` → ✅ `total_amount`

## Files That Need Updates:

### Controllers (Backend Logic - HIGH PRIORITY):

1. ✅ `app/Http/Controllers/Tenant/BookingController.php` - FIXED (store, update methods)
2. ✅ `app/Http/Controllers/Mitra/BookingController.php` - FIXED (store method)
3. ⚠️ `app/Http/Controllers/Admin/BookingController.php` - NEEDS FIX
4. ⚠️ `app/Http/Controllers/Api/V1/BookingController.php` - NEEDS FIX
5. ⚠️ `app/Http/Controllers/Api/V1/DashboardController.php` - NEEDS FIX
6. ⚠️ `app/Http/Controllers/Admin/ReportController.php` - NEEDS FIX

### Resources (API Responses - HIGH PRIORITY):

1. ⚠️ `app/Http/Resources/BookingResource.php` - NEEDS FIX
2. ⚠️ `app/Http/Resources/RoomResource.php` - NEEDS FIX

### Models (Business Logic - HIGH PRIORITY):

1. ✅ `app/Models/Booking.php` - FIXED
2. ✅ `app/Models/Room.php` - FIXED

### Commands (Console/Cron - MEDIUM PRIORITY):

1. ⚠️ `app/Console/Commands/UpdateBookingStatus.php` - NEEDS FIX

### Views (Frontend Display - CRITICAL USER FACING):

1. ✅ `resources/views/tenant/bookings/show.blade.php` - FIXED
2. ✅ `resources/views/tenant/bookings/index.blade.php` - FIXED
3. ⚠️ `resources/views/tenant/bookings/edit.blade.php` - NEEDS FIX
4. ⚠️ `resources/views/tenant/payments/*.blade.php` - NEEDS FIX (multiple files)
5. ⚠️ `resources/views/mitra/bookings/*.blade.php` - NEEDS FIX (multiple files)
6. ⚠️ `resources/views/mitra/payments/*.blade.php` - NEEDS FIX (multiple files)
7. ⚠️ `resources/views/admin/bookings/*.blade.php` - NEEDS FIX (multiple files)
8. ⚠️ `resources/views/admin/payments/*.blade.php` - NEEDS FIX (multiple files)

## Priority Fix Order:

1. **CRITICAL**: API Resources (affects all API responses)
2. **HIGH**: Admin Controllers (affects admin panel functionality)
3. **HIGH**: API Controllers (affects mobile app)
4. **HIGH**: Console Commands (affects scheduled tasks)
5. **MEDIUM**: Mitra/Admin Views (fix as errors appear)

## Notes:

-   Some filter parameters in controllers use `start_date`/`end_date` as REQUEST parameters (OK - those are query params, not database columns)
-   Focus on database column references (e.g., `->start_date`, `'start_date' =>` in queries/inserts)
