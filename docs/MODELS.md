# LIVORA - Models Documentation

Dokumentasi lengkap untuk semua Eloquent Models beserta field, relationship, dan method yang digunakan.

---

## 1. User Model

**File:** `app/Models/User.php`  
**Table:** `users`

### Fields

| Field               | Type        | Nullable | Default  | Description                     |
| ------------------- | ----------- | -------- | -------- | ------------------------------- |
| `id`                | bigint      | No       | AUTO     | Primary key                     |
| `name`              | string(255) | No       | -        | Nama lengkap user               |
| `email`             | string(255) | No       | -        | Email (unique)                  |
| `email_verified_at` | timestamp   | Yes      | NULL     | Waktu verifikasi email          |
| `password`          | string(255) | No       | -        | Password (hashed)               |
| `role`              | enum        | No       | 'tenant' | Role user: admin, owner, tenant |
| `phone`             | string(255) | Yes      | NULL     | Nomor telepon                   |
| `address`           | text        | Yes      | NULL     | Alamat lengkap                  |
| `date_of_birth`     | date        | Yes      | NULL     | Tanggal lahir                   |
| `gender`            | string      | Yes      | NULL     | Jenis kelamin                   |
| `is_active`         | boolean     | No       | true     | Status aktif user               |
| `avatar`            | string      | Yes      | NULL     | Path foto profil                |
| `remember_token`    | string(100) | Yes      | NULL     | Remember token                  |
| `created_at`        | timestamp   | Yes      | CURRENT  | Waktu dibuat                    |
| `updated_at`        | timestamp   | Yes      | CURRENT  | Waktu diupdate                  |

### Relationships

```php
// Has Many
public function boardingHouses(): HasMany
  → Returns: Collection<BoardingHouse>
  → Relation: user_id → boarding_houses

public function bookings(): HasMany
  → Returns: Collection<Booking>
  → Relation: user_id → bookings

public function tickets(): HasMany
  → Returns: Collection<Ticket>
  → Relation: user_id → tickets

public function notifications(): HasMany
  → Returns: Collection<Notification>
  → Relation: user_id → notifications (ordered by created_at desc)
```

### Methods

```php
// Role Check Methods
isAdmin(): bool
  → Returns true if role === 'admin'

isOwner(): bool
  → Returns true if role === 'owner'

isTenant(): bool
  → Returns true if role === 'tenant'

// Notification Methods
unreadNotifications()
  → Returns: Query Builder (notifications with read_at = NULL)

getUnreadNotificationsCountAttribute(): int
  → Returns: Count of unread notifications
```

### Casts

-   `email_verified_at` → datetime
-   `password` → hashed
-   `date_of_birth` → date
-   `is_active` → boolean

### Fillable Fields

`name`, `email`, `password`, `role`, `phone`, `address`, `date_of_birth`, `gender`, `is_active`, `avatar`, `email_verified_at`

---

## 2. BoardingHouse Model

**File:** `app/Models/BoardingHouse.php`  
**Table:** `boarding_houses`

### Fields

| Field               | Type          | Nullable | Default | Description                  |
| ------------------- | ------------- | -------- | ------- | ---------------------------- |
| `id`                | bigint        | No       | AUTO    | Primary key                  |
| `user_id`           | bigint        | No       | -       | Foreign key ke users (owner) |
| `name`              | string(255)   | No       | -       | Nama kost                    |
| `slug`              | string(255)   | No       | -       | URL slug (unique)            |
| `address`           | text          | No       | -       | Alamat lengkap               |
| `city`              | string(255)   | No       | -       | Kota                         |
| `description`       | text          | Yes      | NULL    | Deskripsi kost               |
| `latitude`          | decimal(10,7) | Yes      | NULL    | Koordinat latitude           |
| `longitude`         | decimal(10,7) | Yes      | NULL    | Koordinat longitude          |
| `images`            | json          | Yes      | NULL    | Array path gambar            |
| `is_active`         | boolean       | No       | true    | Status aktif                 |
| `is_verified`       | boolean       | No       | false   | Status verifikasi admin      |
| `price_range_start` | decimal(10,2) | Yes      | NULL    | Harga minimum kamar          |
| `price_range_end`   | decimal(10,2) | Yes      | NULL    | Harga maksimum kamar         |
| `created_at`        | timestamp     | Yes      | CURRENT | Waktu dibuat                 |
| `updated_at`        | timestamp     | Yes      | CURRENT | Waktu diupdate               |

