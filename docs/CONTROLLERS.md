# LIVORA - Controllers Documentation

Dokumentasi lengkap untuk semua Controllers beserta methods, parameters, dan variables yang digunakan.

---

## Public Controllers

### HomeController

**File:** `app/Http/Controllers/HomeController.php`  
**Purpose:** Handle public landing page dan browse property tanpa login

#### Method: `index()`

**Route:** `GET /`  
**Name:** `home`  
**Middleware:** None (public)

**Variables:**

```php
$properties (Collection<BoardingHouse>)
  → Latest 6 properties dengan rooms count dan available_rooms_count
  → Eager loading: rooms relationship

$stats (array)
  → ['total_properties' => int]  // Total boarding houses
  → ['total_rooms' => int]       // Total semua rooms
  → ['available_rooms' => int]   // Rooms dengan is_available = true
```

**Returns:** `view('public.index', compact('properties', 'stats'))`

---

#### Method: `browse(Request $request)`

**Route:** `GET /browse`  
**Name:** `browse`  
**Middleware:** None (public)

**Request Parameters:**

-   `search` (string, optional) - Cari di name, address, atau city
-   `city` (string, optional) - Filter by city
-   `max_price` (decimal, optional) - Filter rooms dengan price <= value
-   `sort` (string, optional) - Sorting: latest, price_low, price_high, name
-   `page` (int, optional) - Pagination page number

**Variables:**

```php
$query (Builder)
  → Query builder untuk BoardingHouse dengan eager loading
  → withCount: rooms, available_rooms_count

$properties (Collection → LengthAwarePaginator)
  → Hasil query yang sudah di-filter dan di-sort
  → Manual pagination: 12 items per page
  → Collection sorting (karena price ada di rooms)

$cities (Collection<string>)
  → Distinct list of cities untuk filter dropdown
  → Sorted alphabetically

$perPage = 12 (int)
  → Items per page

$currentPage (int)
  → Current page dari request

$offset (int)
  → ($currentPage - 1) * $perPage

$paginatedItems (Collection)
  → Slice dari collection dengan offset dan limit
```

**Sorting Logic:**

-   `price_low`: Sort by minimum room price (ASC)
-   `price_high`: Sort by minimum room price (DESC)
-   `name`: Sort by property name (ASC)
-   `latest`: Sort by created_at (DESC) - default

**Returns:** `view('public.browse', compact('properties', 'cities'))`

---

#### Method: `show($id)`

**Route:** `GET /properties/{id}`  
**Name:** `properties.show`  
**Middleware:** None (public)

**Parameters:**

-   `$id` (int) - BoardingHouse ID

**Variables:**

```php
$property (BoardingHouse)
  → Property dengan rooms, user (owner)
  → withCount: rooms, available_rooms_count
  → findOrFail - throw 404 jika tidak ditemukan

$otherProperties (Collection<BoardingHouse>)
  → Max 3 properties dari owner yang sama
  → Exclude property saat ini
  → Eager load: rooms dengan available_rooms_count
```

**Returns:** `view('public.show', compact('property', 'otherProperties'))`

---

#### Method: `about()`

**Route:** `GET /about`  
**Name:** `about`  
**Middleware:** None (public)

**Returns:** `view('public.about')`

---

#### Method: `contact()`

**Route:** `GET /contact`  
**Name:** `contact`  
**Middleware:** None (public)

**Returns:** `view('public.contact')`

---

#### Method: `submitContact(Request $request)`

**Route:** `POST /contact`  
**Name:** `contact.submit`  
**Middleware:** None (public)

**Validation:**

-   `name`: required, string, max:255
-   `email`: required, email, max:255
-   `subject`: required, string, max:255
-   `message`: required, string, max:1000

**Returns:** `back()->with('success', 'Terima kasih! Pesan Anda telah dikirim...')`

---

## Tenant Controllers

### Tenant\DashboardController

**File:** `app/Http/Controllers/Tenant/DashboardController.php`  
**Purpose:** Dashboard utama untuk tenant dengan statistik dan aktivitas

#### Method: `index()`

**Route:** `GET /tenant/dashboard`  
**Name:** `tenant.dashboard`  
**Middleware:** auth, role:tenant

**Variables:**

