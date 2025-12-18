# 📱 LIVORA MOBILE APP - Technical Specification

**Version:** 1.0  
**Date:** December 15, 2025  
**Target Platform:** Flutter (Android & iOS)  
**Backend:** Laravel 11 + Sanctum  
**Scope:** Tenant & Owner (Mitra) Only - Admin Dashboard EXCLUDED

---

## 📋 Table of Contents

1. [API Endpoints](#1-api-endpoints)
2. [Authentication Mechanism](#2-authentication-mechanism)
3. [Data Models](#3-data-models)
4. [Design Tokens](#4-design-tokens)
5. [Feature Scope](#5-feature-scope)
6. [Native Features](#6-native-features)
7. [Future Development](#7-future-development)

---

## 1. 🔌 API Endpoints

### Base URL

```
http://192.168.1.31:8000/api/v1
```

### 1.1 Public Endpoints (No Auth Required)

#### **Authentication**

```http
POST /login
```

**Body:**

```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response:** (200)

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com",
            "role": "tenant",
            "phone": "081234567890",
            "is_active": true
        },
        "token": "1|xxxxxxxxxxxxxxxxxxxx"
    }
}
```

---

```http
POST /register
```

**Body:**

```json
{
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "081234567890",
    "role": "tenant"
}
```

**Validation:**

-   `role`: required, enum(`tenant`, `owner`)
-   `email`: unique, valid email
-   `password`: min 8 characters
-   `phone`: max 15 characters

**Response:** Same as login

---

#### **Property Browsing**

```http
GET /properties
```

**Query Parameters:**

-   `search`: string (name/city/address)
-   `city`: string
-   `min_price`: integer
-   `max_price`: integer
-   `sort`: enum(`price_asc`, `price_desc`, `rating`, `newest`)
-   `page`: integer (default: 1)
-   `per_page`: integer (default: 10)

**Response:** (200)

```json
{
    "success": true,
    "message": "Properties retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "Kost Melati",
            "slug": "kost-melati",
            "city": "Bandung",
            "address": "Jl. Sudirman No. 10, Bandung",
            "description": "Kost nyaman dekat kampus",
            "latitude": -6.9175,
            "longitude": 107.6191,
            "price_range": {
                "start": 800000,
                "end": 1500000,
                "formatted": "Rp 800.000 - Rp 1.500.000"
            },
            "thumbnail": "http://localhost/storage/properties/thumb.jpg",
            "images": [
                "http://localhost/storage/properties/img1.jpg",
                "http://localhost/storage/properties/img2.jpg"
            ],
            "is_active": true,
            "is_verified": true,
            "rooms_count": 10,
            "available_rooms_count": 5,
            "owner": {
                "id": 2,
                "name": "Owner Name"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 10,
        "total": 50
    }
}
```

---

```http
GET /properties/{slug}
```

**Response:** (200)

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Kost Melati",
        "slug": "kost-melati",
        "city": "Bandung",
        "address": "Jl. Sudirman No. 10",
        "description": "<p>Kost nyaman dengan fasilitas lengkap</p>",
        "latitude": -6.9175,
        "longitude": 107.6191,
        "price_range": {
            "start": 800000,
            "end": 1500000,
            "formatted": "Rp 800.000 - Rp 1.500.000"
        },
        "thumbnail": "http://localhost/storage/properties/thumb.jpg",
        "images": ["url1", "url2"],
        "is_active": true,
        "is_verified": true,
        "rooms_count": 10,
        "available_rooms_count": 5,
        "rooms": [
            {
                "id": 1,
                "name": "Kamar A1",
                "description": "Kamar dengan AC",
                "price": 1000000,
                "price_formatted": "Rp 1.000.000",
                "capacity": 1,
                "size": 12,
                "size_formatted": "12 m²",
                "is_available": true,
                "thumbnail": "url",
                "images": ["url1", "url2"],
                "facilities": [
                    {
                        "id": 1,
                        "name": "WiFi",
                        "icon": "wifi",
                        "description": "Internet 50 Mbps"
                    }
                ]
            }
        ],
        "owner": {
            "id": 2,
            "name": "Owner Name"
        }
    }
}
```

---

```http
GET /rooms/{id}
```

**Response:** Same as room object in property detail

---

### 1.2 Protected Endpoints (Sanctum Auth Required)

**Header Required:**

```
Authorization: Bearer {token}
```

#### **User Profile**

```http
GET /me
```

**Response:** (200)

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "role": "tenant",
        "phone": "081234567890",
        "address": null,
        "avatar": null,
        "email_verified_at": "2025-01-01T00:00:00.000000Z",
        "created_at": "2025-01-01T00:00:00.000000Z"
    }
}
```

---

```http
POST /logout
```

**Response:** (200)

```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

---

### 1.3 TENANT Endpoints

#### **Bookings Management**

```http
GET /bookings
```

**Query Parameters:**

-   `status`: enum(`pending`, `confirmed`, `active`, `completed`, `cancelled`)
-   `sort_by`: enum(`created_at`, `start_date`, `total_price`)
-   `sort_order`: enum(`asc`, `desc`)
-   `page`: integer

**Response:** (200)

```json
{
    "success": true,
    "message": "Bookings retrieved successfully",
    "data": [
        {
            "id": 1,
            "room": {
                "id": 1,
                "name": "Kamar A1",
                "price": 1000000
            },
            "boarding_house": {
                "id": 1,
                "name": "Kost Melati",
                "slug": "kost-melati",
                "address": "Jl. Sudirman No. 10",
                "city": "Bandung"
            },
            "tenant": {
                "id": 1,
                "name": "John Doe",
                "email": "user@example.com",
                "phone": "081234567890"
            },
            "start_date": "2025-02-01",
            "start_date_formatted": "01 Feb 2025",
            "end_date": "2025-05-01",
            "end_date_formatted": "01 Mei 2025",
            "duration": 3,
            "duration_text": "3 bulan",
            "total_price": 3000000,
            "total_price_formatted": "Rp 3.000.000",
            "final_amount": 3000000,
            "final_amount_formatted": "Rp 3.000.000",
            "status": "pending",
            "status_label": "Menunggu Konfirmasi",
            "status_color": "#FFF9C4",
            "notes": "Mohon konfirmasi segera",
            "tenant_identity_number": "1234567890123456",
            "ktp_image": "http://localhost/storage/bookings/ktp.jpg",
            "payments": [
                {
                    "id": 1,
                    "amount": 3000000,
                    "amount_formatted": "Rp 3.000.000",
                    "status": "pending",
                    "status_label": "Pending",
                    "proof_image": "http://localhost/storage/payments/proof.jpg",
                    "notes": null,
                    "verified_at": null,
                    "created_at": "2025-01-15T10:00:00.000000Z"
                }
            ],
            "payments_count": 1,
            "has_verified_payment": false,
            "created_at": "2025-01-15T10:00:00.000000Z",
            "created_at_formatted": "15 Jan 2025 10:00",
            "updated_at": "2025-01-15T10:00:00.000000Z"
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

```http
GET /bookings/{id}
```

**Response:** Same as single booking object above

---

```http
POST /bookings
```

**Body (multipart/form-data):**

```json
{
    "room_id": 1,
    "start_date": "2025-02-01",
    "duration": 3,
    "tenant_identity_number": "1234567890123456",
    "ktp_image": "file",
    "notes": "Optional notes"
}
```

**Validation Rules:**

-   `room_id`: required, exists in rooms table
-   `start_date`: required, date, >= today
-   `duration`: required, integer, 1-12 months
-   `tenant_identity_number`: required, string, exactly 16 digits
-   `ktp_image`: required, image (jpeg/jpg/png), max 2MB
-   `notes`: nullable, string, max 500 characters

**Business Logic:**

-   `end_date` = `start_date` + `duration` months (auto-calculated)
-   `total_price` = `duration` \* `room.price` (auto-calculated)
-   `final_amount` = `total_price` (auto-calculated)
-   `status` = `pending` (auto-set)
-   `user_id` = Auth user ID (auto-set)

**Response:** (201)

```json
{
    "success": true,
    "message": "Booking created successfully. Please proceed with payment.",
    "data": {
        // Same as booking object
    }
}
```

**Error Responses:**

-   **400** - Room not available
-   **422** - Validation error

---

```http
POST /bookings/{id}/cancel
```

**Body:**

```json
{
    "reason": "Optional cancellation reason"
}
```

**Rules:**

-   Only bookings with status `pending` or `confirmed` can be cancelled
-   Cannot cancel if status is `active`, `completed`, or already `cancelled`

**Response:** (200)

```json
{
    "success": true,
    "message": "Booking cancelled successfully",
    "data": {
        // Updated booking object with status = "cancelled"
    }
}
```

---

#### **Payments Management**

```http
GET /payments
```

**Response:** (200)

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "booking_id": 1,
            "amount": 3000000,
            "amount_formatted": "Rp 3.000.000",
            "status": "pending",
            "proof_image": "http://localhost/storage/payments/proof.jpg",
            "notes": null,
            "verified_at": null,
            "created_at": "2025-01-15T10:00:00.000000Z",
            "booking": {
                // Booking object
            }
        }
    ]
}
```

---

```http
POST /payments
```

**Body (multipart/form-data):**

```json
{
    "booking_id": 1,
    "amount": 3000000,
    "proof_image": "file",
    "notes": "Optional notes"
}
```

**Validation:**

-   `booking_id`: required, exists, user must own the booking
-   `amount`: required, numeric, must match booking final_amount
-   `proof_image`: required, image (jpeg/jpg/png), max 2MB
-   `notes`: nullable, string, max 500 characters

**Response:** (201)

```json
{
    "success": true,
    "message": "Payment submitted successfully. Waiting for owner verification.",
    "data": {
        // Payment object
    }
}
```

---

### 1.4 OWNER (Mitra) Endpoints

**All owner endpoints require `role = 'owner'` in authenticated user**

#### **Dashboard Statistics**

```http
GET /owner/dashboard
```

**Response:** (200)

```json
{
    "success": true,
    "message": "Dashboard statistics retrieved successfully",
    "data": {
        "statistics": {
            "total_properties": 3,
            "total_rooms": 25,
            "available_rooms": 10,
            "occupied_rooms": 15,
            "total_bookings": 50,
            "pending_bookings": 5,
            "active_bookings": 15,
            "completed_bookings": 28,
            "cancelled_bookings": 2,
            "pending_payments": 8,
            "total_revenue": 150000000,
            "total_revenue_formatted": "Rp 150.000.000",
            "revenue_this_month": 25000000,
            "revenue_this_month_formatted": "Rp 25.000.000"
        },
        "recent_bookings": [
            // Array of booking objects (last 5)
        ],
        "pending_payments": [
            // Array of payment objects that need verification
        ]
    }
}
```

---

#### **Owner's Properties**

```http
GET /owner/properties
```

**Query Parameters:**

-   `status`: enum(`active`, `inactive`)
-   `page`: integer

**Response:** (200)

```json
{
    "success": true,
    "data": [
        // Array of BoardingHouse objects owned by authenticated user
    ],
    "meta": {
        "current_page": 1,
        "total": 3
    }
}
```

---

#### **Owner's Bookings**

```http
GET /owner/bookings
```

**Query Parameters:**

-   `status`: enum(`pending`, `confirmed`, `active`, `completed`, `cancelled`)
-   `boarding_house_id`: integer (filter by specific property)
-   `sort_by`: enum(`created_at`, `start_date`, `total_price`)
-   `sort_order`: enum(`asc`, `desc`)
-   `page`: integer

**Response:** Same structure as tenant bookings, but includes all bookings from owner's properties

---

#### **Payment Verification**

```http
POST /owner/bookings/{bookingId}/payments/{paymentId}/verify
```

**Body:**

```json
{
    "notes": "Optional verification notes"
}
```

**Response:** (200)

```json
{
    "success": true,
    "message": "Payment verified successfully",
    "data": {
        // Updated payment object with status = "verified"
    }
}
```

---

```http
POST /owner/bookings/{bookingId}/payments/{paymentId}/reject
```

**Body:**

```json
{
    "notes": "Required rejection reason"
}
```

**Validation:**

-   `notes`: required when rejecting

**Response:** (200)

```json
{
    "success": true,
    "message": "Payment rejected",
    "data": {
        // Updated payment object with status = "rejected"
    }
}
```

---

## 2. 🔐 Authentication Mechanism

### 2.1 Laravel Sanctum

**Token Type:** Bearer Token  
**Storage:** FlutterSecureStorage  
**Expiration:** None (manual revocation only)

### 2.2 Implementation Flow

#### **Login Flow**

```dart
// 1. User submits email & password
final response = await dio.post('/login', data: {
  'email': email,
  'password': password,
});

// 2. Extract token from response
final token = response.data['data']['token'];
final user = User.fromJson(response.data['data']['user']);

// 3. Save token securely
await secureStorage.write(key: 'auth_token', value: token);

// 4. Navigate based on role
if (user.role == 'tenant') {
  // Navigate to Tenant Home
} else if (user.role == 'owner') {
  // Navigate to Owner Dashboard
}
```

#### **API Request with Token**

```dart
// Already implemented in ApiClient (lib/src/services/api_client.dart)
class ApiClient {
  final Dio _dio;
  final FlutterSecureStorage _secureStorage;

  ApiClient(this._dio, this._secureStorage) {
    _dio.options.baseUrl = 'http://localhost/Livora/public/api/v1';

    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        // Auto-attach token
        final token = await _secureStorage.read(key: 'auth_token');
        if (token != null) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        options.headers['Accept'] = 'application/json';
        return handler.next(options);
      },
      onError: (DioException e, handler) async {
        // Handle 401 Unauthorized
        if (e.response?.statusCode == 401) {
          await _secureStorage.delete(key: 'auth_token');
          // Navigate to Login (handled by AuthController in Flutter)
        }
        return handler.next(e);
      },
    ));
  }
}
```

#### **Logout Flow**

```dart
// 1. Call logout endpoint
await dio.post('/logout'); // Revokes token on server

// 2. Delete local token
await secureStorage.delete(key: 'auth_token');

// 3. Navigate to Login
Navigator.of(context).pushAndRemoveUntil(
  MaterialPageRoute(builder: (_) => LoginScreen()),
  (route) => false,
);
```

### 2.3 Error Handling

| Status Code | Meaning                      | Action                         |
| ----------- | ---------------------------- | ------------------------------ |
| 401         | Unauthorized / Invalid Token | Auto-logout, redirect to Login |
| 403         | Forbidden (wrong role)       | Show error message             |
| 422         | Validation Error             | Show field errors              |
| 500         | Server Error                 | Show generic error             |

---

## 3. 📦 Data Models

### 3.1 User Model

```dart
class User {
  final int id;
  final String name;
  final String email;
  final String role; // 'tenant' | 'owner'
  final String? phone;
  final String? address;
  final String? avatar;
  final DateTime? emailVerifiedAt;
  final DateTime createdAt;
  final DateTime updatedAt;

  User({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
    this.phone,
    this.address,
    this.avatar,
    this.emailVerifiedAt,
    required this.createdAt,
    required this.updatedAt,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      role: json['role'],
      phone: json['phone'],
      address: json['address'],
      avatar: json['avatar'],
      emailVerifiedAt: json['email_verified_at'] != null
          ? DateTime.parse(json['email_verified_at'])
          : null,
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
    );
  }

  bool get isTenant => role == 'tenant';
  bool get isOwner => role == 'owner';
}
```

---

### 3.2 BoardingHouse (Kost) Model

```dart
class BoardingHouse {
  final int id;
  final String name;
  final String slug;
  final String address;
  final String city;
  final String? description;
  final double? latitude;
  final double? longitude;
  final PriceRange priceRange;
  final String? thumbnail;
  final List<String> images;
  final bool isActive;
  final bool isVerified;
  final int? roomsCount;
  final int? availableRoomsCount;
  final List<Room>? rooms;
  final Owner? owner;
  final DateTime createdAt;
  final DateTime updatedAt;

  BoardingHouse({
    required this.id,
    required this.name,
    required this.slug,
    required this.address,
    required this.city,
    this.description,
    this.latitude,
    this.longitude,
    required this.priceRange,
    this.thumbnail,
    required this.images,
    required this.isActive,
    required this.isVerified,
    this.roomsCount,
    this.availableRoomsCount,
    this.rooms,
    this.owner,
    required this.createdAt,
    required this.updatedAt,
  });

  factory BoardingHouse.fromJson(Map<String, dynamic> json) {
    return BoardingHouse(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      address: json['address'],
      city: json['city'],
      description: json['description'],
      latitude: json['latitude']?.toDouble(),
      longitude: json['longitude']?.toDouble(),
      priceRange: PriceRange.fromJson(json['price_range']),
      thumbnail: json['thumbnail'],
      images: List<String>.from(json['images'] ?? []),
      isActive: json['is_active'],
      isVerified: json['is_verified'] ?? false,
      roomsCount: json['rooms_count'],
      availableRoomsCount: json['available_rooms_count'],
      rooms: json['rooms'] != null
          ? (json['rooms'] as List).map((r) => Room.fromJson(r)).toList()
          : null,
      owner: json['owner'] != null ? Owner.fromJson(json['owner']) : null,
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
    );
  }
}

class PriceRange {
  final double? start;
  final double? end;
  final String? formatted;

  PriceRange({this.start, this.end, this.formatted});

  factory PriceRange.fromJson(Map<String, dynamic> json) {
    return PriceRange(
      start: json['start']?.toDouble(),
      end: json['end']?.toDouble(),
      formatted: json['formatted'],
    );
  }
}

class Owner {
  final int id;
  final String? name;

  Owner({required this.id, this.name});

  factory Owner.fromJson(Map<String, dynamic> json) {
    return Owner(
      id: json['id'],
      name: json['name'],
    );
  }
}
```

---

### 3.3 Room Model

```dart
class Room {
  final int id;
  final int boardingHouseId;
  final String name;
  final String? description;
  final double price;
  final String priceFormatted;
  final int capacity;
  final double? size;
  final String? sizeFormatted;
  final bool isAvailable;
  final String? thumbnail;
  final List<String> images;
  final List<Facility>? facilities;
  final BoardingHouse? boardingHouse;
  final DateTime createdAt;
  final DateTime updatedAt;

  Room({
    required this.id,
    required this.boardingHouseId,
    required this.name,
    this.description,
    required this.price,
    required this.priceFormatted,
    required this.capacity,
    this.size,
    this.sizeFormatted,
    required this.isAvailable,
    this.thumbnail,
    required this.images,
    this.facilities,
    this.boardingHouse,
    required this.createdAt,
    required this.updatedAt,
  });

  factory Room.fromJson(Map<String, dynamic> json) {
    return Room(
      id: json['id'],
      boardingHouseId: json['boarding_house_id'],
      name: json['name'],
      description: json['description'],
      price: json['price'].toDouble(),
      priceFormatted: json['price_formatted'],
      capacity: json['capacity'],
      size: json['size']?.toDouble(),
      sizeFormatted: json['size_formatted'],
      isAvailable: json['is_available'],
      thumbnail: json['thumbnail'],
      images: List<String>.from(json['images'] ?? []),
      facilities: json['facilities'] != null
          ? (json['facilities'] as List).map((f) => Facility.fromJson(f)).toList()
          : null,
      boardingHouse: json['boarding_house'] != null
          ? BoardingHouse.fromJson(json['boarding_house'])
          : null,
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
    );
  }
}

class Facility {
  final int id;
  final String name;
  final String? icon;
  final String? description;

  Facility({
    required this.id,
    required this.name,
    this.icon,
    this.description,
  });

  factory Facility.fromJson(Map<String, dynamic> json) {
    return Facility(
      id: json['id'],
      name: json['name'],
      icon: json['icon'],
      description: json['description'],
    );
  }
}
```

---

### 3.4 Booking Model

```dart
class Booking {
  final int id;
  final RoomInfo room;
  final BoardingHouseInfo boardingHouse;
  final TenantInfo tenant;
  final DateTime startDate;
  final String startDateFormatted;
  final DateTime endDate;
  final String endDateFormatted;
  final int duration;
  final String durationText;
  final double totalPrice;
  final String totalPriceFormatted;
  final double finalAmount;
  final String finalAmountFormatted;
  final String status;
  final String statusLabel;
  final String statusColor;
  final String? notes;
  final String? tenantIdentityNumber;
  final String? ktpImage;
  final List<Payment>? payments;
  final int? paymentsCount;
  final bool? hasVerifiedPayment;
  final DateTime createdAt;
  final String createdAtFormatted;
  final DateTime updatedAt;

  Booking({
    required this.id,
    required this.room,
    required this.boardingHouse,
    required this.tenant,
    required this.startDate,
    required this.startDateFormatted,
    required this.endDate,
    required this.endDateFormatted,
    required this.duration,
    required this.durationText,
    required this.totalPrice,
    required this.totalPriceFormatted,
    required this.finalAmount,
    required this.finalAmountFormatted,
    required this.status,
    required this.statusLabel,
    required this.statusColor,
    this.notes,
    this.tenantIdentityNumber,
    this.ktpImage,
    this.payments,
    this.paymentsCount,
    this.hasVerifiedPayment,
    required this.createdAt,
    required this.createdAtFormatted,
    required this.updatedAt,
  });

  factory Booking.fromJson(Map<String, dynamic> json) {
    return Booking(
      id: json['id'],
      room: RoomInfo.fromJson(json['room']),
      boardingHouse: BoardingHouseInfo.fromJson(json['boarding_house']),
      tenant: TenantInfo.fromJson(json['tenant']),
      startDate: DateTime.parse(json['start_date']),
      startDateFormatted: json['start_date_formatted'],
      endDate: DateTime.parse(json['end_date']),
      endDateFormatted: json['end_date_formatted'],
      duration: json['duration'],
      durationText: json['duration_text'],
      totalPrice: json['total_price'].toDouble(),
      totalPriceFormatted: json['total_price_formatted'],
      finalAmount: json['final_amount'].toDouble(),
      finalAmountFormatted: json['final_amount_formatted'],
      status: json['status'],
      statusLabel: json['status_label'],
      statusColor: json['status_color'],
      notes: json['notes'],
      tenantIdentityNumber: json['tenant_identity_number'],
      ktpImage: json['ktp_image'],
      payments: json['payments'] != null
          ? (json['payments'] as List).map((p) => Payment.fromJson(p)).toList()
          : null,
      paymentsCount: json['payments_count'],
      hasVerifiedPayment: json['has_verified_payment'],
      createdAt: DateTime.parse(json['created_at']),
      createdAtFormatted: json['created_at_formatted'],
      updatedAt: DateTime.parse(json['updated_at']),
    );
  }

  // Helper getters
  Color get statusColorValue {
    switch (status) {
      case 'pending':
        return const Color(0xFFFFF9C4);
      case 'confirmed':
        return const Color(0xFFBBDEFB);
      case 'active':
        return const Color(0xFFC8E6C9);
      case 'completed':
        return const Color(0xFFE0E0E0);
      case 'cancelled':
        return const Color(0xFFFFCDD2);
      default:
        return const Color(0xFFE0E0E0);
    }
  }

  bool get canCancel => status == 'pending' || status == 'confirmed';
  bool get canPay => status == 'confirmed' && (hasVerifiedPayment == false);
}

class RoomInfo {
  final int id;
  final String name;
  final double? price;

  RoomInfo({required this.id, required this.name, this.price});

  factory RoomInfo.fromJson(Map<String, dynamic> json) {
    return RoomInfo(
      id: json['id'],
      name: json['name'],
      price: json['price']?.toDouble(),
    );
  }
}

class BoardingHouseInfo {
  final int id;
  final String name;
  final String slug;
  final String address;
  final String city;

  BoardingHouseInfo({
    required this.id,
    required this.name,
    required this.slug,
    required this.address,
    required this.city,
  });

  factory BoardingHouseInfo.fromJson(Map<String, dynamic> json) {
    return BoardingHouseInfo(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      address: json['address'],
      city: json['city'],
    );
  }
}

class TenantInfo {
  final int id;
  final String name;
  final String email;
  final String? phone;

  TenantInfo({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
  });

  factory TenantInfo.fromJson(Map<String, dynamic> json) {
    return TenantInfo(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      phone: json['phone'],
    );
  }
}
```

---

### 3.5 Payment Model

```dart
class Payment {
  final int id;
  final double amount;
  final String amountFormatted;
  final String status;
  final String statusLabel;
  final String? proofImage;
  final String? notes;
  final DateTime? verifiedAt;
  final DateTime createdAt;

  Payment({
    required this.id,
    required this.amount,
    required this.amountFormatted,
    required this.status,
    required this.statusLabel,
    this.proofImage,
    this.notes,
    this.verifiedAt,
    required this.createdAt,
  });

  factory Payment.fromJson(Map<String, dynamic> json) {
    return Payment(
      id: json['id'],
      amount: json['amount'].toDouble(),
      amountFormatted: json['amount_formatted'],
      status: json['status'],
      statusLabel: json['status_label'],
      proofImage: json['proof_image'],
      notes: json['notes'],
      verifiedAt: json['verified_at'] != null
          ? DateTime.parse(json['verified_at'])
          : null,
      createdAt: DateTime.parse(json['created_at']),
    );
  }

  bool get isPending => status == 'pending';
  bool get isVerified => status == 'verified';
  bool get isRejected => status == 'rejected';
}
```

---

## 4. 🎨 Design Tokens

### 4.1 Color Palette

```dart
class AppColors {
  // === PRIMARY BRAND COLORS ===
  static const Color primary = Color(0xFFFF6900);           // Orange utama
  static const Color primaryDark = Color(0xFFE55A00);       // Orange gelap
  static const Color secondary = Color(0xFFFF8533);         // Light orange
  static const Color accent = Color(0xFFFFB366);            // Accent orange

  // === BACKGROUND COLORS ===
  static const Color background = Color(0xFFFAFAFA);        // Light gray
  static const Color surface = Color(0xFFFFFFFF);           // Pure white
  static const Color cardBackground = Color(0xFFFFF4E6);    // Light cream

  // === SEMANTIC COLORS ===
  static const Color success = Color(0xFF00C851);           // Green
  static const Color warning = Color(0xFFFFBB33);           // Yellow
  static const Color error = Color(0xFFFF4444);             // Red
  static const Color info = Color(0xFF0066CC);              // Blue

  // === STATUS COLORS ===
  static const Color statusPending = Color(0xFFFFF9C4);     // Yellow light bg
  static const Color statusPendingText = Color(0xFFF57F17); // Yellow dark text
  static const Color statusConfirmed = Color(0xFFBBDEFB);   // Blue light bg
  static const Color statusConfirmedText = Color(0xFF1565C0);
  static const Color statusActive = Color(0xFFC8E6C9);      // Green light bg
  static const Color statusActiveText = Color(0xFF2E7D32);
  static const Color statusCompleted = Color(0xFFE0E0E0);   // Gray light bg
  static const Color statusCompletedText = Color(0xFF616161);
  static const Color statusCancelled = Color(0xFFFFCDD2);   // Red light bg
  static const Color statusCancelledText = Color(0xFFC62828);

  // === TEXT COLORS ===
  static const Color textPrimary = Color(0xFF1A1A1A);       // Dark
  static const Color textSecondary = Color(0xFF757575);     // Gray 600
  static const Color textTertiary = Color(0xFF9E9E9E);      // Gray 500
  static const Color textDisabled = Color(0xFFBDBDBD);      // Gray 400

  // === BORDER & DIVIDER ===
  static const Color border = Color(0xFFE6E6E6);
  static const Color borderLight = Color(0xFFF5F5F5);

  // === GRADIENTS ===
  static const LinearGradient primaryGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [Color(0xFFFF6900), Color(0xFFFF8533)],
  );

  static const LinearGradient heroGradient = LinearGradient(
    begin: Alignment(0.135, -1.0),  // 135deg
    end: Alignment(-0.135, 1.0),
    colors: [
      Color(0xFFFF6900),  // 0%
      Color(0xFFFF8533),  // 50%
      Color(0xFFFFB366),  // 100%
    ],
    stops: [0.0, 0.5, 1.0],
  );
}
```

---

### 4.2 Typography

```dart
import 'package:google_fonts/google_fonts.dart';

class AppTypography {
  // === FONT FAMILY ===
  static const String primaryFont = 'Inter';

  // === TEXT STYLES ===
  static TextStyle displayLarge = GoogleFonts.inter(
    fontSize: 48,
    fontWeight: FontWeight.w800,
    color: AppColors.textPrimary,
    letterSpacing: -0.5,
  );

  static TextStyle displayMedium = GoogleFonts.inter(
    fontSize: 36,
    fontWeight: FontWeight.w700,
    color: AppColors.textPrimary,
  );

  static TextStyle headlineLarge = GoogleFonts.inter(
    fontSize: 28,
    fontWeight: FontWeight.w700,
    color: AppColors.textPrimary,
  );

  static TextStyle headlineMedium = GoogleFonts.inter(
    fontSize: 24,
    fontWeight: FontWeight.w600,
    color: AppColors.textPrimary,
  );

  static TextStyle headlineSmall = GoogleFonts.inter(
    fontSize: 20,
    fontWeight: FontWeight.w600,
    color: AppColors.textPrimary,
  );

  static TextStyle bodyLarge = GoogleFonts.inter(
    fontSize: 16,
    fontWeight: FontWeight.w400,
    color: AppColors.textPrimary,
    height: 1.5,
  );

  static TextStyle bodyMedium = GoogleFonts.inter(
    fontSize: 14,
    fontWeight: FontWeight.w400,
    color: AppColors.textSecondary,
    height: 1.5,
  );

  static TextStyle bodySmall = GoogleFonts.inter(
    fontSize: 12,
    fontWeight: FontWeight.w400,
    color: AppColors.textTertiary,
    height: 1.4,
  );

  static TextStyle labelLarge = GoogleFonts.inter(
    fontSize: 16,
    fontWeight: FontWeight.w600,
    color: AppColors.primary,
    letterSpacing: 0.5,
  );

  static TextStyle labelMedium = GoogleFonts.inter(
    fontSize: 14,
    fontWeight: FontWeight.w500,
    color: AppColors.textPrimary,
  );

  static TextStyle labelSmall = GoogleFonts.inter(
    fontSize: 12,
    fontWeight: FontWeight.w500,
    color: AppColors.textSecondary,
    letterSpacing: 0.5,
  );
}
```

---

### 4.3 Spacing & Border Radius

```dart
class AppSpacing {
  static const double xs = 4.0;
  static const double sm = 8.0;
  static const double md = 16.0;
  static const double lg = 24.0;
  static const double xl = 32.0;
  static const double xxl = 48.0;
}

class AppBorderRadius {
  static const double small = 8.0;
  static const double medium = 12.0;
  static const double large = 16.0;
  static const double extraLarge = 24.0;
  static const double circle = 999.0;

  // === SPECIFIC USE CASES ===
  static BorderRadius button = BorderRadius.circular(medium);
  static BorderRadius card = BorderRadius.circular(large);
  static BorderRadius modal = BorderRadius.circular(20.0);
  static BorderRadius hero = BorderRadius.circular(extraLarge);
  static BorderRadius circleRadius = BorderRadius.circular(circle);
}
```

---

### 4.4 Shadows

```dart
class AppShadows {
  static const BoxShadow card = BoxShadow(
    color: Color(0x1A000000),  // rgba(0,0,0,0.1)
    offset: Offset(0, 2),
    blurRadius: 8,
    spreadRadius: 0,
  );

  static const BoxShadow cardMd = BoxShadow(
    color: Color(0x1A000000),
    offset: Offset(0, 4),
    blurRadius: 16,
    spreadRadius: 0,
  );

  static const BoxShadow cardLg = BoxShadow(
    color: Color(0x1F000000),  // rgba(0,0,0,0.12)
    offset: Offset(0, 8),
    blurRadius: 32,
    spreadRadius: 0,
  );

  static const BoxShadow primaryGlow = BoxShadow(
    color: Color(0x4DFF6900),  // rgba(255,105,0,0.3)
    offset: Offset(0, 8),
    blurRadius: 20,
    spreadRadius: 0,
  );

  static List<BoxShadow> get cardShadow => [card];
  static List<BoxShadow> get cardShadowMd => [cardMd];
  static List<BoxShadow> get cardShadowLg => [cardLg];
  static List<BoxShadow> get buttonGlow => [primaryGlow];
}
```

---

## 5. 🎯 Feature Scope

### 5.1 TENANT Features (Priority)

#### ✅ **Phase 1: Core Features**

1. **Authentication**

    - Login with email & password
    - Register as Tenant
    - Logout
    - Remember me (auto-login)

2. **Property Browsing**

    - Browse all active properties (list view)
    - Search by name/city/address
    - Filter by city
    - Filter by price range
    - Sort by price/rating/newest
    - View property detail with gallery
    - View room list in property
    - View room detail with facilities

3. **Booking Management**

    - Create new booking:
        - Select room
        - Choose start date (date picker)
        - Select duration (1-12 months)
        - Input KTP number (16 digits)
        - Upload KTP photo (camera/gallery)
        - Add optional notes
        - Preview total price
    - View booking history (list)
    - Filter bookings by status
    - View booking detail
    - Cancel booking (if status = pending/confirmed)

4. **Payment Management**

    - View payment list
    - Submit payment proof:
        - Upload bukti transfer (camera/gallery)
        - Add optional notes
    - View payment status
    - View payment detail

5. **Profile**
    - View profile
    - Edit profile (name, phone, address)
    - Change password

#### ⏳ **Phase 2: Enhancement (Future)**

-   Property favorites/wishlist
-   Rating & review system
-   In-app chat with owner
-   Payment history export (PDF)
-   Push notifications (Firebase)

---

### 5.2 OWNER (Mitra) Features (Priority)

#### ✅ **Phase 1: Core Features**

1. **Authentication**

    - Login with email & password
    - Register as Owner
    - Logout

2. **Dashboard**

    - View statistics:
        - Total properties
        - Total rooms
        - Available/occupied rooms
        - Total bookings by status
        - Pending payments count
        - Total revenue
        - Revenue this month
    - View recent bookings (last 5)
    - View pending payments (need verification)

3. **Property Management**

    - View owned properties list
    - View property detail
    - ❌ **SKIP:** Create/edit/delete property (use web admin)

4. **Booking Management**

    - View all bookings from owned properties
    - Filter by status
    - Filter by property
    - View booking detail with tenant info
    - View KTP photo
    - ❌ **Manual confirm/reject:** Use web admin (auto-confirm on payment verification)

5. **Payment Verification**

    - View pending payments list
    - View payment proof image (zoom/pan)
    - Verify payment (approve)
    - Reject payment with reason
    - View verified payments history

6. **Profile**
    - View profile
    - Edit profile
    - Change password

#### ⏳ **Phase 2: Enhancement (Future)**

-   Property CRUD (create/edit/delete)
-   Room CRUD
-   Revenue analytics (charts)
-   Export reports (PDF/Excel)
-   Push notifications (Firebase)

---

### 5.3 EXCLUDED Features (Not in Scope)

❌ **Admin Dashboard** - Use web version only  
❌ **Ticket System** - Future development  
❌ **Push Notifications (Firebase)** - Future development  
❌ **Property Creation** - Use web admin  
❌ **Room Management** - Use web admin  
❌ **Payment Gateway Integration** - Manual bank transfer only

---

## 6. 📱 Native Features (Required)

### 6.1 Camera & Image Picker

**Usage:**

-   Upload KTP photo (Booking)
-   Upload payment proof (Payment)
-   Upload profile avatar (Profile)

**Implementation:**

```dart
// pubspec.yaml
dependencies:
  image_picker: ^1.0.4

// Usage
import 'package:image_picker/image_picker.dart';

final ImagePicker picker = ImagePicker();

// Pick from camera
final XFile? photo = await picker.pickImage(
  source: ImageSource.camera,
  maxWidth: 1920,
  maxHeight: 1080,
  imageQuality: 85,
);

// Pick from gallery
final XFile? image = await picker.pickImage(
  source: ImageSource.gallery,
  maxWidth: 1920,
  maxHeight: 1080,
  imageQuality: 85,
);
```

**Permissions Required:**

-   **Android:** `CAMERA`, `READ_EXTERNAL_STORAGE`, `WRITE_EXTERNAL_STORAGE`
-   **iOS:** `NSCameraUsageDescription`, `NSPhotoLibraryUsageDescription`

---

### 6.2 Location & Maps

**Usage:**

-   Display property location on map (Property Detail)
-   Get directions to property (open Google Maps)
-   ❌ **NOT NEEDED:** Get current location, location tracking

**Implementation:**

```dart
// pubspec.yaml
dependencies:
  google_maps_flutter: ^2.5.0
  url_launcher: ^6.2.1

// Display map
GoogleMap(
  initialCameraPosition: CameraPosition(
    target: LatLng(property.latitude, property.longitude),
    zoom: 15,
  ),
  markers: {
    Marker(
      markerId: MarkerId('property'),
      position: LatLng(property.latitude, property.longitude),
      infoWindow: InfoWindow(title: property.name),
    ),
  },
);

// Open Google Maps for directions
final url = 'https://www.google.com/maps/search/?api=1&query=${property.latitude},${property.longitude}';
if (await canLaunchUrl(Uri.parse(url))) {
  await launchUrl(Uri.parse(url), mode: LaunchMode.externalApplication);
}
```

**Permissions Required:**

-   **Android:** `INTERNET`, `ACCESS_FINE_LOCATION` (optional, for "My Location" button)
-   **iOS:** `NSLocationWhenInUseUsageDescription` (optional)

---

### 6.3 Secure Storage

**Usage:**

-   Store auth token
-   Store user credentials (remember me)

**Implementation:**

```dart
// Already implemented in lib/src/services/api_client.dart
dependencies:
  flutter_secure_storage: ^9.0.0

const storage = FlutterSecureStorage();

// Write
await storage.write(key: 'auth_token', value: token);

// Read
final token = await storage.read(key: 'auth_token');

// Delete
await storage.delete(key: 'auth_token');
```

---

### 6.4 Image Caching

**Usage:**

-   Cache property images
-   Cache room images
-   Improve performance

**Implementation:**

```dart
// pubspec.yaml
dependencies:
  cached_network_image: ^3.3.0

// Usage
CachedNetworkImage(
  imageUrl: property.thumbnail,
  placeholder: (context, url) => CircularProgressIndicator(),
  errorWidget: (context, url, error) => Icon(Icons.error),
  fit: BoxFit.cover,
)
```

---

## 7. 🚀 Future Development (NOT in Current Scope)

### 7.1 Firebase Push Notifications

**Planned Features:**

-   Booking confirmation notification (Tenant)
-   Payment verification notification (Tenant)
-   New booking notification (Owner)
-   Payment submission notification (Owner)

**Requirements:**

-   Firebase Cloud Messaging setup
-   Backend endpoint to send notifications
-   Token registration on login

---

### 7.2 Advanced Features

-   **In-App Chat:** Real-time chat between tenant & owner
-   **Payment Gateway:** Midtrans/Xendit integration
-   **Review System:** Tenant can review property after checkout
-   **Wishlist:** Save favorite properties
-   **Property Comparison:** Compare multiple properties
-   **Analytics Dashboard:** Advanced charts for owner

---

## 8. 📦 Required Dependencies

```yaml
# pubspec.yaml
name: livora_mobile
description: Livora Kost Mobile App for Tenant & Owner
publish_to: "none"
version: 1.0.0+1

environment:
    sdk: ">=3.0.0 <4.0.0"

dependencies:
    flutter:
        sdk: flutter

    # State Management
    flutter_riverpod: ^2.4.9

    # HTTP Client
    dio: ^5.4.0

    # Secure Storage
    flutter_secure_storage: ^9.0.0

    # UI Components
    google_fonts: ^6.1.0
    cached_network_image: ^3.3.0
    flutter_svg: ^2.0.9

    # Native Features
    image_picker: ^1.0.4
    google_maps_flutter: ^2.5.0
    url_launcher: ^6.2.1

    # Utilities
    intl: ^0.18.1

dev_dependencies:
    flutter_test:
        sdk: flutter
    flutter_lints: ^3.0.0
```

---

## 9. 📂 Recommended Project Structure

```
lib/
├── main.dart
├── app/
│   ├── theme/
│   │   ├── app_colors.dart
│   │   ├── app_typography.dart
│   │   ├── app_spacing.dart
│   │   ├── app_shadows.dart
│   │   └── app_theme.dart
│   └── routes/
│       └── app_routes.dart
├── src/
│   ├── services/
│   │   ├── api_client.dart (EXISTING)
│   │   └── storage_service.dart
│   ├── features/
│   │   ├── auth/
│   │   │   ├── data/
│   │   │   │   └── auth_repository.dart (EXISTING)
│   │   │   ├── domain/
│   │   │   │   └── user.dart (EXISTING)
│   │   │   ├── presentation/
│   │   │   │   ├── login_screen.dart (EXISTING)
│   │   │   │   ├── register_screen.dart
│   │   │   │   └── auth_controller.dart (EXISTING)
│   │   ├── property/
│   │   │   ├── data/
│   │   │   │   └── property_repository.dart (EXISTING)
│   │   │   ├── domain/
│   │   │   │   ├── property.dart (EXISTING)
│   │   │   │   └── room.dart
│   │   │   └── presentation/
│   │   │       ├── property_list_screen.dart
│   │   │       ├── property_detail_screen.dart
│   │   │       └── room_detail_screen.dart
│   │   ├── booking/
│   │   │   ├── data/
│   │   │   │   └── booking_repository.dart (EXISTING)
│   │   │   ├── domain/
│   │   │   │   └── booking.dart (EXISTING)
│   │   │   └── presentation/
│   │   │       ├── booking_list_screen.dart (EXISTING)
│   │   │       ├── booking_detail_screen.dart
│   │   │       ├── create_booking_screen.dart
│   │   │       └── booking_controller.dart
│   │   ├── payment/
│   │   │   ├── data/
│   │   │   │   └── payment_repository.dart
│   │   │   ├── domain/
│   │   │   │   └── payment.dart
│   │   │   └── presentation/
│   │   │       ├── payment_list_screen.dart
│   │   │       ├── payment_detail_screen.dart
│   │   │       └── submit_payment_screen.dart
│   │   ├── tenant/
│   │   │   └── dashboard/
│   │   │       └── presentation/
│   │   │           └── tenant_dashboard_screen.dart (EXISTING)
│   │   └── owner/
│   │       ├── data/
│   │       │   └── owner_repository.dart
│   │       └── presentation/
│   │           ├── owner_dashboard_screen.dart
│   │           ├── owner_bookings_screen.dart
│   │           └── payment_verification_screen.dart
│   └── widgets/
│       ├── app_button.dart
│       ├── app_text_field.dart
│       ├── property_card.dart
│       ├── room_card.dart
│       ├── booking_card.dart
│       └── status_badge.dart
```

---

## 10. ✅ Implementation Checklist

### Phase 1: Core Setup (Week 1)

-   [ ] Setup project structure
-   [ ] Implement design tokens (colors, typography, spacing)
-   [ ] Create base widgets (button, text field, card)
-   [ ] Setup Riverpod providers
-   [ ] Setup routing

### Phase 2: Authentication (Week 1-2)

-   [ ] Login screen UI
-   [ ] Register screen UI
-   [ ] Auth API integration
-   [ ] Token management
-   [ ] Auto-login (remember me)
-   [ ] Logout functionality

### Phase 3: Tenant Features (Week 2-4)

-   [ ] Property browsing (list + search)
-   [ ] Property detail with map
-   [ ] Room detail
-   [ ] Create booking flow
-   [ ] Booking list & detail
-   [ ] Submit payment
-   [ ] Tenant dashboard

### Phase 4: Owner Features (Week 4-5)

-   [ ] Owner dashboard with statistics
-   [ ] Owner bookings list
-   [ ] Payment verification flow
-   [ ] View payment proof (image viewer)

### Phase 5: Polish & Testing (Week 5-6)

-   [ ] Error handling
-   [ ] Loading states
-   [ ] Empty states
-   [ ] Form validation
-   [ ] Image optimization
-   [ ] Performance testing
-   [ ] UAT testing

---

## 11. 🔗 Important Links & Resources

-   **Backend API Base URL:** `http://localhost/Livora/public/api/v1`
-   **Storage URL:** `http://localhost/storage/`
-   **API Documentation:** This document (Section 1)
-   **Laravel Project:** `c:\laragon\www\Livora`
-   **Existing Flutter Code:** `c:\laragon\www\Livora\livora_mobile`

---

## 12. 📝 Notes & Best Practices

1. **Always use Sanctum token** in Authorization header for protected routes
2. **Handle 401 errors** by auto-logout and redirect to login
3. **Validate forms** on client-side before API call to reduce errors
4. **Cache images** using CachedNetworkImage for better performance
5. **Compress images** before upload (max 2MB, quality 85%)
6. **Use optimistic updates** for better UX (show loading state)
7. **Handle offline mode** gracefully with error messages
8. **Test on real devices** for camera & location features
9. **Follow Material Design 3** guidelines for consistency
10. **Use Riverpod** for state management (already implemented)

---

## 13. 🎯 Success Criteria

### Tenant App

-   ✅ Can register and login
-   ✅ Can browse and search properties
-   ✅ Can view property detail with map
-   ✅ Can create booking with KTP upload
-   ✅ Can view booking history
-   ✅ Can submit payment proof
-   ✅ Can cancel booking

### Owner App

-   ✅ Can login as owner
-   ✅ Can view dashboard statistics
-   ✅ Can view all bookings from owned properties
-   ✅ Can verify payment with image preview
-   ✅ Can reject payment with reason

### Technical

-   ✅ 100% API integration complete
-   ✅ Token-based authentication working
-   ✅ Image upload (camera + gallery) working
-   ✅ Google Maps integration working
-   ✅ Responsive UI on various screen sizes
-   ✅ Smooth navigation and animations
-   ✅ Error handling and validation

---

**END OF SPECIFICATION**

_This document should be used as the single source of truth for building the Livora Mobile App with Flutter. All features, APIs, models, and design tokens are defined here._