### Relationships

```php
// Belongs To
public function owner(): BelongsTo
  → Returns: User
  → Foreign Key: user_id

public function user(): BelongsTo
  → Returns: User (alias untuk owner)
  → Foreign Key: user_id

// Has Many
public function rooms(): HasMany
  → Returns: Collection<Room>
  → Relation: boarding_house_id → rooms

public function bookings(): HasMany
  → Returns: Collection<Booking>
  → Through: rooms → bookings

public function tickets(): HasMany
  → Returns: Collection<Ticket>
  → Through: rooms → tickets
```

### Methods

```php
getTotalBookingsCount(): int
  → Returns: Total semua booking di kost ini

getActiveBookingsCount(): int
  → Returns: Booking dengan status 'checked_in'

getPendingBookingsCount(): int
  → Returns: Booking dengan status 'pending'

getMonthlyRevenue(): float
  → Returns: Total pendapatan bulan ini dari booking checked_in/checked_out
```

### Casts

-   `images` → array
-   `is_active` → boolean
-   `is_verified` → boolean
-   `latitude` → decimal:7
-   `longitude` → decimal:7
-   `price_range_start` → decimal:2
-   `price_range_end` → decimal:2

### Fillable Fields

`user_id`, `name`, `slug`, `address`, `city`, `description`, `latitude`, `longitude`, `images`, `is_active`, `is_verified`, `price_range_start`, `price_range_end`

---

## 3. Room Model

**File:** `app/Models/Room.php`  
**Table:** `rooms`

### Fields

| Field               | Type          | Nullable | Default | Description                    |
| ------------------- | ------------- | -------- | ------- | ------------------------------ |
| `id`                | bigint        | No       | AUTO    | Primary key                    |
| `boarding_house_id` | bigint        | No       | -       | Foreign key ke boarding_houses |
| `name`              | string(255)   | No       | -       | Nama/nomor kamar               |
| `description`       | text          | Yes      | NULL    | Deskripsi kamar                |
| `price`             | decimal(10,2) | No       | -       | Harga per bulan                |
| `capacity`          | integer       | No       | 1       | Kapasitas orang                |
| `size`              | decimal(5,2)  | Yes      | NULL    | Ukuran kamar (m²)              |
| `images`            | json          | Yes      | NULL    | Array path gambar              |
| `is_available`      | boolean       | No       | true    | Status ketersediaan            |
| `created_at`        | timestamp     | Yes      | CURRENT | Waktu dibuat                   |
| `updated_at`        | timestamp     | Yes      | CURRENT | Waktu diupdate                 |

### Relationships

```php
// Belongs To
public function boardingHouse(): BelongsTo
  → Returns: BoardingHouse
  → Foreign Key: boarding_house_id

// Has Many
public function bookings(): HasMany
  → Returns: Collection<Booking>
  → Relation: room_id → bookings

public function tickets(): HasMany
  → Returns: Collection<Ticket>
  → Relation: room_id → tickets

// Many to Many
public function facilities(): BelongsToMany
  → Returns: Collection<Facility>
  → Pivot Table: facility_room
```

### Methods

```php
isAvailableForBooking($checkIn, $checkOut): bool
  → Parameters:
    - $checkIn: date
    - $checkOut: date
  → Returns: true jika kamar tersedia untuk periode tersebut
  → Logic: Cek overlap booking dengan status confirmed/checked_in

getActiveBooking(): ?Booking
  → Returns: Booking yang sedang aktif (status = checked_in) atau null

getNextBooking(): ?Booking
  → Returns: Booking berikutnya yang confirmed atau null
  → Sorted by: check_in_date ASC
```

### Casts

-   `images` → array
-   `is_available` → boolean
-   `price` → decimal:2
-   `size` → decimal:2

### Fillable Fields

`boarding_house_id`, `name`, `description`, `price`, `capacity`, `size`, `images`, `is_available`