```php
$user (User)
  → Auth::user() - Current authenticated tenant

$bookings (Collection<Booking>)
  → All bookings milik tenant
  → Eager load: room.boardingHouse, payments, tickets
  → Sorted: latest first

$statistics (array)
  → ['total_bookings' => int]        // Total semua booking
  → ['active_bookings' => int]       // Status = checked_in
  → ['completed_bookings' => int]    // Status = checked_out
  → ['cancelled_bookings' => int]    // Status = cancelled
  → ['total_payments' => int]        // Total payment records
  → ['verified_payments' => int]     // Status = verified
  → ['pending_payments' => int]      // Status = pending
  → ['total_spent' => decimal]       // Sum verified payments
  → ['monthly_spent' => decimal]     // Sum verified bulan ini
  → ['open_tickets' => int]          // Status = open
  → ['resolved_tickets' => int]      // Status = resolved

$recentActivities (Collection<array>)
  → Max 10 aktivitas terbaru (bookings, payments, tickets)
  → Sorted by time (DESC)
  → Each item:
    - type: 'booking', 'payment', 'ticket'
    - icon: string (icon name)
    - title: string
    - description: string
    - time: Carbon datetime
    - status: string
    - link: string (URL)

$activeBooking (?Booking)
  → Booking dengan status = checked_in (current stay)
  → null jika tidak ada

$upcomingBooking (?Booking)
  → Booking confirmed dengan check_in_date >= today
  → Sorted by check_in_date ASC (closest first)
  → null jika tidak ada

$pendingPayments (Collection<Payment>)
  → Max 3 payments dengan status = pending
  → Eager load: booking.room.boardingHouse
  → Sorted: latest first

$openTickets (Collection<Ticket>)
  → Max 3 tickets dengan status = open
  → Eager load: room.boardingHouse
  → Sorted: latest first
```

**Private Methods:**

##### `calculateStatistics($user, $bookings): array`

**Parameters:**

-   `$user` (User)
-   `$bookings` (Collection<Booking>)

**Logic:**

-   Calculate booking stats by status
-   Calculate payment stats (count dan sum amount)
-   Calculate ticket stats by status
-   Use `$currentMonth` = Carbon::now()->startOfMonth()

**Returns:** Array dengan 11 key statistics

---

##### `getRecentActivities($user): Collection`

**Parameters:**

-   `$user` (User)

**Logic:**

1. Ambil 5 recent bookings → map ke activity format
2. Ambil 5 recent payments → map ke activity format
3. Ambil 5 recent tickets → map ke activity format
4. Merge semua collections
5. Sort by time DESC
6. Take 10 items

**Returns:** Collection<array> dengan format activity

---

### Tenant\BookingController

**File:** `app/Http/Controllers/Tenant/BookingController.php`

#### Key Methods:

##### `index()`

**Variables:**

-   `$bookings` (Collection<Booking>) - All tenant bookings dengan room, boardingHouse
-   Filter & pagination support

##### `create()`

**Variables:**

-   `$properties` (Collection<BoardingHouse>) - Available properties
-   `$selectedProperty` - Jika ada query param property_id

##### `store(StoreBookingRequest $request)`

**Variables:**

-   Validated data dari request
-   `$room` (Room) - Selected room
-   `$duration` (int) - Duration in months
-   `$start_date`, `$end_date` (Carbon)
-   `$total_price`, `$final_amount` (decimal)
-   `$booking` (Booking) - Newly created

##### `show($id)`

**Variables:**

-   `$booking` (Booking) - dengan room.boardingHouse, payments
-   `$canCancel` (bool) - Check if booking can be cancelled

##### `cancel($id)`

**Variables:**

-   `$booking` (Booking)
-   Update status to 'cancelled'

---

### Tenant\PaymentController

**File:** `app/Http/Controllers/Tenant/PaymentController.php`

#### Key Methods:

##### `index()`

**Variables:**

-   `$payments` (Collection<Payment>) - All payments milik tenant
-   Eager load: booking.room.boardingHouse

##### `create()`

**Variables:**

-   `$bookings` (Collection<Booking>) - Tenant's bookings untuk pilih booking
-   Filter: status = confirmed atau pending

##### `store(StorePaymentRequest $request)`

**Variables:**

-   `$booking` (Booking)
-   `$proof_image` (string) - Uploaded file path
-   `$amount` (decimal) - From request
-   `$payment` (Payment) - Newly created

##### `show($id)`

**Variables:**

-   `$payment` (Payment) - dengan booking.room.boardingHouse

##### `downloadReceipt($id)`

**Variables:**

-   `$payment` (Payment)
-   Generate receipt view dengan terbilang function
-   Return PDF or view

---

### Tenant\TicketController

