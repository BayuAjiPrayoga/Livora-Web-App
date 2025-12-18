# LIVORA - Routes & Architecture Documentation

Dokumentasi lengkap routing, arsitektur aplikasi, dan alur user flow.

---

## Table of Contents

1. [Arsitektur Aplikasi](#arsitektur-aplikasi)
2. [Public Routes](#public-routes)
3. [Tenant Routes](#tenant-routes)
4. [Mitra (Owner) Routes](#mitra-owner-routes)
5. [Admin Routes](#admin-routes)
6. [User Flow Diagrams](#user-flow-diagrams)
7. [Role-Based Access Control](#role-based-access-control)

---

## Arsitektur Aplikasi

### Multi-Tenant System Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    LIVORA Platform                       │
├─────────────────────────────────────────────────────────┤
│                                                           │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │   Public     │  │    Tenant    │  │    Owner     │  │
│  │  (Guest)     │  │  (Penyewa)   │  │   (Mitra)    │  │
│  │              │  │              │  │              │  │
│  │ • Browse     │  │ • Dashboard  │  │ • Dashboard  │  │
│  │ • Search     │  │ • Booking    │  │ • Properties │  │
│  │ • View       │  │ • Payment    │  │ • Rooms      │  │
│  │ • Register   │  │ • Tickets    │  │ • Bookings   │  │
│  └──────────────┘  └──────────────┘  │ • Payments   │  │
│                                       │ • Tickets    │  │
│  ┌───────────────────────────────┐   │ • Reports    │  │
│  │         Admin Panel           │   └──────────────┘  │
│  │                               │                      │
│  │ • Users Management            │                      │
│  │ • Properties Verification     │                      │
│  │ • Bookings Monitoring         │                      │
│  │ • Payments Verification       │                      │
│  │ • Tickets Management          │                      │
│  │ • System Reports              │                      │
│  │ • Notifications Broadcast     │                      │
│  └───────────────────────────────┘                      │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

### Technology Stack

-   **Framework:** Laravel 11
-   **Frontend:** Blade Templates + Tailwind CSS
-   **Database:** MySQL
-   **Authentication:** Laravel Breeze
-   **File Storage:** Local (public disk)
-   **Assets:** Vite

---

## Public Routes

**File:** `routes/web.php` (Top section)  
**Middleware:** None (accessible without authentication)

### Route List

| Method | URI                | Name            | Controller@Method            | Description                             |
| ------ | ------------------ | --------------- | ---------------------------- | --------------------------------------- |
| GET    | `/`                | home            | HomeController@index         | Landing page dengan featured properties |
| GET    | `/browse`          | browse          | HomeController@browse        | Search & filter kost                    |
| GET    | `/properties/{id}` | properties.show | HomeController@show          | Detail property single                  |
| GET    | `/about`           | about           | HomeController@about         | About page                              |
| GET    | `/contact`         | contact         | HomeController@contact       | Contact form page                       |
| POST   | `/contact`         | contact.submit  | HomeController@submitContact | Submit contact form                     |

### Flow Diagram: Guest User

```
[Landing Page] → [Browse Kost] → [Property Detail]
       ↓              ↓                ↓
   [Register]    [Register]      [Register/Login]
       ↓              ↓                ↓
   [Login] ────────> [Tenant Dashboard]
```

---

## Dashboard Route (Role-Based Redirect)

**URI:** `/dashboard`  
**Middleware:** auth, verified

### Logic:

```php
if (Auth::check()) {
    if ($user->role === 'tenant') {
        redirect to: tenant.dashboard
    } elseif ($user->role === 'owner') {
        redirect to: mitra.dashboard
    } elseif ($user->role === 'admin') {
        redirect to: admin.dashboard
    }
} else {
    redirect to: login
}
```

---

## Tenant Routes

**Prefix:** `/tenant`  
**Name Prefix:** `tenant.`  
**Middleware:** auth, role:tenant

### Dashboard & Profile

| Method | URI                 | Name                  | Controller@Method                |
| ------ | ------------------- | --------------------- | -------------------------------- |
| GET    | `/tenant/dashboard` | tenant.dashboard      | Tenant\DashboardController@index |
| GET    | `/tenant/profile`   | tenant.profile        | Tenant\ProfileController@show    |
| PATCH  | `/tenant/profile`   | tenant.profile.update | Tenant\ProfileController@update  |

### Bookings (Full CRUD)

| Method    | URI                                      | Name                    | Controller@Method                 | Description                |
| --------- | ---------------------------------------- | ----------------------- | --------------------------------- | -------------------------- |
| GET       | `/tenant/bookings`                       | tenant.bookings.index   | Tenant\BookingController@index    | List all bookings          |
| GET       | `/tenant/bookings/create`                | tenant.bookings.create  | Tenant\BookingController@create   | Form booking baru          |
| POST      | `/tenant/bookings`                       | tenant.bookings.store   | Tenant\BookingController@store    | Submit booking             |
| GET       | `/tenant/bookings/{id}`                  | tenant.bookings.show    | Tenant\BookingController@show     | Detail booking             |
| GET       | `/tenant/bookings/{id}/edit`             | tenant.bookings.edit    | Tenant\BookingController@edit     | Edit booking               |
| PUT/PATCH | `/tenant/bookings/{id}`                  | tenant.bookings.update  | Tenant\BookingController@update   | Update booking             |
| DELETE    | `/tenant/bookings/{id}`                  | tenant.bookings.destroy | Tenant\BookingController@destroy  | Delete booking             |
| GET       | `/tenant/bookings/rooms/{boardingHouse}` | tenant.bookings.rooms   | Tenant\BookingController@getRooms | Get available rooms (AJAX) |
| POST      | `/tenant/bookings/{id}/cancel`           | tenant.bookings.cancel  | Tenant\BookingController@cancel   | Cancel booking             |

### Payments (Full CRUD)

| Method    | URI                                      | Name                             | Controller@Method                        | Description          |
| --------- | ---------------------------------------- | -------------------------------- | ---------------------------------------- | -------------------- |
| GET       | `/tenant/payments`                       | tenant.payments.index            | Tenant\PaymentController@index           | List all payments    |
| GET       | `/tenant/payments/create`                | tenant.payments.create           | Tenant\PaymentController@create          | Form upload payment  |
| POST      | `/tenant/payments`                       | tenant.payments.store            | Tenant\PaymentController@store           | Submit payment proof |
| GET       | `/tenant/payments/{id}`                  | tenant.payments.show             | Tenant\PaymentController@show            | Detail payment       |
| GET       | `/tenant/payments/{id}/edit`             | tenant.payments.edit             | Tenant\PaymentController@edit            | Edit payment         |
| PUT/PATCH | `/tenant/payments/{id}`                  | tenant.payments.update           | Tenant\PaymentController@update          | Update payment       |
| DELETE    | `/tenant/payments/{id}`                  | tenant.payments.destroy          | Tenant\PaymentController@destroy         | Delete payment       |
| GET       | `/tenant/payments/{id}/download-receipt` | tenant.payments.download-receipt | Tenant\PaymentController@downloadReceipt | Download receipt PDF |

### Tickets (Full CRUD)

| Method    | URI                         | Name                   | Controller@Method               | Description        |
| --------- | --------------------------- | ---------------------- | ------------------------------- | ------------------ |
| GET       | `/tenant/tickets`           | tenant.tickets.index   | Tenant\TicketController@index   | List all tickets   |
| GET       | `/tenant/tickets/create`    | tenant.tickets.create  | Tenant\TicketController@create  | Form create ticket |
| POST      | `/tenant/tickets`           | tenant.tickets.store   | Tenant\TicketController@store   | Submit ticket      |
| GET       | `/tenant/tickets/{id}`      | tenant.tickets.show    | Tenant\TicketController@show    | Detail ticket      |
| GET       | `/tenant/tickets/{id}/edit` | tenant.tickets.edit    | Tenant\TicketController@edit    | Edit ticket        |
| PUT/PATCH | `/tenant/tickets/{id}`      | tenant.tickets.update  | Tenant\TicketController@update  | Update ticket      |
| DELETE    | `/tenant/tickets/{id}`      | tenant.tickets.destroy | Tenant\TicketController@destroy | Delete ticket      |

---

## Mitra (Owner) Routes

**Prefix:** `/mitra`  
**Name Prefix:** `mitra.`  
**Middleware:** auth, role:owner

### Dashboard

| Method | URI                | Name            | Controller@Method               |
| ------ | ------------------ | --------------- | ------------------------------- |
| GET    | `/mitra/dashboard` | mitra.dashboard | Mitra\DashboardController@index |

### Properties Management (Full CRUD)

| Method    | URI                           | Name                     | Controller@Method                | Description          |
| --------- | ----------------------------- | ------------------------ | -------------------------------- | -------------------- |
| GET       | `/mitra/properties`           | mitra.properties.index   | Mitra\PropertyController@index   | List properties      |
| GET       | `/mitra/properties/create`    | mitra.properties.create  | Mitra\PropertyController@create  | Form create property |
| POST      | `/mitra/properties`           | mitra.properties.store   | Mitra\PropertyController@store   | Submit property      |
| GET       | `/mitra/properties/{id}`      | mitra.properties.show    | Mitra\PropertyController@show    | Detail property      |
| GET       | `/mitra/properties/{id}/edit` | mitra.properties.edit    | Mitra\PropertyController@edit    | Edit property        |
| PUT/PATCH | `/mitra/properties/{id}`      | mitra.properties.update  | Mitra\PropertyController@update  | Update property      |
| DELETE    | `/mitra/properties/{id}`      | mitra.properties.destroy | Mitra\PropertyController@destroy | Delete property      |

### Rooms Management (Nested under Properties)

| Method    | URI                                                             | Name                            | Controller@Method                       | Description              |
| --------- | --------------------------------------------------------------- | ------------------------------- | --------------------------------------- | ------------------------ |
| GET       | `/mitra/properties/{property}/rooms`                            | mitra.rooms.index               | Mitra\RoomController@index              | List rooms               |
| GET       | `/mitra/properties/{property}/rooms/create`                     | mitra.rooms.create              | Mitra\RoomController@create             | Form create room         |
| POST      | `/mitra/properties/{property}/rooms`                            | mitra.rooms.store               | Mitra\RoomController@store              | Submit room              |
| GET       | `/mitra/properties/{property}/rooms/{room}`                     | mitra.rooms.show                | Mitra\RoomController@show               | Detail room              |
| GET       | `/mitra/properties/{property}/rooms/{room}/edit`                | mitra.rooms.edit                | Mitra\RoomController@edit               | Edit room                |
| PUT/PATCH | `/mitra/properties/{property}/rooms/{room}`                     | mitra.rooms.update              | Mitra\RoomController@update             | Update room              |
| DELETE    | `/mitra/properties/{property}/rooms/{room}`                     | mitra.rooms.destroy             | Mitra\RoomController@destroy            | Delete room              |
| PATCH     | `/mitra/properties/{property}/rooms/{room}/toggle-availability` | mitra.rooms.toggle-availability | Mitra\RoomController@toggleAvailability | Toggle room availability |

### Bookings Management

| Method    | URI                                     | Name                     | Controller@Method                | Description             |
| --------- | --------------------------------------- | ------------------------ | -------------------------------- | ----------------------- |
| GET       | `/mitra/bookings`                       | mitra.bookings.index     | Mitra\BookingController@index    | List bookings           |
| GET       | `/mitra/bookings/create`                | mitra.bookings.create    | Mitra\BookingController@create   | Create booking (manual) |
| POST      | `/mitra/bookings`                       | mitra.bookings.store     | Mitra\BookingController@store    | Submit booking          |
| GET       | `/mitra/bookings/{id}`                  | mitra.bookings.show      | Mitra\BookingController@show     | Detail booking          |
| GET       | `/mitra/bookings/{id}/edit`             | mitra.bookings.edit      | Mitra\BookingController@edit     | Edit booking            |
| PUT/PATCH | `/mitra/bookings/{id}`                  | mitra.bookings.update    | Mitra\BookingController@update   | Update booking          |
| DELETE    | `/mitra/bookings/{id}`                  | mitra.bookings.destroy   | Mitra\BookingController@destroy  | Delete booking          |
| GET       | `/mitra/bookings/rooms/{boardingHouse}` | mitra.bookings.rooms     | Mitra\BookingController@getRooms | Get rooms AJAX          |
| POST      | `/mitra/bookings/{id}/confirm`          | mitra.bookings.confirm   | Mitra\BookingController@confirm  | Confirm booking         |
| POST      | `/mitra/bookings/{id}/check-in`         | mitra.bookings.check-in  | Mitra\BookingController@checkIn  | Check-in tenant         |
| POST      | `/mitra/bookings/{id}/check-out`        | mitra.bookings.check-out | Mitra\BookingController@checkOut | Check-out tenant        |
| POST      | `/mitra/bookings/{id}/cancel`           | mitra.bookings.cancel    | Mitra\BookingController@cancel   | Cancel booking          |

### Payments Management

| Method | URI                                     | Name                            | Controller@Method                       | Description        |
| ------ | --------------------------------------- | ------------------------------- | --------------------------------------- | ------------------ |
| GET    | `/mitra/payments`                       | mitra.payments.index            | Mitra\PaymentController@index           | List payments      |
| GET    | `/mitra/payments/{id}`                  | mitra.payments.show             | Mitra\PaymentController@show            | Detail payment     |
| PATCH  | `/mitra/payments/{id}/verify`           | mitra.payments.verify           | Mitra\PaymentController@verify          | Verify payment     |
| PATCH  | `/mitra/payments/{id}/reject`           | mitra.payments.reject           | Mitra\PaymentController@reject          | Reject payment     |
| POST   | `/mitra/payments/bulk-action`           | mitra.payments.bulk-action      | Mitra\PaymentController@bulkAction      | Bulk verify/reject |
| GET    | `/mitra/payments/{id}/download-proof`   | mitra.payments.download-proof   | Mitra\PaymentController@downloadProof   | Download proof     |
| GET    | `/mitra/payments/{id}/download-receipt` | mitra.payments.download-receipt | Mitra\PaymentController@downloadReceipt | Download receipt   |

### Tickets Management (Read-Only + Update)

| Method    | URI                            | Name                          | Controller@Method                     | Description                  |
| --------- | ------------------------------ | ----------------------------- | ------------------------------------- | ---------------------------- |
| GET       | `/mitra/tickets`               | mitra.tickets.index           | Mitra\TicketController@index          | List tickets                 |
| GET       | `/mitra/tickets/{id}`          | mitra.tickets.show            | Mitra\TicketController@show           | Detail ticket                |
| PUT/PATCH | `/mitra/tickets/{id}`          | mitra.tickets.update          | Mitra\TicketController@update         | Update ticket (add response) |
| PATCH     | `/mitra/tickets/{id}/status`   | mitra.tickets.update-status   | Mitra\TicketController@updateStatus   | Update status only           |
| PATCH     | `/mitra/tickets/{id}/priority` | mitra.tickets.update-priority | Mitra\TicketController@updatePriority | Update priority              |

### Reports

| Method | URI                        | Name                    | Controller@Method                | Description       |
| ------ | -------------------------- | ----------------------- | -------------------------------- | ----------------- |
| GET    | `/mitra/reports`           | mitra.reports.index     | Mitra\ReportController@index     | Reports dashboard |
| GET    | `/mitra/reports/revenue`   | mitra.reports.revenue   | Mitra\ReportController@revenue   | Revenue report    |
| GET    | `/mitra/reports/occupancy` | mitra.reports.occupancy | Mitra\ReportController@occupancy | Occupancy report  |

---

## Admin Routes

**Prefix:** `/admin`  
**Name Prefix:** `admin.`  
**Middleware:** auth, role:admin

### Dashboard

| Method | URI                | Name            | Controller@Method               |
| ------ | ------------------ | --------------- | ------------------------------- |
| GET    | `/admin/dashboard` | admin.dashboard | Admin\DashboardController@index |

### Users Management (Full CRUD + Bulk Actions)

| Method    | URI                            | Name                        | Controller@Method                   | Description     |
| --------- | ------------------------------ | --------------------------- | ----------------------------------- | --------------- |
| GET       | `/admin/users`                 | admin.users.index           | Admin\UserController@index          | List users      |
| GET       | `/admin/users/create`          | admin.users.create          | Admin\UserController@create         | Create user     |
| POST      | `/admin/users`                 | admin.users.store           | Admin\UserController@store          | Store user      |
| GET       | `/admin/users/{id}`            | admin.users.show            | Admin\UserController@show           | Detail user     |
| GET       | `/admin/users/{id}/edit`       | admin.users.edit            | Admin\UserController@edit           | Edit user       |
| PUT/PATCH | `/admin/users/{id}`            | admin.users.update          | Admin\UserController@update         | Update user     |
| DELETE    | `/admin/users/{id}`            | admin.users.destroy         | Admin\UserController@destroy        | Delete user     |
| PATCH     | `/admin/users/{id}/activate`   | admin.users.activate        | Admin\UserController@activate       | Activate user   |
| PATCH     | `/admin/users/{id}/deactivate` | admin.users.deactivate      | Admin\UserController@deactivate     | Deactivate user |
| POST      | `/admin/users/bulk-activate`   | admin.users.bulk-activate   | Admin\UserController@bulkActivate   | Bulk activate   |
| POST      | `/admin/users/bulk-deactivate` | admin.users.bulk-deactivate | Admin\UserController@bulkDeactivate | Bulk deactivate |
| POST      | `/admin/users/bulk-delete`     | admin.users.bulk-delete     | Admin\UserController@bulkDelete     | Bulk delete     |
| GET       | `/admin/users-export`          | admin.users.export          | Admin\UserController@export         | Export to Excel |

### Properties Management (Full CRUD + Verification)

| Method    | URI                              | Name                          | Controller@Method                    | Description      |
| --------- | -------------------------------- | ----------------------------- | ------------------------------------ | ---------------- |
| GET       | `/admin/properties`              | admin.properties.index        | Admin\PropertyController@index       | List properties  |
| GET       | `/admin/properties/create`       | admin.properties.create       | Admin\PropertyController@create      | Create property  |
| POST      | `/admin/properties`              | admin.properties.store        | Admin\PropertyController@store       | Store property   |
| GET       | `/admin/properties/{id}`         | admin.properties.show         | Admin\PropertyController@show        | Detail property  |
| GET       | `/admin/properties/{id}/edit`    | admin.properties.edit         | Admin\PropertyController@edit        | Edit property    |
| PUT/PATCH | `/admin/properties/{id}`         | admin.properties.update       | Admin\PropertyController@update      | Update property  |
| DELETE    | `/admin/properties/{id}`         | admin.properties.destroy      | Admin\PropertyController@destroy     | Delete property  |
| PATCH     | `/admin/properties/{id}/verify`  | admin.properties.verify       | Admin\PropertyController@verify      | Verify property  |
| PATCH     | `/admin/properties/{id}/suspend` | admin.properties.suspend      | Admin\PropertyController@suspend     | Suspend property |
| POST      | `/admin/properties/bulk-verify`  | admin.properties.bulk-verify  | Admin\PropertyController@bulkVerify  | Bulk verify      |
| POST      | `/admin/properties/bulk-suspend` | admin.properties.bulk-suspend | Admin\PropertyController@bulkSuspend | Bulk suspend     |
| GET       | `/admin/properties-export`       | admin.properties.export       | Admin\PropertyController@export      | Export           |
| GET       | `/admin/properties/{id}/rooms`   | admin.properties.rooms        | Admin\PropertyController@getRooms    | Get rooms        |

### Bookings Management (Full CRUD + Approval)

Similar structure dengan bulk approve/reject actions.

### Payments Management (Full CRUD + Verification)

Similar structure dengan bulk verify/reject actions.

### Tickets Management (Full CRUD + Assignment)

| Additional Routes                   | Description        |
| ----------------------------------- | ------------------ |
| PATCH `/admin/tickets/{id}/assign`  | Assign to staff    |
| PATCH `/admin/tickets/{id}/resolve` | Mark as resolved   |
| POST `/admin/tickets/bulk-status`   | Bulk update status |
| POST `/admin/tickets/bulk-assign`   | Bulk assign        |
| POST `/admin/tickets/bulk-close`    | Bulk close         |

### Notifications Management

| Method | URI                                | Name                            | Controller@Method                       | Description         |
| ------ | ---------------------------------- | ------------------------------- | --------------------------------------- | ------------------- |
| GET    | `/admin/notifications`             | admin.notifications.index       | Admin\NotificationController@index      | List notifications  |
| GET    | `/admin/notifications/create`      | admin.notifications.create      | Admin\NotificationController@create     | Create notification |
| POST   | `/admin/notifications`             | admin.notifications.store       | Admin\NotificationController@store      | Send notification   |
| GET    | `/admin/notifications/{id}`        | admin.notifications.show        | Admin\NotificationController@show       | Detail              |
| DELETE | `/admin/notifications/{id}`        | admin.notifications.destroy     | Admin\NotificationController@destroy    | Delete              |
| POST   | `/admin/notifications/bulk-delete` | admin.notifications.bulk-delete | Admin\NotificationController@bulkDelete | Bulk delete         |
| POST   | `/admin/notifications/send-test`   | admin.notifications.send-test   | Admin\NotificationController@sendTest   | Test send           |
| GET    | `/admin/notifications/stats`       | admin.notifications.stats       | Admin\NotificationController@getStats   | Get stats           |
| GET    | `/admin/notifications-export`      | admin.notifications-export      | Admin\NotificationController@export     | Export              |

### Reports

| Method | URI                                 | Name                             | Controller@Method                        | Description        |
| ------ | ----------------------------------- | -------------------------------- | ---------------------------------------- | ------------------ |
| GET    | `/admin/reports/revenue`            | admin.reports.revenue            | Admin\ReportController@revenue           | Revenue report     |
| GET    | `/admin/reports/revenue/export`     | admin.reports.revenue.export     | Admin\ReportController@revenueExport     | Export             |
| GET    | `/admin/reports/occupancy`          | admin.reports.occupancy          | Admin\ReportController@occupancy         | Occupancy report   |
| GET    | `/admin/reports/occupancy/export`   | admin.reports.occupancy.export   | Admin\ReportController@occupancyExport   | Export             |
| GET    | `/admin/reports/performance`        | admin.reports.performance        | Admin\ReportController@performance       | Performance report |
| GET    | `/admin/reports/performance/export` | admin.reports.performance.export | Admin\ReportController@performanceExport | Export             |
| GET    | `/admin/reports/users`              | admin.reports.users              | Admin\ReportController@users             | Users report       |
| GET    | `/admin/reports/users/export`       | admin.reports.users.export       | Admin\ReportController@usersExport       | Export             |
| GET    | `/admin/reports/export/{type}`      | admin.reports.export             | Admin\ReportController@export            | Generic export     |

### Settings

| Method | URI                           | Name                       | Controller@Method                         | Description        |
| ------ | ----------------------------- | -------------------------- | ----------------------------------------- | ------------------ |
| GET    | `/admin/settings`             | admin.settings.index       | Admin\SettingController@index             | Settings page      |
| PATCH  | `/admin/settings`             | admin.settings.update      | Admin\SettingController@update            | Update settings    |
| PATCH  | `/admin/settings/general`     | admin.settings.general     | Admin\SettingController@updateGeneral     | Update general     |
| PATCH  | `/admin/settings/email`       | admin.settings.email       | Admin\SettingController@updateEmail       | Update email       |
| PATCH  | `/admin/settings/maintenance` | admin.settings.maintenance | Admin\SettingController@updateMaintenance | Update maintenance |
| POST   | `/admin/settings/test-email`  | admin.settings.test-email  | Admin\SettingController@testEmail         | Test email         |
| POST   | `/admin/settings/clear-cache` | admin.settings.clear-cache | Admin\SettingController@clearCache        | Clear cache        |
| POST   | `/admin/settings/clear-logs`  | admin.settings.clear-logs  | Admin\SettingController@clearLogs         | Clear logs         |

### Profile

| Method | URI                       | Name                   | Controller@Method                      |
| ------ | ------------------------- | ---------------------- | -------------------------------------- |
| GET    | `/admin/profile`          | admin.profile.edit     | Admin\ProfileController@edit           |
| PATCH  | `/admin/profile`          | admin.profile.update   | Admin\ProfileController@update         |
| POST   | `/admin/profile/password` | admin.profile.password | Admin\ProfileController@updatePassword |

---

## Shared Routes (All Authenticated Users)

**Prefix:** None  
**Middleware:** auth

### Profile Management

| Method | URI        | Name            | Controller@Method         |
| ------ | ---------- | --------------- | ------------------------- |
| GET    | `/profile` | profile.edit    | ProfileController@edit    |
| PATCH  | `/profile` | profile.update  | ProfileController@update  |
| DELETE | `/profile` | profile.destroy | ProfileController@destroy |

### Notifications

| Method | URI                            | Name                        | Controller@Method                     | Description        |
| ------ | ------------------------------ | --------------------------- | ------------------------------------- | ------------------ |
| GET    | `/notifications`               | notifications.index         | NotificationController@index          | List notifications |
| GET    | `/notifications/unread-count`  | notifications.unread-count  | NotificationController@getUnreadCount | Get count (AJAX)   |
| GET    | `/notifications/recent`        | notifications.recent        | NotificationController@getRecent      | Get recent (AJAX)  |
| PATCH  | `/notifications/{id}/read`     | notifications.read          | NotificationController@markAsRead     | Mark as read       |
| PATCH  | `/notifications/mark-all-read` | notifications.mark-all-read | NotificationController@markAllAsRead  | Mark all read      |
| DELETE | `/notifications/{id}`          | notifications.destroy       | NotificationController@destroy        | Delete             |
| DELETE | `/notifications/clear-read`    | notifications.clear-read    | NotificationController@clearRead      | Clear all read     |

---

## User Flow Diagrams

### Flow 1: Guest → Tenant (Complete Registration & Booking)

```
[Guest Landing Page]
         ↓
[Browse Kost dengan Filter]
    • Search by name/location
    • Filter by city
    • Filter by max price
    • Sort by price/name/latest
         ↓
[View Property Detail]
    • View photos gallery
    • View available rooms
    • View facilities
    • View owner contact
         ↓
[Click "Book This Room" or "Daftar Untuk Book"]
         ↓
[Register Account]
    • Fill name, email, password
    • Select role: tenant
    • Submit registration
         ↓
[Email Verification] (optional)
         ↓
[Login]
         ↓
[Redirected to Tenant Dashboard]
         ↓
[Go to Bookings → Create New Booking]
    • Select boarding house
    • Select room
    • Set start date & duration
    • Calculate total price
    • Add notes
    • Submit booking
         ↓
[Booking Status: PENDING]
    • Waiting for owner confirmation
         ↓
[Go to Payments → Upload Payment Proof]
    • Select booking
    • Enter amount
    • Upload transfer proof image
    • Submit payment
         ↓
[Payment Status: PENDING]
    • Waiting for owner verification
         ↓
    ┌────────────────────────┐
    │   Owner verifies       │
    │   payment              │
    └────────────────────────┘
         ↓
[Payment Status: VERIFIED]
[Booking Status: CONFIRMED]
    • Notification sent to tenant
         ↓
[Check-in Date Arrives]
    ┌────────────────────────┐
    │   Owner performs       │
    │   check-in             │
    └────────────────────────┘
         ↓
[Booking Status: ACTIVE]
    • Tenant can create tickets if needed
         ↓
[Check-out Date Arrives]
    ┌────────────────────────┐
    │   Owner performs       │
    │   check-out            │
    └────────────────────────┘
         ↓
[Booking Status: COMPLETED]
```

### Flow 2: Owner (Mitra) Registration → Property Management

```
[Register as Owner]
    • Role: owner
         ↓
[Login → Redirected to Mitra Dashboard]
    • View revenue statistics
    • View occupancy rate
    • View recent bookings
    • View pending tickets
         ↓
[Create New Property]
    • Fill property details
    • Upload photos
    • Set location
    • Submit
         ↓
[Property Status: PENDING VERIFICATION]
    ┌────────────────────────┐
    │   Admin verifies       │
    │   property             │
    └────────────────────────┘
         ↓
[Property Status: VERIFIED]
         ↓
[Add Rooms to Property]
    • Fill room details
    • Set price, capacity, size
    • Upload room photos
    • Set availability
    • Submit
         ↓
[Room is now AVAILABLE for booking]
         ↓
[Wait for Tenant Bookings]
         ↓
[Receive Booking Notification]
    • View booking details
    • Verify tenant information
         ↓
[Confirm Booking]
    • Booking status: PENDING → CONFIRMED
         ↓
[Receive Payment from Tenant]
    • View payment proof
         ↓
[Verify or Reject Payment]
    • If verify: Payment VERIFIED, Booking CONFIRMED
    • If reject: Payment REJECTED, Tenant must reupload
         ↓
[On Check-in Date]
    • Perform check-in
    • Booking status: CONFIRMED → ACTIVE
    • Room availability: true → false
         ↓
[During Stay]
    • Handle tenant tickets
    • Provide responses
    • Update ticket status
         ↓
[On Check-out Date]
    • Perform check-out
    • Booking status: ACTIVE → COMPLETED
    • Room availability: false → true
         ↓
[View Reports]
    • Revenue report
    • Occupancy report
```

### Flow 3: Admin Monitoring & Management

```
[Admin Login]
         ↓
[Admin Dashboard]
    • Overview statistics
    • Recent users
    • Recent bookings
    • Pending verifications
    • Open tickets
         ↓
[Property Verification Flow]
    • View pending properties
    • Check property details
    • Verify or Suspend
         ↓
[User Management]
    • List all users
    • Activate/Deactivate users
    • Delete users
    • Bulk actions
         ↓
[Payment Monitoring]
    • View all payments
    • Verify/Reject payments
    • Bulk verification
    • Export data
         ↓
[Ticket Management]
    • View all tickets
    • Assign to staff
    • Update status/priority
    • Resolve tickets
    • Bulk operations
         ↓
[Send Notifications]
    • Create announcement
    • Select target users (by role)
    • Send email/push notifications
         ↓
[View Reports]
    • Revenue report (all owners)
    • Occupancy report (all properties)
    • Performance report
    • Users report
    • Export to Excel/CSV
         ↓
[System Settings]
    • Update general settings
    • Configure email settings
    • Maintenance mode
    • Clear cache
    • Clear logs
```

---

## Role-Based Access Control

### Middleware: RoleMiddleware

**File:** `app/Http/Middleware/RoleMiddleware.php`

**Usage in routes:**

```php
Route::middleware(['auth', 'role:tenant'])->group(function () {
    // Tenant routes
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    // Owner routes
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin routes
});
```

### Access Matrix

| Feature               | Guest | Tenant | Owner    | Admin    |
| --------------------- | ----- | ------ | -------- | -------- |
| Browse Properties     | ✅    | ✅     | ✅       | ✅       |
| View Property Details | ✅    | ✅     | ✅       | ✅       |
| Register/Login        | ✅    | -      | -        | -        |
| Create Booking        | ❌    | ✅     | ❌       | ❌       |
| Upload Payment        | ❌    | ✅     | ❌       | ❌       |
| Create Ticket         | ❌    | ✅     | ❌       | ❌       |
| Create Property       | ❌    | ❌     | ✅       | ✅       |
| Manage Rooms          | ❌    | ❌     | ✅       | ✅       |
| Verify Payments       | ❌    | ❌     | ✅       | ✅       |
| Confirm Bookings      | ❌    | ❌     | ✅       | ✅       |
| Respond to Tickets    | ❌    | ❌     | ✅       | ✅       |
| View Reports          | ❌    | ❌     | ✅ (own) | ✅ (all) |
| Manage Users          | ❌    | ❌     | ❌       | ✅       |
| Verify Properties     | ❌    | ❌     | ❌       | ✅       |
| Send Notifications    | ❌    | ❌     | ❌       | ✅       |
| System Settings       | ❌    | ❌     | ❌       | ✅       |

---

## API-Like Routes (AJAX Endpoints)

### Get Available Rooms

```php
GET /tenant/bookings/rooms/{boardingHouse}
GET /mitra/bookings/rooms/{boardingHouse}

Response: JSON
{
    "rooms": [
        {
            "id": 1,
            "name": "Kamar 1",
            "price": 1000000,
            "is_available": true
        }
    ]
}
```

### Get Unread Notifications Count

```php
GET /notifications/unread-count

Response: JSON
{
    "count": 5
}
```

### Get Recent Notifications

```php
GET /notifications/recent

Response: JSON
{
    "notifications": [...]
}
```

---

## Authentication Routes

**File:** `routes/auth.php`  
**Included by:** `routes/web.php` (require **DIR**.'/auth.php')

Standard Laravel Breeze authentication routes:

-   Login, Register, Logout
-   Password Reset
-   Email Verification
-   Password Confirmation

---

## Route Naming Convention

### Pattern:

```
{prefix}.{resource}.{action}

Examples:
- tenant.bookings.index
- mitra.properties.create
- admin.users.bulk-activate
```

### Actions:

-   `index` - List all
-   `create` - Show create form
-   `store` - Submit create
-   `show` - Show detail
-   `edit` - Show edit form
-   `update` - Submit update
-   `destroy` - Delete

### Custom Actions:

-   `confirm`, `cancel`, `verify`, `reject`
-   `activate`, `deactivate`, `suspend`
-   `bulk-*` - Bulk operations
-   `download-*` - File downloads
-   `export` - Data export

---

## Route Groups & Organization

Routes organized by:

1. **Public routes** (no auth)
2. **Shared authenticated routes** (all roles)
3. **Tenant routes** (role-specific)
4. **Mitra routes** (role-specific)
5. **Admin routes** (role-specific)
6. **Authentication routes** (separate file)

All role-specific routes use:

-   Prefix (tenant/mitra/admin)
-   Name prefix
-   Middleware: auth + role check