---

## 4. Booking Model

**File:** `app/Models/Booking.php`  
**Table:** `bookings`

### Fields

| Field          | Type          | Nullable | Default   | Description                                              |
| -------------- | ------------- | -------- | --------- | -------------------------------------------------------- |
| `id`           | bigint        | No       | AUTO      | Primary key                                              |
| `user_id`      | bigint        | No       | -         | Foreign key ke users (tenant)                            |
| `room_id`      | bigint        | No       | -         | Foreign key ke rooms                                     |
| `start_date`   | date          | No       | -         | Tanggal mulai sewa                                       |
| `duration`     | integer       | No       | -         | Durasi sewa (bulan)                                      |
| `end_date`     | date          | No       | -         | Tanggal selesai sewa                                     |
| `total_price`  | decimal(10,2) | No       | -         | Harga total sebelum diskon                               |
| `final_amount` | decimal(10,2) | Yes      | NULL      | Harga final setelah diskon                               |
| `status`       | enum          | No       | 'pending' | Status: pending, confirmed, active, completed, cancelled |
| `notes`        | text          | Yes      | NULL      | Catatan booking                                          |
| `created_at`   | timestamp     | Yes      | CURRENT   | Waktu dibuat                                             |
| `updated_at`   | timestamp     | Yes      | CURRENT   | Waktu diupdate                                           |

### Status Constants

```php
const STATUS_PENDING = 'pending';      // Menunggu konfirmasi
const STATUS_CONFIRMED = 'confirmed';  // Dikonfirmasi owner
const STATUS_ACTIVE = 'active';        // Sedang menginap
const STATUS_COMPLETED = 'completed';  // Selesai
const STATUS_CANCELLED = 'cancelled';  // Dibatalkan
```

### Relationships

```php
// Belongs To
public function user(): BelongsTo
  → Returns: User
  → Foreign Key: user_id

public function tenant(): BelongsTo
  → Returns: User (alias untuk user)
  → Foreign Key: user_id

public function room(): BelongsTo
  → Returns: Room
  → Foreign Key: room_id

// Has Many
public function payments(): HasMany
  → Returns: Collection<Payment>
  → Relation: booking_id → payments

public function tickets(): HasMany
  → Returns: Collection<Ticket>
  → Relation: room_id (indirect)

// Accessors
public function getBoardingHouseAttribute()
  → Returns: BoardingHouse melalui room relationship
```

### Methods - Status Check

```php
isPending(): bool
  → Returns: true jika status = pending

isConfirmed(): bool
  → Returns: true jika status = confirmed

isActive(): bool
  → Returns: true jika status = active

isCompleted(): bool
  → Returns: true jika status = completed

isCancelled(): bool
  → Returns: true jika status = cancelled
```

### Methods - Action Check

```php
canBeConfirmed(): bool
  → Returns: true jika status = pending

canBeCheckedIn(): bool
  → Returns: true jika status = confirmed DAN start_date = hari ini

canBeCheckedOut(): bool
  → Returns: true jika status = active

canBeCancelled(): bool
  → Returns: true jika status = pending atau confirmed

canBeEdited(): bool
  → Returns: true jika status = pending atau confirmed

canEditDates(): bool
  → Returns: true jika status = pending atau confirmed
```

### Methods - Helper

```php
getDurationInDays(): int
  → Returns: Selisih hari antara start_date dan end_date

getRemainingDays(): int
  → Returns: Sisa hari menginap (jika active), 0 jika tidak active

getBookingTypeLabel(): string
  → Returns:
    - 'Sewa Tahunan' jika duration >= 12 bulan
    - 'Sewa Bulanan' jika duration >= 3 bulan
    - 'Sewa Harian' jika duration < 3 bulan

getBookingCodeAttribute(): string
  → Returns: Kode booking format 'BK-000001' (padded 6 digit)
```

### Accessors

```php
getStatusLabelAttribute(): string
  → Returns: Label status dalam Bahasa Indonesia

getStatusColorAttribute(): string
  → Returns: Nama warna untuk badge (yellow, blue, green, gray, red)
```

### Scopes