**File:** `app/Http/Controllers/Tenant/TicketController.php`

#### Key Methods:

##### `index()`

**Variables:**

-   `$tickets` (Collection<Ticket>) - All tenant tickets
-   Eager load: room.boardingHouse

##### `create()`

**Variables:**

-   `$bookings` (Collection<Booking>) - Tenant's bookings (pending/confirmed)
-   `$rooms` (Collection<Room>) - Rooms dari bookings

##### `store(StoreTicketRequest $request)`

**Variables:**

-   Validated: subject, message, room_id, priority
-   `$ticket` (Ticket) - Newly created

##### `show($id)`

**Variables:**

-   `$ticket` (Ticket) - dengan room.boardingHouse

---

## Mitra (Owner) Controllers

### Mitra\DashboardController

**File:** `app/Http/Controllers/Mitra/DashboardController.php`  
**Purpose:** Dashboard untuk property owner dengan revenue dan statistik

#### Method: `index()`

**Route:** `GET /mitra/dashboard`  
**Name:** `mitra.dashboard`  
**Middleware:** auth, role:owner

**Variables:**

```php
$user (User)
  → Auth::user() - Current owner

$boardingHouses (Collection<BoardingHouse>)
  → All properties owned by user
  → Eager load: rooms

$roomIds (Collection<int>)
  → Pluck all room IDs dari semua boarding houses
  → Used untuk filter bookings

$currentMonth (Carbon)
  → Carbon::now()->startOfMonth()
  → Start date untuk calculate monthly revenue

$lastMonth (Carbon)
  → Carbon::now()->subMonth()->startOfMonth()
  → Start date untuk calculate last month revenue

$monthlyRevenue (decimal)
  → Sum final_amount dari bookings bulan ini
  → Filter: room_id IN roomIds, status = confirmed, created_at >= currentMonth

$lastMonthRevenue (decimal)
  → Sum final_amount bulan lalu
  → Filter: created_at BETWEEN lastMonth AND currentMonth

$totalRevenue (decimal)
  → Sum final_amount sepanjang waktu
  → Filter: room_id IN roomIds, status = confirmed

$revenueGrowth (float)
  → Percentage growth: ((monthly - lastMonth) / lastMonth) * 100
  → 0 jika lastMonthRevenue = 0

$totalRooms (int)
  → Count rooms di semua properties owner

$occupiedRooms (int)
  → Count rooms dengan is_available = false

$activeBookings (int)
  → Count bookings dengan status = checked_in

$pendingTickets (int)
  → Count tickets dengan status = open

$recentBookings (Collection<Booking>)
  → Max 10 bookings terbaru dari properties owner
  → Eager load: user (tenant), room.boardingHouse
  → Sorted by created_at DESC
```

**Returns:** `view('mitra.dashboard', compact(...))`

---

### Mitra\PropertyController

**File:** `app/Http/Controllers/Mitra/PropertyController.php`

#### Key Methods:

##### `index()`

**Variables:**

-   `$properties` (Collection<BoardingHouse>) - Owner's properties
-   `$properties` with rooms_count, bookings_count

##### `create()`

**Returns:** Form view

##### `store(StorePropertyRequest $request)`

**Variables:**

-   Validated data
-   `$images` (array) - Uploaded images paths
-   `$slug` - Generated dari name
-   `$property` (BoardingHouse) - Newly created

##### `show($id)`

**Variables:**

-   `$property` (BoardingHouse) - dengan rooms, bookings stats
-   `$recentBookings` - Latest bookings di property ini

##### `edit($id)`

**Variables:**

-   `$property` (BoardingHouse)

##### `update(UpdatePropertyRequest $request, $id)`

**Variables:**

-   `$property` (BoardingHouse)
-   Updated fields dari request
-   Handle image uploads

##### `destroy($id)`

**Variables:**

-   `$property` (BoardingHouse)
-   Delete cascade: rooms, bookings

---

### Mitra\RoomController

**File:** `app/Http/Controllers/Mitra/RoomController.php`

#### Key Methods:

##### `index($propertyId)`

**Variables:**

-   `$property` (BoardingHouse) - Verify ownership
-   `$rooms` (Collection<Room>) - Rooms in property

##### `create($propertyId)`

**Variables:**

-   `$property` (BoardingHouse)

##### `store(StoreRoomRequest $request, $propertyId)`

**Variables:**

-   `$property` (BoardingHouse)
-   Validated data
-   `$images` (array) - Uploaded
-   `$room` (Room) - Newly created

