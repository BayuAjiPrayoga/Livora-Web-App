# VARIABLES.md - Variable Reference Guide

> Comprehensive reference of all variables used across LIVORA application files, organized by controller/class with types, descriptions, and usage context.

**Last Updated**: December 21, 2025  
**Laravel Version**: 12  
**Scope**: Web Controllers & API Controllers  
**Status**: Comprehensive reference for all active controllers

---

## 📋 Table of Contents

1. [Controllers](#controllers)
2. [Models](#models)
3. [Views](#views)
4. [Configuration Variables](#configuration-variables)
5. [Environment Variables](#environment-variables)
6. [Database Variables](#database-variables)

---

## 🎮 Controllers

### HomeController

#### `index()` Method Variables

```php
// Variable: $properties
// Type: Collection<BoardingHouse>
// Description: Collection of boarding houses with room count relationship
// Usage: Display featured properties on homepage
$properties = BoardingHouse::with('rooms')
    ->where('is_active', true)
    ->where('is_verified', true)
    ->withCount('rooms')
    ->latest()
    ->take(6)
    ->get();

// Variable: $stats
// Type: array
// Description: Statistics for homepage display
// Keys: total_properties (int), total_rooms (int), available_rooms (int)
// Usage: Show platform statistics to visitors
$stats = [
    'total_properties' => (int) Total verified boarding houses count,
    'total_rooms' => (int) Total rooms across all properties,
    'available_rooms' => (int) Rooms with is_available = true
];
```

#### `browse()` Method Variables

```php
// Variable: $query
// Type: Builder (Eloquent Query Builder)
// Description: Base query for boarding house filtering
// Usage: Build dynamic query based on search parameters
$query = BoardingHouse::with(['rooms', 'user'])
    ->where('is_active', true)
    ->where('is_verified', true);

// Variable: $city
// Type: string|null
// Description: City filter parameter from request
// Usage: Filter properties by city location
$city = $request->get('city');

// Variable: $minPrice
// Type: int|null
// Description: Minimum price filter from request
// Usage: Filter rooms with price >= minPrice
$minPrice = $request->get('min_price');

// Variable: $maxPrice
// Type: int|null
// Description: Maximum price filter from request
// Usage: Filter rooms with price <= maxPrice
$maxPrice = $request->get('max_price');

// Variable: $capacity
// Type: int|null
// Description: Room capacity filter from request
// Usage: Filter rooms with capacity >= specified value
$capacity = $request->get('capacity');

// Variable: $facilities
// Type: array|null
// Description: Array of facility IDs from request
// Usage: Filter rooms that have specified facilities
$facilities = $request->get('facilities');

// Variable: $sort
// Type: string
// Description: Sort parameter (price_asc, price_desc, capacity_asc, capacity_desc)
// Default: 'latest'
// Usage: Determine property sorting order
$sort = $request->get('sort', 'latest');

// Variable: $perPage
// Type: int
// Description: Number of items per page
// Default: 12
// Usage: Pagination limit
$perPage = 12;

// Variable: $properties
// Type: LengthAwarePaginator<BoardingHouse>
// Description: Paginated collection of filtered boarding houses
// Usage: Display properties with pagination
$properties = $query->paginate($perPage);

// Variable: $cities
// Type: Collection<string>
// Description: Distinct cities for filter dropdown
// Usage: Populate city filter options
$cities = BoardingHouse::where('is_active', true)
    ->where('is_verified', true)
    ->distinct()
    ->pluck('city')
    ->sort();

// Variable: $facilities
// Type: Collection<Facility>
// Description: All facilities for filter checkboxes
// Usage: Populate facility filter options
$facilities = Facility::all();

// Variable: $currentPage
// Type: int
// Description: Current pagination page number
// Usage: Calculate offset for manual pagination
$currentPage = $request->get('page', 1);

// Variable: $offset
// Type: int
// Description: Calculated offset for pagination
// Usage: Skip records for current page
$offset = ($currentPage - 1) * $perPage;
```

#### `show($id)` Method Variables

```php
// Variable: $property
// Type: BoardingHouse
// Description: Single boarding house with relationships
// Usage: Display property details page
$property = BoardingHouse::with(['rooms.facilities', 'user'])
    ->where('is_active', true)
    ->where('is_verified', true)
    ->findOrFail($id);

// Variable: $otherProperties
// Type: Collection<BoardingHouse>
// Description: Other properties from same owner (max 3)
// Usage: Display related properties section
$otherProperties = BoardingHouse::with('rooms')
    ->where('user_id', $property->user_id)
    ->where('id', '!=', $property->id)
    ->where('is_active', true)
    ->where('is_verified', true)
    ->withCount('rooms')
    ->take(3)
    ->get();
```

#### `submitContact()` Method Variables

```php
// Variable: $name
// Type: string
// Description: Contact form name field
// Validation: required|string|max:255
// Usage: Sender name for contact message
$name = $request->input('name');

// Variable: $email
// Type: string
// Description: Contact form email field
// Validation: required|email|max:255
// Usage: Sender email for reply
$email = $request->input('email');

// Variable: $subject
// Type: string
// Description: Contact form subject field
// Validation: required|string|max:255
// Usage: Message subject line
$subject = $request->input('subject');

// Variable: $message
// Type: string
// Description: Contact form message content
// Validation: required|string|min:10
// Usage: Main message content
$message = $request->input('message');
```

---

### Tenant\DashboardController

#### `index()` Method Variables

```php
// Variable: $user
// Type: User
// Description: Authenticated tenant user
// Usage: Get user-specific data and relationships
$user = auth()->user();

// Variable: $bookings
// Type: Collection<Booking>
// Description: All bookings for authenticated tenant with relationships
// Usage: Calculate statistics and display recent activities
$bookings = $user->bookings()->with(['room.boardingHouse', 'payments'])->get();

// Variable: $statistics
// Type: array
// Description: Dashboard statistics for tenant
// Keys: total_bookings, active_bookings, completed_bookings, cancelled_bookings,
//       total_payments, verified_payments, pending_payments, total_spent,
//       monthly_spent, open_tickets, resolved_tickets
// Usage: Display dashboard metrics
$statistics = $this->calculateStatistics($user);

// Variable: $recentActivities
// Type: Collection<array>
// Description: Recent activities (bookings, payments, tickets) with metadata
// Structure: ['type', 'icon', 'title', 'description', 'time', 'status', 'link']
// Usage: Display activity timeline on dashboard
$recentActivities = $this->getRecentActivities($user);

// Variable: $activeBooking
// Type: Booking|null
// Description: Current active booking for tenant
// Usage: Display current stay information
$activeBooking = $bookings->where('status', 'active')->first();

// Variable: $upcomingBooking
// Type: Booking|null
// Description: Next confirmed booking for tenant
// Usage: Display upcoming stay information
$upcomingBooking = $bookings->where('status', 'confirmed')
    ->sortBy('start_date')
    ->first();

// Variable: $pendingPayments
// Type: Collection<Payment>
// Description: Payments awaiting verification
// Usage: Alert user about pending payment verifications
$pendingPayments = Payment::whereIn('booking_id', $bookings->pluck('id'))
    ->where('status', 'pending')
    ->with('booking.room.boardingHouse')
    ->get();

// Variable: $openTickets
// Type: Collection<Ticket>
// Description: Tickets that are still open (not resolved)
// Usage: Display active support tickets
$openTickets = $user->tickets()
    ->whereIn('status', ['open', 'in_progress'])
    ->with('room.boardingHouse')
    ->latest()
    ->take(5)
    ->get();
```

#### `calculateStatistics($user)` Method Variables

```php
// Variable: $bookings
// Type: Collection<Booking>
// Description: All user bookings
// Usage: Base collection for calculations
$bookings = $user->bookings;

// Variable: $payments
// Type: Collection<Payment>
// Description: All payments for user's bookings
// Usage: Calculate payment statistics
$payments = Payment::whereIn('booking_id', $bookings->pluck('id'))->get();

// Variable: $tickets
// Type: Collection<Ticket>
// Description: All user tickets
// Usage: Calculate ticket statistics
$tickets = $user->tickets;

// Variable: $currentMonth
// Type: Carbon
// Description: Current month start date
// Usage: Filter monthly data
$currentMonth = Carbon::now()->startOfMonth();

// Return array variables:
// - total_bookings: int (total booking count)
// - active_bookings: int (bookings with status 'active')
// - completed_bookings: int (bookings with status 'completed')
// - cancelled_bookings: int (bookings with status 'cancelled')
// - total_payments: int (total payment count)
// - verified_payments: int (payments with status 'verified')
// - pending_payments: int (payments with status 'pending')
// - total_spent: decimal (sum of all verified payments)
// - monthly_spent: decimal (sum of current month verified payments)
// - open_tickets: int (tickets with status 'open' or 'in_progress')
// - resolved_tickets: int (tickets with status 'resolved')
```

#### `getRecentActivities($user)` Method Variables

```php
// Variable: $activities
// Type: Collection<array>
// Description: Collection to store all activities
// Usage: Merge different activity types
$activities = collect();

// Variable: $recentBookings
// Type: Collection<Booking>
// Description: Recent bookings (last 10) with relationships
// Usage: Add booking activities to timeline
$recentBookings = $user->bookings()
    ->with('room.boardingHouse')
    ->latest()
    ->take(10)
    ->get();

// Variable: $recentPayments
// Type: Collection<Payment>
// Description: Recent payments (last 10) with relationships
// Usage: Add payment activities to timeline
$recentPayments = Payment::whereIn('booking_id', $user->bookings->pluck('id'))
    ->with('booking.room.boardingHouse')
    ->latest()
    ->take(10)
    ->get();

// Variable: $recentTickets
// Type: Collection<Ticket>
// Description: Recent tickets (last 10) with relationships
// Usage: Add ticket activities to timeline
$recentTickets = $user->tickets()
    ->with('room.boardingHouse')
    ->latest()
    ->take(10)
    ->get();

// Activity array structure for each item:
// - type: string ('booking', 'payment', 'ticket')
// - icon: string (icon class name)
// - title: string (activity title)
// - description: string (activity description)
// - time: Carbon (activity timestamp)
// - status: string (activity status)
// - link: string (URL to view details)
```

---

### Mitra\DashboardController

#### `index()` Method Variables

```php
// Variable: $boardingHouses
// Type: Collection<BoardingHouse>
// Description: All properties owned by authenticated user with relationships
// Usage: Calculate revenue and room statistics
$boardingHouses = auth()->user()->boardingHouses()
    ->with(['rooms.bookings.payments'])
    ->get();

// Variable: $roomIds
// Type: Collection<int>
// Description: All room IDs from owner's properties
// Usage: Filter bookings and calculate occupancy
$roomIds = $boardingHouses->pluck('rooms')->flatten()->pluck('id');

// Variable: $currentMonth
// Type: Carbon
// Description: Current month start date
// Usage: Calculate monthly revenue
$currentMonth = Carbon::now()->startOfMonth();

// Variable: $lastMonth
// Type: Carbon
// Description: Last month start date
// Usage: Calculate revenue growth
$lastMonth = Carbon::now()->subMonth()->startOfMonth();

// Variable: $monthlyRevenue
// Type: decimal
// Description: Revenue for current month
// Usage: Display current month earnings
$monthlyRevenue = Payment::whereIn('booking_id', function($query) use ($roomIds) {
        $query->select('id')
              ->from('bookings')
              ->whereIn('room_id', $roomIds);
    })
    ->where('status', 'verified')
    ->where('created_at', '>=', $currentMonth)
    ->sum('amount');

// Variable: $lastMonthRevenue
// Type: decimal
// Description: Revenue for last month
// Usage: Calculate revenue growth percentage
$lastMonthRevenue = Payment::whereIn('booking_id', function($query) use ($roomIds) {
        $query->select('id')
              ->from('bookings')
              ->whereIn('room_id', $roomIds);
    })
    ->where('status', 'verified')
    ->whereBetween('created_at', [$lastMonth, $currentMonth])
    ->sum('amount');

// Variable: $totalRevenue
// Type: decimal
// Description: All-time revenue from verified payments
// Usage: Display total lifetime earnings
$totalRevenue = Payment::whereIn('booking_id', function($query) use ($roomIds) {
        $query->select('id')
              ->from('bookings')
              ->whereIn('room_id', $roomIds);
    })
    ->where('status', 'verified')
    ->sum('amount');

// Variable: $revenueGrowth
// Type: float
// Description: Revenue growth percentage (monthly)
// Usage: Display growth indicator with color coding
$revenueGrowth = $lastMonthRevenue > 0
    ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
    : 0;

// Variable: $totalRooms
// Type: int
// Description: Total number of rooms across all properties
// Usage: Display room inventory
$totalRooms = $boardingHouses->sum(function($house) {
    return $house->rooms->count();
});

// Variable: $occupiedRooms
// Type: int
// Description: Rooms with active bookings
// Usage: Calculate occupancy rate
$occupiedRooms = Booking::whereIn('room_id', $roomIds)
    ->where('status', 'active')
    ->count();

// Variable: $activeBookings
// Type: int
// Description: Total active bookings
// Usage: Display current occupancy
$activeBookings = Booking::whereIn('room_id', $roomIds)
    ->whereIn('status', ['confirmed', 'active'])
    ->count();

// Variable: $pendingTickets
// Type: int
// Description: Tickets awaiting owner response
// Usage: Alert about pending customer service
$pendingTickets = Ticket::whereIn('room_id', $roomIds)
    ->whereIn('status', ['open', 'in_progress'])
    ->count();

// Variable: $recentBookings
// Type: Collection<Booking>
// Description: Latest 10 bookings with user and room info
// Usage: Display recent booking activity
$recentBookings = Booking::whereIn('room_id', $roomIds)
    ->with(['user', 'room'])
    ->latest()
    ->take(10)
    ->get();
```

---

### Admin\DashboardController

#### `index()` Method Variables

```php
// Variable: $totalUsers
// Type: int
// Description: Total registered users count
// Usage: System statistics
$totalUsers = User::count();

// Variable: $totalProperties
// Type: int
// Description: Total boarding houses count
// Usage: Platform inventory statistics
$totalProperties = BoardingHouse::count();

// Variable: $totalBookings
// Type: int
// Description: Total bookings across platform
// Usage: Business metrics
$totalBookings = Booking::count();

// Variable: $totalRevenue
// Type: decimal
// Description: Total verified payments amount
// Usage: Platform revenue statistics
$totalRevenue = Payment::where('status', 'verified')->sum('amount');

// Variable: $monthlyStats
// Type: array
// Description: Monthly statistics for current month
// Keys: users, properties, bookings, revenue
// Usage: Monthly growth tracking
$monthlyStats = [
    'users' => User::whereMonth('created_at', Carbon::now()->month)->count(),
    'properties' => BoardingHouse::whereMonth('created_at', Carbon::now()->month)->count(),
    'bookings' => Booking::whereMonth('created_at', Carbon::now()->month)->count(),
    'revenue' => Payment::where('status', 'verified')
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->sum('amount')
];

// Variable: $pendingVerifications
// Type: array
// Description: Items awaiting admin verification
// Keys: properties, payments, tickets
// Usage: Admin action alerts
$pendingVerifications = [
    'properties' => BoardingHouse::where('is_verified', false)->count(),
    'payments' => Payment::where('status', 'pending')->count(),
    'tickets' => Ticket::where('status', 'open')->count()
];

// Variable: $recentActivities
// Type: Collection<array>
// Description: Recent platform activities (last 20)
// Structure: ['type', 'description', 'user', 'time', 'status']
// Usage: Platform monitoring timeline
$recentActivities = $this->getRecentActivities();

// Variable: $chartData
// Type: array
// Description: Data for dashboard charts
// Keys: revenue_chart (monthly revenue), booking_chart (daily bookings), user_growth (monthly users)
// Usage: Generate dashboard visualizations
$chartData = $this->getChartData();
```

---

## 🏗️ Models

### User Model Variables

#### Properties

```php
// Variable: $fillable
// Type: array
// Description: Mass assignable attributes
$fillable = ['name', 'email', 'password', 'role', 'phone', 'address', 'avatar'];

// Variable: $hidden
// Type: array
// Description: Attributes hidden from arrays
$hidden = ['password', 'remember_token'];

// Variable: $casts
// Type: array
// Description: Attribute casting definitions
$casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
];

// Variable: $role
// Type: string
// Description: User role (tenant, owner, admin)
// Validation: in:tenant,owner,admin
// Usage: Access control and routing
```

#### Relationships

```php
// Variable: boardingHouses
// Type: HasMany<BoardingHouse>
// Description: Properties owned by user (for owner role)
// Usage: Owner property management

// Variable: bookings
// Type: HasMany<Booking>
// Description: Bookings made by user (for tenant role)
// Usage: Tenant booking history

// Variable: tickets
// Type: HasMany<Ticket>
// Description: Support tickets created by user
// Usage: Customer support tracking

// Variable: notifications
// Type: HasMany<Notification>
// Description: Notifications sent to user
// Usage: Real-time communication
```

### BoardingHouse Model Variables

#### Properties

```php
// Variable: $fillable
// Type: array
// Description: Mass assignable attributes
$fillable = [
    'user_id', 'name', 'slug', 'description', 'address', 'city',
    'phone', 'images', 'facilities', 'rules', 'is_active', 'is_verified'
];

// Variable: $casts
// Type: array
// Description: Attribute casting definitions
$casts = [
    'images' => 'array',
    'facilities' => 'array',
    'rules' => 'array',
    'is_active' => 'boolean',
    'is_verified' => 'boolean',
];

// Variable: $slug
// Type: string
// Description: URL-friendly property identifier
// Usage: SEO-friendly URLs for property pages
// Generation: Str::slug($name)

// Variable: $images
// Type: array|null
// Description: Array of property image filenames
// Storage: storage/app/public/properties/
// Usage: Property gallery display

// Variable: $facilities
// Type: array|null
// Description: Array of property-level facilities
// Examples: ['WiFi', 'Parking', 'Security', 'Laundry']
// Usage: Property amenities display

// Variable: $is_verified
// Type: boolean
// Description: Admin verification status
// Default: false
// Usage: Control property visibility to public
```

#### Relationships

```php
// Variable: owner
// Type: BelongsTo<User>
// Description: Property owner user
// Usage: Display owner information

// Variable: rooms
// Type: HasMany<Room>
// Description: Rooms in this property
// Usage: Room management and availability

// Variable: bookings
// Type: HasManyThrough<Booking>
// Description: All bookings for property rooms
// Usage: Property booking statistics
```

### Room Model Variables

#### Properties

```php
// Variable: $fillable
// Type: array
// Description: Mass assignable attributes
$fillable = [
    'boarding_house_id', 'name', 'description', 'price', 'capacity',
    'size', 'images', 'is_available'
];

// Variable: $casts
// Type: array
// Description: Attribute casting definitions
$casts = [
    'price' => 'decimal:0',
    'images' => 'array',
    'is_available' => 'boolean',
];

// Variable: $price
// Type: decimal
// Description: Monthly room price in IDR
// Validation: numeric|min:0
// Usage: Pricing and filtering

// Variable: $capacity
// Type: int
// Description: Maximum occupants allowed
// Validation: integer|min:1
// Usage: Occupancy filtering

// Variable: $size
// Type: decimal|null
// Description: Room size in square meters
// Usage: Room specifications display

// Variable: $images
// Type: array|null
// Description: Array of room image filenames
// Storage: storage/app/public/rooms/
// Usage: Room photo gallery
```

#### Relationships

```php
// Variable: boardingHouse
// Type: BelongsTo<BoardingHouse>
// Description: Parent property
// Usage: Property information context

// Variable: bookings
// Type: HasMany<Booking>
// Description: All bookings for this room
// Usage: Availability calculation

// Variable: tickets
// Type: HasMany<Ticket>
// Description: Support tickets for this room
// Usage: Room-specific customer service

// Variable: facilities
// Type: BelongsToMany<Facility>
// Description: Room-specific facilities
// Pivot: room_facility table
// Usage: Room amenities filtering
```

### Booking Model Variables

#### Properties

```php
// Variable: $fillable
// Type: array
// Description: Mass assignable attributes
$fillable = [
    'user_id', 'room_id', 'start_date', 'duration', 'total_amount',
    'final_amount', 'booking_code', 'status', 'notes'
];

// Variable: $casts
// Type: array
// Description: Attribute casting definitions
$casts = [
    'start_date' => 'date',
    'total_amount' => 'decimal:0',
    'final_amount' => 'decimal:0',
];

// Constants for status values:
const STATUS_PENDING = 'pending';
const STATUS_CONFIRMED = 'confirmed';
const STATUS_ACTIVE = 'active';
const STATUS_COMPLETED = 'completed';
const STATUS_CANCELLED = 'cancelled';

// Variable: $booking_code
// Type: string
// Description: Unique booking identifier
// Format: BK-YYYYMMDD-XXXX
// Usage: Reference number for booking tracking

// Variable: $duration
// Type: int
// Description: Booking duration in months
// Validation: integer|min:1
// Usage: Calculate end date and total amount

// Variable: $status
// Type: string
// Description: Current booking status
// Values: pending, confirmed, active, completed, cancelled
// Usage: Workflow control and display
```

#### Relationships

```php
// Variable: user
// Type: BelongsTo<User>
// Description: Tenant who made booking
// Usage: Contact information and notifications

// Variable: room
// Type: BelongsTo<Room>
// Description: Booked room
// Usage: Room details and availability

// Variable: payments
// Type: HasMany<Payment>
// Description: Payments for this booking
// Usage: Payment tracking and verification
```

### Payment Model Variables

#### Properties

```php
// Variable: $fillable
// Type: array
// Description: Mass assignable attributes
$fillable = [
    'booking_id', 'amount', 'payment_method', 'proof_image',
    'status', 'verified_at', 'verified_by', 'notes'
];

// Variable: $casts
// Type: array
// Description: Attribute casting definitions
$casts = [
    'amount' => 'decimal:0',
    'verified_at' => 'datetime',
];

// Constants for status values:
const STATUS_PENDING = 'pending';
const STATUS_VERIFIED = 'verified';
const STATUS_REJECTED = 'rejected';

// Variable: $proof_image
// Type: string|null
// Description: Payment proof image filename
// Storage: storage/app/public/payments/
// Usage: Verification by property owner

// Variable: $amount
// Type: decimal
// Description: Payment amount in IDR
// Validation: numeric|min:0
// Usage: Financial tracking and verification

// Variable: $verified_at
// Type: Carbon|null
// Description: Timestamp when payment was verified
// Usage: Audit trail and reporting

// Variable: $verified_by
// Type: int|null
// Description: User ID who verified payment (owner/admin)
// Usage: Accountability and audit
```

---

## 🎨 Views

### Layout Variables

#### `app.blade.php` Variables

```php
// Variable: $title
// Type: string
// Description: Page title for <title> tag
// Usage: SEO and browser tab display
// Default: 'LIVORA - Live Better, Stay Better'

// Variable: $user
// Type: User|null
// Description: Authenticated user instance
// Usage: Navigation menu and user-specific content
// Access: auth()->user()

// Variable: $unreadNotifications
// Type: int
// Description: Count of unread notifications
// Usage: Notification badge in header
// Calculation: $user->notifications()->whereNull('read_at')->count()
```

#### Navigation Variables

```php
// Variable: $currentRoute
// Type: string
// Description: Current route name
// Usage: Active navigation highlighting
// Access: request()->route()->getName()

// Variable: $userRole
// Type: string
// Description: Current user's role
// Values: 'guest', 'tenant', 'owner', 'admin'
// Usage: Role-based navigation menu display
// Access: auth()->user()?->role ?? 'guest'
```

### Public Views Variables

#### `home/index.blade.php` Variables

```php
// Variable: $properties
// Type: Collection<BoardingHouse>
// Description: Featured properties for homepage
// Usage: Property cards display

// Variable: $stats
// Type: array
// Description: Platform statistics
// Keys: total_properties, total_rooms, available_rooms
// Usage: Statistics section display
```

#### `home/browse.blade.php` Variables

```php
// Variable: $properties
// Type: LengthAwarePaginator<BoardingHouse>
// Description: Filtered and paginated properties
// Usage: Property grid with pagination

// Variable: $cities
// Type: Collection<string>
// Description: Available cities for filter
// Usage: City dropdown options

// Variable: $facilities
// Type: Collection<Facility>
// Description: Available facilities for filter
// Usage: Facility checkbox options

// Variable: $filters
// Type: array
// Description: Active filters from request
// Keys: city, min_price, max_price, capacity, facilities, sort
// Usage: Maintain filter state in form
```

### Dashboard Views Variables

#### `tenant/dashboard.blade.php` Variables

```php
// Variable: $statistics
// Type: array
// Description: Tenant dashboard metrics
// Usage: Statistics cards display

// Variable: $activeBooking
// Type: Booking|null
// Description: Current active booking
// Usage: Current stay widget

// Variable: $upcomingBooking
// Type: Booking|null
// Description: Next confirmed booking
// Usage: Upcoming stay widget

// Variable: $recentActivities
// Type: Collection<array>
// Description: Recent activity timeline
// Usage: Activity feed display

// Variable: $pendingPayments
// Type: Collection<Payment>
// Description: Payments awaiting verification
// Usage: Payment alerts
```

#### `mitra/dashboard.blade.php` Variables

```php
// Variable: $monthlyRevenue
// Type: decimal
// Description: Current month revenue
// Usage: Revenue card display

// Variable: $revenueGrowth
// Type: float
// Description: Revenue growth percentage
// Usage: Growth indicator with color

// Variable: $totalRooms
// Type: int
// Description: Total room count
// Usage: Inventory statistics

// Variable: $occupiedRooms
// Type: int
// Description: Occupied room count
// Usage: Occupancy rate calculation

// Variable: $recentBookings
// Type: Collection<Booking>
// Description: Latest bookings
// Usage: Recent activity table
```

---

## ⚙️ Configuration Variables

### Config Files Variables

#### `config/app.php` Variables

```php
// Variable: APP_NAME
// Type: string
// Description: Application name
// Default: 'LIVORA'
// Usage: Email templates, page titles

// Variable: APP_ENV
// Type: string
// Description: Application environment
// Values: 'local', 'production', 'staging'
// Usage: Feature toggles, debugging

// Variable: APP_DEBUG
// Type: boolean
// Description: Debug mode toggle
// Usage: Error display, logging level

// Variable: APP_URL
// Type: string
// Description: Application base URL
// Usage: Asset URLs, email links
```

#### `config/database.php` Variables

```php
// Variable: DB_CONNECTION
// Type: string
// Description: Database connection type
// Default: 'mysql'
// Usage: Database driver selection

// Variable: DB_HOST
// Type: string
// Description: Database server host
// Default: '127.0.0.1'
// Usage: Database connection

// Variable: DB_DATABASE
// Type: string
// Description: Database name
// Default: 'livora'
// Usage: Database selection
```

#### `config/filesystems.php` Variables

```php
// Variable: FILESYSTEM_DISK
// Type: string
// Description: Default filesystem disk
// Default: 'public'
// Usage: File storage and retrieval

// Storage paths:
// - properties: storage/app/public/properties/
// - rooms: storage/app/public/rooms/
// - payments: storage/app/public/payments/
// - profiles: storage/app/public/profiles/
```

---

## 🌍 Environment Variables

### Database Variables

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=livora
DB_USERNAME=root
DB_PASSWORD=
```

### Mail Variables

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@livora.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Storage Variables

```env
FILESYSTEM_DISK=public
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
```

### Application Variables

```env
APP_NAME=LIVORA
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost
```

---

## 🗄️ Database Variables

### Migration Variables

#### Users Table

```php
$table->id();                           // Primary key
$table->string('name');                 // User full name
$table->string('email')->unique();      // Email (unique)
$table->timestamp('email_verified_at')->nullable();
$table->string('password');             // Hashed password
$table->enum('role', ['tenant', 'owner', 'admin'])->default('tenant');
$table->string('phone')->nullable();    // Contact number
$table->text('address')->nullable();    // User address
$table->string('avatar')->nullable();   // Profile picture
$table->rememberToken();
$table->timestamps();
```

#### Boarding Houses Table

```php
$table->id();
$table->foreignId('user_id')->constrained()->onDelete('cascade');
$table->string('name');                 // Property name
$table->string('slug')->unique();       // URL slug
$table->text('description')->nullable();
$table->text('address');               // Full address
$table->string('city');                // City name
$table->string('phone');               // Contact phone
$table->json('images')->nullable();     // Image array
$table->json('facilities')->nullable(); // Facility array
$table->json('rules')->nullable();      // Rules array
$table->boolean('is_active')->default(true);
$table->boolean('is_verified')->default(false);
$table->timestamps();
```

#### Rooms Table

```php
$table->id();
$table->foreignId('boarding_house_id')->constrained()->onDelete('cascade');
$table->string('name');                 // Room name/number
$table->text('description')->nullable();
$table->decimal('price', 10, 0);       // Monthly price
$table->integer('capacity');           // Max occupants
$table->decimal('size', 5, 2)->nullable(); // Size in sqm
$table->json('images')->nullable();     // Image array
$table->boolean('is_available')->default(true);
$table->timestamps();
```

#### Bookings Table

```php
$table->id();
$table->foreignId('user_id')->constrained()->onDelete('cascade');
$table->foreignId('room_id')->constrained()->onDelete('cascade');
$table->date('start_date');            // Booking start
$table->integer('duration');           // Duration in months
$table->decimal('total_amount', 12, 0); // Base amount
$table->decimal('final_amount', 12, 0); // Final amount (after discounts)
$table->string('booking_code')->unique(); // Reference code
$table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled'])->default('pending');
$table->text('notes')->nullable();
$table->timestamps();
```

#### Payments Table

```php
$table->id();
$table->foreignId('booking_id')->constrained()->onDelete('cascade');
$table->decimal('amount', 12, 0);      // Payment amount
$table->string('payment_method')->nullable(); // Payment method
$table->string('proof_image')->nullable(); // Proof filename
$table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
$table->timestamp('verified_at')->nullable(); // Verification time
$table->foreignId('verified_by')->nullable()->constrained('users');
$table->text('notes')->nullable();     // Admin notes
$table->timestamps();
```

---

## 📊 Calculated Variables

### Statistical Calculations

#### Revenue Calculations

```php
// Monthly Revenue
$monthlyRevenue = Payment::where('status', 'verified')
    ->whereMonth('created_at', Carbon::now()->month)
    ->sum('amount');

// Growth Percentage
$growthPercentage = $lastMonthRevenue > 0
    ? (($currentRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
    : 0;

// Occupancy Rate
$occupancyRate = $totalRooms > 0
    ? ($occupiedRooms / $totalRooms) * 100
    : 0;
```

#### Aggregation Variables

```php
// Property Statistics
$propertyStats = BoardingHouse::selectRaw('
    COUNT(*) as total_properties,
    SUM(CASE WHEN is_verified = 1 THEN 1 ELSE 0 END) as verified_properties,
    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_properties
')->first();

// Room Availability
$roomAvailability = Room::selectRaw('
    COUNT(*) as total_rooms,
    SUM(CASE WHEN is_available = 1 THEN 1 ELSE 0 END) as available_rooms,
    AVG(price) as average_price
')->first();

// Booking Status Distribution
$bookingStats = Booking::selectRaw('
    status,
    COUNT(*) as count,
    SUM(final_amount) as total_amount
')->groupBy('status')->get();
```

---

## 🔄 Dynamic Variables

### Request Variables

#### Search and Filter Variables

```php
// From HomeController@browse
$searchTerm = $request->get('search');      // string|null
$cityFilter = $request->get('city');        // string|null
$minPrice = $request->get('min_price');     // int|null
$maxPrice = $request->get('max_price');     // int|null
$capacityFilter = $request->get('capacity'); // int|null
$facilityFilters = $request->get('facilities', []); // array
$sortBy = $request->get('sort', 'latest');  // string

// Pagination variables
$page = $request->get('page', 1);           // int
$perPage = $request->get('per_page', 12);   // int
$offset = ($page - 1) * $perPage;          // int
```

#### Form Variables

```php
// Booking form variables
$roomId = $request->get('room_id');         // int
$startDate = $request->get('start_date');   // string (Y-m-d)
$duration = $request->get('duration');      // int (months)
$specialRequests = $request->get('notes');  // string|null

// Payment form variables
$bookingId = $request->get('booking_id');   // int
$paymentAmount = $request->get('amount');   // decimal
$paymentMethod = $request->get('payment_method'); // string
$proofFile = $request->file('proof_image'); // UploadedFile|null

// Property form variables
$propertyName = $request->get('name');      // string
$propertyDesc = $request->get('description'); // string
$propertyAddress = $request->get('address'); // string
$propertyCity = $request->get('city');      // string
$propertyImages = $request->file('images'); // array<UploadedFile>
```

### Session Variables

```php
// Flash messages
session()->flash('success', 'Operation completed successfully');
session()->flash('error', 'An error occurred');
session()->flash('warning', 'Please review your input');
session()->flash('info', 'Information updated');

// Form data persistence
session()->flashInput($request->input());

// User preferences
session()->put('preferred_city', $cityName);
session()->put('last_search_filters', $filters);
session()->put('dashboard_layout', 'grid'); // 'grid' or 'list'
```

### Cache Variables

```php
// Property cache
$cacheKey = "properties.city.{$cityName}";
$cachedProperties = cache()->remember($cacheKey, 3600, function() {
    return BoardingHouse::where('city', $cityName)->get();
});

// Statistics cache
$statsCacheKey = 'dashboard.stats.user.' . auth()->id();
$cachedStats = cache()->remember($statsCacheKey, 1800, function() {
    return $this->calculateUserStats();
});

// Popular searches cache
$popularSearches = cache()->remember('popular.searches', 86400, function() {
    return SearchLog::groupBy('term')
        ->orderBy('count', 'desc')
        ->take(10)
        ->get();
});
```

---

This comprehensive variable reference covers all major variables used throughout the LIVORA application, organized by context and usage. Each variable includes its type, description, validation rules (where applicable), and usage context to help developers understand the application's data flow and structure.