```php
scopePending($query)
  → Filter: status = pending

scopeConfirmed($query)
  → Filter: status = confirmed

scopeActive($query)
  → Filter: status = active

scopeCompleted($query)
  → Filter: status = completed

scopeCancelled($query)
  → Filter: status = cancelled
```

### Casts

-   `start_date` → date
-   `end_date` → date
-   `total_price` → decimal:2
-   `final_amount` → decimal:2
-   `duration` → integer

### Fillable Fields

`user_id`, `room_id`, `start_date`, `duration`, `end_date`, `total_price`, `final_amount`, `status`, `notes`

---

## 5. Payment Model

**File:** `app/Models/Payment.php`  
**Table:** `payments`

### Fields

| Field         | Type          | Nullable | Default   | Description                         |
| ------------- | ------------- | -------- | --------- | ----------------------------------- |
| `id`          | bigint        | No       | AUTO      | Primary key                         |
| `booking_id`  | bigint        | No       | -         | Foreign key ke bookings             |
| `amount`      | decimal(10,2) | No       | -         | Jumlah pembayaran                   |
| `proof_image` | string(255)   | Yes      | NULL      | Path bukti transfer                 |
| `status`      | enum          | No       | 'pending' | Status: pending, verified, rejected |
| `notes`       | text          | Yes      | NULL      | Catatan payment                     |
| `verified_at` | timestamp     | Yes      | NULL      | Waktu diverifikasi                  |
| `created_at`  | timestamp     | Yes      | CURRENT   | Waktu dibuat                        |
| `updated_at`  | timestamp     | Yes      | CURRENT   | Waktu diupdate                      |

### Status Constants

```php
const STATUS_PENDING = 'pending';    // Menunggu verifikasi
const STATUS_VERIFIED = 'verified';  // Diverifikasi
const STATUS_REJECTED = 'rejected';  // Ditolak
```

### Relationships

```php
// Belongs To
public function booking(): BelongsTo
  → Returns: Booking
  → Foreign Key: booking_id
```

### Scopes

```php
scopePending($query)
  → Filter: status = pending

scopeVerified($query)
  → Filter: status = verified

scopeRejected($query)
  → Filter: status = rejected
```

### Casts

-   `verified_at` → datetime
-   `amount` → decimal:2

### Fillable Fields

`booking_id`, `amount`, `proof_image`, `status`, `notes`, `verified_at`

---

## 6. Ticket Model

**File:** `app/Models/Ticket.php`  
**Table:** `tickets`

### Fields

| Field         | Type        | Nullable | Default  | Description                                 |
| ------------- | ----------- | -------- | -------- | ------------------------------------------- |
| `id`          | bigint      | No       | AUTO     | Primary key                                 |
| `user_id`     | bigint      | No       | -        | Foreign key ke users (tenant)               |
| `room_id`     | bigint      | No       | -        | Foreign key ke rooms                        |
| `subject`     | string(255) | No       | -        | Subjek tiket                                |
| `message`     | text        | No       | -        | Isi pesan                                   |
| `status`      | enum        | No       | 'open'   | Status: open, in_progress, resolved, closed |
| `priority`    | enum        | No       | 'medium' | Prioritas: low, medium, high                |
| `response`    | text        | Yes      | NULL     | Respon dari owner/admin                     |
| `resolved_at` | timestamp   | Yes      | NULL     | Waktu diselesaikan                          |
| `created_at`  | timestamp   | Yes      | CURRENT  | Waktu dibuat                                |
| `updated_at`  | timestamp   | Yes      | CURRENT  | Waktu diupdate                              |

### Relationships

```php
// Belongs To
public function tenant(): BelongsTo
  → Returns: User
  → Foreign Key: user_id

public function room(): BelongsTo
  → Returns: Room
  → Foreign Key: room_id
```

### Casts

-   `resolved_at` → datetime

### Fillable Fields

`user_id`, `room_id`, `subject`, `message`, `status`, `priority`, `response`, `resolved_at`

---

## 7. Notification Model

**File:** `app/Models/Notification.php`  
**Table:** `notifications`

### Fields