##### `toggleAvailability($propertyId, $roomId)`

**Variables:**

-   `$room` (Room)
-   Toggle is_available boolean

---

### Mitra\BookingController

**File:** `app/Http/Controllers/Mitra/BookingController.php`

#### Key Methods:

##### `index()`

**Variables:**

-   `$bookings` (Collection<Booking>) - Bookings dari properties owner
-   Filter by status, date range
-   Eager load: tenant, room.boardingHouse

##### `show($id)`

**Variables:**

-   `$booking` (Booking) - Verify ownership via room->boardingHouse
-   Eager load: tenant, room, payments

##### `confirm($id)`

**Variables:**

-   `$booking` (Booking)
-   Update status to 'confirmed'
-   Create notification

##### `checkIn($id)`

**Variables:**

-   `$booking` (Booking)
-   Update status to 'checked_in'
-   Update room is_available to false

##### `checkOut($id)`

**Variables:**

-   `$booking` (Booking)
-   Update status to 'checked_out'
-   Update room is_available to true

##### `cancel($id)`

**Variables:**

-   `$booking` (Booking)
-   Update status to 'cancelled'
-   Update room is_available to true

---

### Mitra\PaymentController

**File:** `app/Http/Controllers/Mitra/PaymentController.php`

#### Key Methods:

##### `index()`

**Variables:**

-   `$payments` (Collection<Payment>) - Payments dari bookings owner
-   Filter by status
-   Eager load: booking.room.boardingHouse, booking.tenant

##### `show($id)`

**Variables:**

-   `$payment` (Payment) - Verify ownership
-   Eager load: booking details

##### `verify($id)`

**Variables:**

-   `$payment` (Payment)
-   Update: status = 'verified', verified_at = now()
-   Update booking status to 'confirmed'
-   Create notification

##### `reject($id)`

**Variables:**

-   `$payment` (Payment)
-   Update status = 'rejected'
-   Create notification

##### `bulkAction(Request $request)`

**Variables:**

-   `$action` (string) - 'verify' or 'reject'
-   `$paymentIds` (array) - Array of payment IDs
-   Loop: process each payment

##### `downloadProof($id)`

**Variables:**

-   `$payment` (Payment)
-   Return file download bukti transfer

##### `downloadReceipt($id)`

**Variables:**

-   `$payment` (Payment)
-   Generate receipt PDF

---

### Mitra\TicketController

**File:** `app/Http/Controllers/Mitra/TicketController.php`

#### Key Methods:

##### `index()`

**Variables:**

-   `$tickets` (Collection<Ticket>) - Tickets dari rooms owner
-   Filter by status, priority
-   Eager load: tenant, room.boardingHouse

##### `show($id)`

**Variables:**

-   `$ticket` (Ticket) - Verify ownership

##### `update(UpdateTicketRequest $request, $id)`

**Variables:**

-   `$ticket` (Ticket)
-   Update response, status
-   If status = resolved: set resolved_at

##### `updateStatus($id, Request $request)`

**Variables:**

-   `$ticket` (Ticket)
-   `$status` (string) - From request
-   Update status

##### `updatePriority($id, Request $request)`

**Variables:**

-   `$ticket` (Ticket)
-   `$priority` (string) - From request
-   Update priority

---

### Mitra\ReportController

**File:** `app/Http/Controllers/Mitra/ReportController.php`

#### Key Methods:

##### `index()`

**Variables:**

-   Dashboard report summary

##### `revenue(Request $request)`

**Variables:**

-   `$startDate`, `$endDate` (Carbon) - Date range from request
-   `$bookings` (Collection<Booking>) - Filtered by date
-   `$totalRevenue` (decimal) - Sum final_amount
-   `$chartData` (array) - Daily/monthly revenue data
-   `$topProperties` (Collection) - Properties dengan revenue tertinggi

##### `occupancy(Request $request)`

**Variables:**

-   `$properties` (Collection<BoardingHouse>) - Owner's properties
-   Calculate occupancy rate per property
-   `$averageOccupancy` (float) - Overall average
-   `$chartData` (array) - Occupancy over time

---

## Admin Controllers

### Admin\DashboardController

**File:** `app/Http/Controllers/Admin/DashboardController.php`

#### Method: `index()`

**Variables:**

