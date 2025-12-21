# ERD Reference - Livora Database

## Overview

Dokumen ini merangkum entitas database Laravel yang relevan untuk Flutter development. Tidak termasuk SQL schema, hanya relasi dan field penting.

---

## Core Entities

### 1. User
**Table**: `users`

**Fields**:
- `id` (int, PK)
- `name` (string)
- `email` (string, unique)
- `password` (string, hashed)
- `role` (enum: admin, owner, tenant)
- `phone` (string, nullable)
- `address` (string, nullable)
- `avatar` (string, nullable)
- `date_of_birth` (date, nullable)
- `gender` (enum: male, female, nullable)
- `is_active` (boolean, default: true)
- `email_verified_at` (timestamp, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships**:
- Has Many: `BoardingHouse` (if role = owner)
- Has Many: `Booking` (if role = tenant)
- Has Many: `Ticket`
- Has Many: `Notification`

**Business Rules**:
- Email must be unique
- Role determines access level
- Only active users can login

---

### 2. BoardingHouse (Property)
**Table**: `boarding_houses`

**Fields**:
- `id` (int, PK)
- `user_id` (int, FK → users.id)
- `name` (string)
- `slug` (string, unique)
- `address` (string)
- `city` (string)
- `description` (text, nullable)
- `latitude` (decimal, nullable)
- `longitude` (decimal, nullable)
- `images` (json, array of image URLs)
- `price_range_start` (decimal)
- `price_range_end` (decimal)
- `is_active` (boolean, default: true)
- `is_verified` (boolean, default: false)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships**:
- Belongs To: `User` (owner)
- Has Many: `Room`
- Has Many: `Booking`
- Has Many: `Ticket`

**Business Rules**:
- Slug is auto-generated from name
- Only active properties shown in browse
- Price range calculated from room prices

---

### 3. Room
**Table**: `rooms`

**Fields**:
- `id` (int, PK)
- `boarding_house_id` (int, FK → boarding_houses.id)
- `name` (string)
- `description` (text, nullable)
- `price` (decimal)
- `capacity` (int)
- `size` (decimal, nullable, in m²)
- `images` (json, array of image URLs)
- `is_available` (boolean, default: true)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships**:
- Belongs To: `BoardingHouse`
- Has Many: `Booking`
- Belongs To Many: `Facility` (pivot table: `facility_room`)

**Business Rules**:
- Availability checked dynamically based on bookings
- `is_available` is manual override flag
- Multiple bookings allowed for different date ranges

---

### 4. Facility
**Table**: `facilities`

**Fields**:
- `id` (int, PK)
- `name` (string)
- `icon` (string, nullable)
- `description` (string, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships**:
- Belongs To Many: `Room` (pivot table: `facility_room`)

**Common Facilities**:
- AC (Air Conditioner)
- WiFi
- Kasur (Bed)
- Lemari (Wardrobe)
- Kamar Mandi Dalam (Private Bathroom)
- TV
- Meja Belajar (Study Desk)
- Parkir (Parking)

---

### 5. Booking
**Table**: `bookings`

**Fields**:
- `id` (int, PK)
- `user_id` (int, FK → users.id)
- `room_id` (int, FK → rooms.id)
- `boarding_house_id` (int, FK → boarding_houses.id)
- `booking_code` (string, unique, auto-generated)
- `check_in_date` (date)
- `check_out_date` (date)
- `duration_months` (int)
- `duration_days` (int)
- `monthly_price` (decimal)
- `total_amount` (decimal)
- `deposit_amount` (decimal, default: 0)
- `admin_fee` (decimal, default: 0)
- `discount_amount` (decimal, default: 0)
- `final_amount` (decimal)
- `status` (enum: pending, confirmed, active, completed, cancelled)
- `booking_type` (enum: monthly, daily)
- `tenant_name` (string, nullable)
- `tenant_phone` (string, nullable)
- `tenant_email` (string, nullable)
- `tenant_identity_number` (string, KTP number)
- `tenant_address` (string, nullable)
- `emergency_contact_name` (string, nullable)
- `emergency_contact_phone` (string, nullable)
- `ktp_image` (string, path to KTP image)
- `notes` (text, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships**:
- Belongs To: `User` (tenant)
- Belongs To: `Room`
- Belongs To: `BoardingHouse`
- Has Many: `Payment`

**Status Flow**:
```
pending → confirmed → active → completed
   ↓
cancelled
```

**Business Rules**:
- `pending`: Booking created, waiting for payment
- `confirmed`: Payment uploaded, waiting for verification
- `active`: Payment verified, tenant can check-in
- `completed`: Booking period ended
- `cancelled`: Booking cancelled by tenant or owner
- Booking code format: `BK-YYYYMMDD-XXXXXX`
- `final_amount` = `total_amount` + `deposit_amount` + `admin_fee` - `discount_amount`

---

### 6. Payment
**Table**: `payments`

**Fields**:
- `id` (int, PK)
- `booking_id` (int, FK → bookings.id)
- `amount` (decimal)
- `proof_image` (string, path to payment proof)
- `status` (enum: pending, verified, rejected, settlement, expired, cancelled, failed, refund)
- `notes` (text, nullable)
- `verified_at` (timestamp, nullable)
- `verified_by` (int, FK → users.id, nullable)
- `snap_token` (string, nullable, Midtrans)
- `order_id` (string, nullable, Midtrans)
- `transaction_id` (string, nullable, Midtrans)
- `payment_type` (string, nullable, Midtrans)
- `payment_method` (string, nullable, Midtrans)
- `midtrans_status` (string, nullable)
- `transaction_time` (timestamp, nullable)
- `midtrans_response` (json, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships**:
- Belongs To: `Booking`
- Belongs To: `User` (verified_by)

**Status Flow (Manual)**:
```
pending → verified
   ↓
rejected
```

**Status Flow (Midtrans)**:
```
pending → settlement (success)
   ↓
expired / cancelled / failed
```

**Business Rules**:
- `pending`: Payment proof uploaded, waiting for owner verification
- `verified`: Payment verified by owner, booking becomes active
- `rejected`: Payment rejected by owner, tenant can upload again
- `settlement`: Midtrans payment successful
- `expired`: Midtrans payment expired
- Multiple payments allowed per booking (e.g., monthly installments)

---

### 7. Ticket (Support)
**Table**: `tickets`

**Fields**:
- `id` (int, PK)
- `user_id` (int, FK → users.id)
- `boarding_house_id` (int, FK → boarding_houses.id, nullable)
- `room_id` (int, FK → rooms.id, nullable)
- `title` (string)
- `description` (text)
- `category` (enum: maintenance, complaint, inquiry, other)
- `priority` (enum: low, medium, high)
- `status` (enum: open, in_progress, resolved, closed)
- `assigned_to` (int, FK → users.id, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships**:
- Belongs To: `User` (creator)
- Belongs To: `BoardingHouse` (nullable)
- Belongs To: `Room` (nullable)
- Belongs To: `User` (assigned_to, nullable)

**Business Rules**:
- Tenant can create tickets
- Owner can respond to tickets for their properties
- Admin can manage all tickets

---

### 8. Notification
**Table**: `notifications`

**Fields**:
- `id` (int, PK)
- `user_id` (int, FK → users.id)
- `type` (string)
- `title` (string)
- `message` (text)
- `data` (json, nullable)
- `read_at` (timestamp, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships**:
- Belongs To: `User`

**Notification Types**:
- `booking_created`: New booking created
- `booking_confirmed`: Booking confirmed
- `booking_cancelled`: Booking cancelled
- `payment_uploaded`: Payment proof uploaded
- `payment_verified`: Payment verified
- `payment_rejected`: Payment rejected
- `ticket_created`: New ticket created
- `ticket_updated`: Ticket status updated

---

## Entity Relationships Diagram (Text)

```
User (owner)
    ↓ (1:N)
BoardingHouse
    ↓ (1:N)
Room ←→ (N:M) Facility
    ↓ (1:N)
Booking
    ↓ (1:N)
Payment

User (tenant)
    ↓ (1:N)
Booking

User
    ↓ (1:N)
Ticket

User
    ↓ (1:N)
Notification
```

---

## Key Relationships for Flutter

### 1. Browse Properties
```
GET /properties
    ↓
BoardingHouse + Room count + Available room count
```

### 2. Property Detail
```
GET /properties/{slug}
    ↓
BoardingHouse
    ├── Owner (User)
    └── Rooms
        └── Facilities
```

### 3. Create Booking
```
POST /bookings
    ↓
Create Booking (user_id, room_id, boarding_house_id)
    ↓
Return Booking with Room and BoardingHouse
```

### 4. User Bookings
```
GET /bookings
    ↓
Booking
    ├── Room
    │   └── BoardingHouse
    └── Payments
```

### 5. Upload Payment
```
POST /payments
    ↓
Create Payment (booking_id, amount, proof_image)
    ↓
Update Booking status to 'confirmed'
```

### 6. Owner Verify Payment
```
POST /owner/bookings/{bookingId}/payments/{paymentId}/verify
    ↓
Update Payment status to 'verified'
    ↓
Update Booking status to 'active'
```

---

## Data Integrity Rules

### Cascade Deletes
- Delete `BoardingHouse` → Delete all `Room`s
- Delete `Room` → Cannot delete if has active bookings
- Delete `User` → Cannot delete if has active bookings or properties

### Soft Deletes
- Not implemented (use `is_active` flag instead)

### Unique Constraints
- `users.email`
- `boarding_houses.slug`
- `bookings.booking_code`

### Foreign Key Constraints
- All FK relationships enforced
- `ON DELETE RESTRICT` for critical relationships
- `ON DELETE CASCADE` for dependent data

---

## Indexing Recommendations (for Backend)

### High Priority
- `users.email` (unique index)
- `boarding_houses.slug` (unique index)
- `boarding_houses.city` (index for filtering)
- `bookings.user_id` (index for user bookings)
- `bookings.status` (index for filtering)
- `payments.booking_id` (index for payment lookup)

### Medium Priority
- `rooms.boarding_house_id`
- `bookings.room_id`
- `notifications.user_id`
- `tickets.user_id`

---

## Flutter Model Mapping

### Entity → Model → DTO

```
Database Entity (Laravel)
    ↓
API Response (JSON)
    ↓
Data Model (Flutter - data/models/)
    ↓
Domain Entity (Flutter - domain/entities/)
    ↓
ViewModel (Flutter - presentation/viewmodels/)
    ↓
UI (Flutter - presentation/pages/)
```

### Example: Property
```dart
// API Response
{
  "id": 1,
  "name": "Kost Melati",
  "city": "Jakarta",
  "price_range_start": "1000000.00",
  "images": ["url1.jpg", "url2.jpg"]
}

// Data Model (data/models/property_model.dart)
class PropertyModel {
  final int id;
  final String name;
  final String city;
  final String priceRangeStart;
  final List<String> images;
  
  factory PropertyModel.fromJson(Map<String, dynamic> json) { ... }
  Property toEntity() { ... }
}

// Domain Entity (domain/entities/property.dart)
class Property {
  final int id;
  final String name;
  final String city;
  final double priceStart;
  final List<String> images;
}
```

---

## Notes for Flutter Development

1. **Pagination**: Backend returns paginated data, Flutter should implement infinite scroll
2. **Image URLs**: Backend returns relative paths, Flutter needs to prepend base URL
3. **Date Format**: Backend uses `YYYY-MM-DD`, Flutter should parse to `DateTime`
4. **Currency**: Backend uses decimal, Flutter should format as Rupiah
5. **Enums**: Backend uses string enums, Flutter should map to Dart enums
6. **Relationships**: Backend eager loads relationships, Flutter should handle nested objects
7. **Null Safety**: Backend may return null, Flutter should handle nullable fields
8. **Validation**: Backend validates on server, Flutter should validate on client too