| Field           | Type        | Nullable | Default  | Description                  |
| --------------- | ----------- | -------- | -------- | ---------------------------- |
| `id`            | bigint      | No       | AUTO     | Primary key                  |
| `user_id`       | bigint      | No       | -        | Foreign key ke users         |
| `type`          | string(255) | No       | -        | Tipe notifikasi              |
| `title`         | string(255) | No       | -        | Judul notifikasi             |
| `message`       | text        | No       | -        | Isi pesan                    |
| `data`          | json        | Yes      | NULL     | Data tambahan                |
| `read_at`       | timestamp   | Yes      | NULL     | Waktu dibaca                 |
| `is_email_sent` | boolean     | No       | false    | Status email terkirim        |
| `is_push_sent`  | boolean     | No       | false    | Status push notif terkirim   |
| `priority`      | enum        | No       | 'medium' | Prioritas: low, medium, high |
| `action_url`    | string(255) | Yes      | NULL     | URL action                   |
| `created_at`    | timestamp   | Yes      | CURRENT  | Waktu dibuat                 |
| `updated_at`    | timestamp   | Yes      | CURRENT  | Waktu diupdate               |

### Relationships

```php
// Belongs To
public function user(): BelongsTo
  → Returns: User
  → Foreign Key: user_id
```

### Methods

```php
markAsRead(): void
  → Update read_at ke sekarang

isRead(): bool
  → Returns: true jika read_at tidak null

isUnread(): bool
  → Returns: true jika read_at null
```

### Static Factory Methods

```php
static createForUser(User $user, string $type, string $title,
                     string $message, array $data = [],
                     string $priority = 'medium',
                     ?string $actionUrl = null): self
  → Create notifikasi baru untuk user

static bookingCreated(Booking $booking): self
  → Create notifikasi booking dibuat

static paymentVerified(Payment $payment): self
  → Create notifikasi payment diverifikasi

static ticketUpdated(Ticket $ticket): self
  → Create notifikasi ticket diupdate
```

### Accessors

```php
getTimeAgoAttribute(): string
  → Returns: Waktu relatif (e.g., "2 hours ago")

getPriorityColorAttribute(): string
  → Returns: Tailwind class untuk badge priority

getIconAttribute(): string
  → Returns: Icon name berdasarkan notification type
```

### Scopes

```php
scopeUnread($query)
  → Filter: read_at IS NULL

scopeRead($query)
  → Filter: read_at IS NOT NULL

scopeOfType($query, string $type)
  → Filter: type = $type

scopeOfPriority($query, string $priority)
  → Filter: priority = $priority
```

### Casts

-   `data` → array
-   `read_at` → datetime
-   `is_email_sent` → boolean
-   `is_push_sent` → boolean

### Fillable Fields

`user_id`, `type`, `title`, `message`, `data`, `read_at`, `is_email_sent`, `is_push_sent`, `priority`, `action_url`

---

## 8. Facility Model

**File:** `app/Models/Facility.php`  
**Table:** `facilities`

### Fields

| Field         | Type        | Nullable | Default | Description         |
| ------------- | ----------- | -------- | ------- | ------------------- |
| `id`          | bigint      | No       | AUTO    | Primary key         |
| `name`        | string(255) | No       | -       | Nama fasilitas      |
| `icon`        | string(255) | Yes      | NULL    | Icon fasilitas      |
| `description` | text        | Yes      | NULL    | Deskripsi fasilitas |
| `created_at`  | timestamp   | Yes      | CURRENT | Waktu dibuat        |
| `updated_at`  | timestamp   | Yes      | CURRENT | Waktu diupdate      |

### Relationships

```php
// Many to Many (implicit through pivot table)
// Pivot table: facility_room
// Columns: facility_id, room_id
```

---

## Model Relationships Summary

### User (1 to Many)

-   User → BoardingHouse (owner)
-   User → Booking (tenant)
-   User → Ticket (tenant)
-   User → Notification

### BoardingHouse (1 to Many)

-   BoardingHouse → Room

### Room (1 to Many)

-   Room → Booking
-   Room → Ticket

### Room (Many to Many)

-   Room ↔ Facility

### Booking (1 to Many)

-   Booking → Payment

### Indirect Relationships

-   Booking → BoardingHouse (through Room)
-   Booking → Ticket (through Room)