-   `$totalUsers` (int) - Count all users
-   `$totalProperties` (int) - Count all boarding houses
-   `$totalBookings` (int) - Count all bookings
-   `$totalRevenue` (decimal) - Sum all verified payments
-   `$recentUsers` (Collection<User>) - Latest 5 users
-   `$recentBookings` (Collection<Booking>) - Latest 10 bookings
-   `$pendingVerifications` (int) - Count pending payments
-   `$openTickets` (int) - Count open tickets

---

### Admin\UserController

**File:** `app/Http/Controllers/Admin/UserController.php`

#### Key Methods:

##### `index(Request $request)`

**Variables:**

-   `$users` (LengthAwarePaginator<User>)
-   Filter: role, is_active, search
-   Paginate: 20 per page

##### `create()`

**Returns:** Form view

##### `store(Request $request)`

**Variables:**

-   Validated user data
-   `$user` (User) - Newly created
-   Hash password

##### `show($id)`

**Variables:**

-   `$user` (User)
-   `$stats` (array) - User statistics (bookings, payments, etc)

##### `edit($id)`

**Variables:**

-   `$user` (User)

##### `update(Request $request, $id)`

**Variables:**

-   `$user` (User)
-   Updated fields

##### `destroy($id)`

**Variables:**

-   `$user` (User)
-   Soft delete or hard delete

##### `activate($id)`

**Variables:**

-   `$user` (User)
-   Set is_active = true

##### `deactivate($id)`

**Variables:**

-   `$user` (User)
-   Set is_active = false

##### `bulkActivate(Request $request)`

**Variables:**

-   `$userIds` (array)
-   Update multiple users

##### `export()`

**Variables:**

-   Generate Excel/CSV export

---

### Admin\PropertyController

Similar structure dengan Mitra\PropertyController tapi dengan additional verification methods.

### Admin\BookingController

Similar structure dengan Mitra\BookingController tapi bisa manage semua bookings.

### Admin\PaymentController

Similar structure dengan Mitra\PaymentController tapi bisa manage semua payments.

### Admin\TicketController

Enhanced version dengan assignment features dan bulk operations.

### Admin\NotificationController

**File:** `app/Http/Controllers/Admin/NotificationController.php`

#### Key Methods:

##### `create()`

**Variables:**

-   `$users` (Collection<User>) - All users untuk target
-   `$roles` (array) - ['admin', 'owner', 'tenant']

##### `store(Request $request)`

**Variables:**

-   `$targetUsers` (Collection<User>) - Based on selection
-   Create notification untuk each user
-   Support email/push notification

---

### Admin\SettingController

**File:** `app/Http/Controllers/Admin/SettingController.php`

#### Key Methods:

##### `index()`

**Variables:**

-   `$settings` (array) - App settings dari config/database

##### `update(Request $request)`

**Variables:**

-   Update app settings
-   Clear cache

##### `clearCache()`

**Variables:**

-   Artisan::call('cache:clear')
-   Artisan::call('config:clear')

---

## Notification Controller (Shared)

**File:** `app/Http/Controllers/NotificationController.php`

#### Key Methods:

##### `index()`

**Variables:**

-   `$notifications` (LengthAwarePaginator<Notification>)
-   Current user's notifications
-   Paginate: 20 per page

##### `getUnreadCount()`

**Returns:** JSON dengan unread count

##### `getRecent()`

**Variables:**

-   `$notifications` (Collection<Notification>) - Latest 10 unread
    **Returns:** JSON

##### `markAsRead($id)`

**Variables:**

-   `$notification` (Notification)
-   Update read_at

##### `markAllAsRead()`

**Variables:**

-   Update semua unread notifications user

##### `destroy($id)`

**Variables:**

-   `$notification` (Notification)
-   Delete notification

##### `clearRead()`

**Variables:**

-   Delete semua read notifications user

---

## Helper Functions & Utilities

### Terbilang Function (in receipt views)

**Purpose:** Convert angka ke kata-kata Bahasa Indonesia

**Usage:** Used in payment receipts

```php
function terbilang($x) {
    // Convert: 1000 → "seribu"
    // Convert: 1500000 → "satu juta lima ratus ribu"
}
```

### Image Upload Handling

**Pattern dalam controllers:**

```php
if ($request->hasFile('images')) {
    $images = [];
    foreach ($request->file('images') as $image) {
        $path = $image->store('boarding_houses', 'public');
        $images[] = $path;
    }
    $validated['images'] = $images;
}
```

### Authorization Checks

**Pattern:**

```php
// Check ownership
$property = BoardingHouse::where('user_id', Auth::id())
    ->findOrFail($id);

// Or use policy
$this->authorize('update', $property);
```
