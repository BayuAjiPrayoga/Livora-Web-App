# API Contract - Livora Mobile

## Base URL

**Production**: `https://livora-web-app-production.up.railway.app/api/v1`  
**Local**: `http://localhost:8000/api/v1`

## Authentication

All protected endpoints require Bearer token in header:
```
Authorization: Bearer {token}
```

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "data": null
}
```

### Paginated Response
```json
{
  "success": true,
  "message": "Data retrieved successfully",
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75,
    "from": 1,
    "to": 15
  }
}
```

## Status Codes

- `200` - OK (Success)
- `201` - Created (Resource created)
- `400` - Bad Request (Validation error)
- `401` - Unauthorized (Invalid/missing token)
- `403` - Forbidden (Insufficient permissions)
- `404` - Not Found (Resource not found)
- `500` - Internal Server Error

---

## Authentication Endpoints

### 1. Login
**POST** `/v1/login`

**Request Body**:
```json
{
  "email": "tenant@livora.com",
  "password": "password"
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "tenant@livora.com",
      "role": "tenant",
      "phone": "081234567890",
      "address": "Jakarta",
      "avatar": null,
      "is_active": true
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

**Error** (401):
```json
{
  "success": false,
  "message": "Invalid credentials",
  "data": null
}
```

---

### 2. Register
**POST** `/v1/register`

**Request Body**:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "081234567890",
  "address": "Jakarta",
  "role": "tenant"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Registration successful",
  "data": {
    "user": {
      "id": 2,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "tenant",
      "phone": "081234567890",
      "address": "Jakarta",
      "avatar": null,
      "is_active": true
    },
    "token": "2|xyz789...",
    "token_type": "Bearer"
  }
}
```

---

### 3. Get Current User
**GET** `/v1/me` 🔒

**Headers**:
```
Authorization: Bearer {token}
```

**Response** (200):
```json
{
  "success": true,
  "message": "User data retrieved successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "tenant@livora.com",
      "role": "tenant",
      "phone": "081234567890",
      "address": "Jakarta",
      "avatar": null,
      "is_active": true,
      "date_of_birth": "1990-01-01",
      "gender": "male",
      "email_verified_at": "2024-01-01T00:00:00.000000Z",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  }
}
```

---

### 4. Logout
**POST** `/v1/logout` 🔒

**Response** (200):
```json
{
  "success": true,
  "message": "Logout successful",
  "data": null
}
```

---

## Property Endpoints

### 5. Get Properties (Browse)
**GET** `/v1/properties`

**Query Parameters**:
- `search` (string, optional) - Search by name, city, address
- `city` (string, optional) - Filter by city
- `min_price` (number, optional) - Minimum price
- `max_price` (number, optional) - Maximum price
- `sort_by` (string, optional) - created_at, name, price_range_start (default: created_at)
- `sort_order` (string, optional) - asc, desc (default: desc)
- `per_page` (number, optional) - Items per page (default: 15)
- `page` (number, optional) - Page number (default: 1)

**Response** (200):
```json
{
  "success": true,
  "message": "Properties retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Kost Melati",
      "slug": "kost-melati",
      "address": "Jl. Melati No. 123",
      "city": "Jakarta",
      "description": "Kost nyaman di pusat kota",
      "latitude": "-6.200000",
      "longitude": "106.816666",
      "images": [
        "https://example.com/storage/properties/image1.jpg",
        "https://example.com/storage/properties/image2.jpg"
      ],
      "is_active": true,
      "price_range_start": "1000000.00",
      "price_range_end": "2000000.00",
      "rooms_count": 10,
      "available_rooms_count": 5
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 45,
    "from": 1,
    "to": 15
  }
}
```

---

### 6. Get Property Detail
**GET** `/v1/properties/{slug}`

**Response** (200):
```json
{
  "success": true,
  "message": "Property detail retrieved successfully",
  "data": {
    "id": 1,
    "name": "Kost Melati",
    "slug": "kost-melati",
    "address": "Jl. Melati No. 123",
    "city": "Jakarta",
    "description": "Kost nyaman di pusat kota",
    "latitude": "-6.200000",
    "longitude": "106.816666",
    "images": [
      "https://example.com/storage/properties/image1.jpg"
    ],
    "is_active": true,
    "price_range_start": "1000000.00",
    "price_range_end": "2000000.00",
    "rooms_count": 10,
    "available_rooms_count": 5,
    "owner": {
      "id": 2,
      "name": "Owner Name",
      "email": "owner@livora.com",
      "phone": "081234567890"
    },
    "rooms": [
      {
        "id": 1,
        "name": "Kamar A1",
        "description": "Kamar dengan AC",
        "price": "1500000.00",
        "capacity": 1,
        "size": "3.00",
        "images": [
          "https://example.com/storage/rooms/room1.jpg"
        ],
        "is_available": true,
        "facilities": [
          {
            "id": 1,
            "name": "AC",
            "icon": "ac",
            "description": "Air Conditioner"
          },
          {
            "id": 2,
            "name": "WiFi",
            "icon": "wifi",
            "description": "Internet"
          }
        ]
      }
    ]
  }
}
```

---

### 7. Get Room Detail
**GET** `/v1/rooms/{id}`

**Response** (200):
```json
{
  "success": true,
  "message": "Room detail retrieved successfully",
  "data": {
    "id": 1,
    "boarding_house_id": 1,
    "name": "Kamar A1",
    "description": "Kamar dengan AC",
    "price": "1500000.00",
    "capacity": 1,
    "size": "3.00",
    "images": [
      "https://example.com/storage/rooms/room1.jpg"
    ],
    "is_available": true,
    "boarding_house": {
      "id": 1,
      "name": "Kost Melati",
      "slug": "kost-melati",
      "city": "Jakarta",
      "address": "Jl. Melati No. 123"
    },
    "facilities": [
      {
        "id": 1,
        "name": "AC",
        "icon": "ac",
        "description": "Air Conditioner"
      }
    ]
  }
}
```

---

## Booking Endpoints

### 8. Get User Bookings
**GET** `/v1/bookings` 🔒

**Query Parameters**:
- `status` (string, optional) - pending, confirmed, active, completed, cancelled
- `sort_by` (string, optional) - created_at, start_date, total_price
- `sort_order` (string, optional) - asc, desc
- `per_page` (number, optional) - Items per page (default: 15)

**Response** (200):
```json
{
  "success": true,
  "message": "Bookings retrieved successfully",
  "data": [
    {
      "id": 1,
      "booking_code": "BK-000001",
      "user_id": 1,
      "room_id": 1,
      "boarding_house_id": 1,
      "check_in_date": "2024-02-01",
      "check_out_date": "2024-05-01",
      "duration_months": 3,
      "duration_days": 0,
      "monthly_price": "1500000.00",
      "total_amount": "4500000.00",
      "deposit_amount": "0.00",
      "admin_fee": "0.00",
      "discount_amount": "0.00",
      "final_amount": "4500000.00",
      "status": "pending",
      "booking_type": "monthly",
      "tenant_identity_number": "3201234567890123",
      "ktp_image": "https://example.com/storage/bookings/ktp/ktp_1.jpg",
      "notes": "Booking notes",
      "created_at": "2024-01-15T10:00:00.000000Z",
      "room": {
        "id": 1,
        "name": "Kamar A1",
        "price": "1500000.00",
        "boarding_house": {
          "id": 1,
          "name": "Kost Melati",
          "city": "Jakarta"
        }
      },
      "payments": []
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 5
  }
}
```

---

### 9. Get Booking Detail
**GET** `/v1/bookings/{id}` 🔒

**Response** (200):
```json
{
  "success": true,
  "message": "Booking detail retrieved successfully",
  "data": {
    "id": 1,
    "booking_code": "BK-000001",
    "user_id": 1,
    "room_id": 1,
    "boarding_house_id": 1,
    "check_in_date": "2024-02-01",
    "check_out_date": "2024-05-01",
    "duration_months": 3,
    "final_amount": "4500000.00",
    "status": "pending",
    "room": {
      "id": 1,
      "name": "Kamar A1",
      "boarding_house": {
        "id": 1,
        "name": "Kost Melati",
        "owner": {
          "id": 2,
          "name": "Owner Name",
          "phone": "081234567890"
        }
      },
      "facilities": [
        {
          "id": 1,
          "name": "AC"
        }
      ]
    },
    "payments": [
      {
        "id": 1,
        "amount": "4500000.00",
        "status": "pending",
        "proof_image": "https://example.com/storage/payments/proof1.jpg",
        "created_at": "2024-01-15T11:00:00.000000Z"
      }
    ]
  }
}
```

---

### 10. Create Booking
**POST** `/v1/bookings` 🔒

**Request Body** (multipart/form-data):
```
room_id: 1
start_date: 2024-02-01
duration: 3
tenant_identity_number: 3201234567890123
ktp_image: (file)
notes: Optional notes
```

**Response** (201):
```json
{
  "success": true,
  "message": "Booking created successfully. Please upload payment proof to confirm your booking.",
  "data": {
    "id": 1,
    "booking_code": "BK-000001",
    "room_id": 1,
    "check_in_date": "2024-02-01",
    "check_out_date": "2024-05-01",
    "duration_months": 3,
    "final_amount": "4500000.00",
    "status": "pending"
  }
}
```

**Error** (400):
```json
{
  "success": false,
  "message": "Room is not available for the selected dates. Please choose different dates.",
  "data": null
}
```

---

### 11. Cancel Booking
**POST** `/v1/bookings/{id}/cancel` 🔒

**Response** (200):
```json
{
  "success": true,
  "message": "Booking cancelled successfully",
  "data": {
    "id": 1,
    "status": "cancelled"
  }
}
```

---

## Payment Endpoints

### 12. Get Payment History
**GET** `/v1/payments` 🔒

**Query Parameters**:
- `booking_id` (number, optional) - Filter by booking
- `status` (string, optional) - pending, verified, rejected
- `per_page` (number, optional) - Items per page

**Response** (200):
```json
{
  "success": true,
  "message": "Payments retrieved successfully",
  "data": [
    {
      "id": 1,
      "booking_id": 1,
      "booking_reference": "Kost Melati - Kamar A1",
      "amount": 4500000.00,
      "amount_formatted": "Rp 4.500.000",
      "status": "pending",
      "status_label": "Pending",
      "proof_image": "https://example.com/storage/payments/proof1.jpg",
      "notes": null,
      "verified_at": null,
      "created_at": "2024-01-15T11:00:00.000000Z",
      "created_at_formatted": "15 Jan 2024 11:00"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 3
  }
}
```

---

### 13. Upload Payment Proof
**POST** `/v1/payments` 🔒

**Request Body** (multipart/form-data):
```
booking_id: 1
amount: 4500000
proof_image: (file)
notes: Transfer from BCA
```

**Response** (201):
```json
{
  "success": true,
  "message": "Payment proof uploaded successfully. Waiting for verification.",
  "data": {
    "id": 1,
    "booking_id": 1,
    "amount": 4500000.00,
    "amount_formatted": "Rp 4.500.000",
    "status": "pending",
    "proof_image": "https://example.com/storage/payments/proof1.jpg",
    "notes": "Transfer from BCA",
    "created_at": "2024-01-15T11:00:00.000000Z"
  }
}
```

---

## Owner Endpoints

### 14. Get Owner Dashboard
**GET** `/v1/owner/dashboard` 🔒 (Role: owner)

**Response** (200):
```json
{
  "success": true,
  "message": "Dashboard data retrieved successfully",
  "data": {
    "total_properties": 5,
    "total_rooms": 50,
    "active_bookings": 30,
    "pending_payments": 5,
    "monthly_revenue": 45000000.00,
    "occupancy_rate": 60.0
  }
}
```

---

### 15. Get Owner Properties
**GET** `/v1/owner/properties` 🔒 (Role: owner)

**Query Parameters**: Same as `/v1/properties`

**Response**: Same format as `/v1/properties`

---

### 16. Get Owner Bookings
**GET** `/v1/owner/bookings` 🔒 (Role: owner)

**Query Parameters**:
- `status` (string, optional)
- `boarding_house_id` (number, optional)
- `per_page` (number, optional)

**Response**: Same format as `/v1/bookings`

---

### 17. Verify Payment
**POST** `/v1/owner/bookings/{bookingId}/payments/{paymentId}/verify` 🔒 (Role: owner)

**Response** (200):
```json
{
  "success": true,
  "message": "Payment verified and booking activated successfully",
  "data": {
    "id": 1,
    "status": "active",
    "payments": [
      {
        "id": 1,
        "status": "verified",
        "verified_at": "2024-01-15T12:00:00.000000Z"
      }
    ]
  }
}
```

---

### 18. Reject Payment
**POST** `/v1/owner/bookings/{bookingId}/payments/{paymentId}/reject` 🔒 (Role: owner)

**Request Body**:
```json
{
  "notes": "Bukti transfer tidak jelas"
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Payment rejected successfully",
  "data": {
    "id": 1,
    "payments": [
      {
        "id": 1,
        "status": "rejected",
        "notes": "Bukti transfer tidak jelas"
      }
    ]
  }
}
```

---

## Validation Rules

### Login
- `email`: required, email format
- `password`: required

### Register
- `name`: required, string, max 255
- `email`: required, email, unique
- `password`: required, min 8, confirmed
- `phone`: optional, string, max 20
- `address`: optional, string
- `role`: optional, in:admin,owner,tenant (default: tenant)

### Create Booking
- `room_id`: required, exists in rooms table
- `start_date`: required, date, after or equal today
- `duration`: required, integer, min 1, max 12
- `tenant_identity_number`: required, string, size 16
- `ktp_image`: required, image (jpeg,jpg,png), max 5MB
- `notes`: optional, string

### Upload Payment
- `booking_id`: required, exists in bookings table
- `amount`: required, numeric, min 0
- `proof_image`: required, image (jpeg,png,jpg), max 5MB
- `notes`: optional, string

---

## Error Handling

### Validation Error (400)
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "data": null,
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthenticated.",
  "data": null
}
```

### Forbidden (403)
```json
{
  "success": false,
  "message": "Unauthorized. Only owners can access this endpoint.",
  "data": null
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Resource not found",
  "data": null
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Internal server error",
  "data": null
}
```
