# LIVORA - Platform Manajemen Kos Modern

## 📋 Deskripsi Project

LIVORA adalah platform manajemen kos modern yang menghubungkan pemilik kos (owner/mitra), penyewa (tenant), dan admin sistem. Platform ini menyediakan fitur lengkap untuk booking, pembayaran, manajemen properti, dan sistem tiket support.

---

## 🏗️ Arsitektur Sistem

### **Tech Stack**

-   **Framework**: Laravel 12
-   **Database**: MySQL (Railway Production)
-   **Frontend**: Tailwind CSS + Blade Templates + Vite
-   **Authentication**: Laravel Breeze + Sanctum (API)
-   **Payment Gateway**: Midtrans (Sandbox & Production)
-   **Export**: Maatwebsite Excel, DomPDF
-   **Charts**: Chart.js
-   **Interactive Components**: Alpine.js
-   **Deployment**: Railway (Production)

### **User Roles**

1. **Admin**: Mengelola seluruh sistem
2. **Mitra**: Pemilik kos yang menyewakan properti (formerly "owner")
3. **Tenant**: Penyewa kos

---

## 📁 Struktur Project

```
c:\laragon\www\Livora\
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Controllers untuk admin
│   │   ├── Auth/           # Authentication controllers
│   │   ├── Mitra/          # Controllers untuk owner/mitra
│   │   └── Tenant/         # Controllers untuk tenant
│   └── Models/
│       ├── User.php        # Model user dengan multi-role
│       ├── BoardingHouse.php
│       ├── Room.php
│       ├── Booking.php
│       ├── Payment.php
│       └── Ticket.php
├── resources/
│   └── views/
│       ├── admin/          # Views untuk admin
│       ├── mitra/          # Views untuk owner/mitra
│       ├── tenant/         # Views untuk tenant
│       └── layouts/
└── routes/
    └── web.php             # Semua route definitions
```

---

## 🗃️ Database Schema

### **Users Table**

```sql
- id (primary key)
- name (varchar)
- email (varchar, unique)
- password (varchar)
- role (enum: 'admin', 'mitra', 'tenant')
- is_active (boolean)
- phone (varchar)
- avatar (varchar)
- created_at, updated_at
```

### **Boarding Houses Table**

```sql
- id (primary key)
- owner_id (foreign key -> users.id)
- name (varchar)
- address (text)
- description (text)
- facilities (json)
- status (enum: 'pending', 'active', 'suspended')
- created_at, updated_at
```

### **Rooms Table**

```sql
- id (primary key)
- boarding_house_id (foreign key -> boarding_houses.id)
- room_number (varchar)
- room_type (varchar)
- price (decimal)
- facilities (json)
- is_available (boolean)
- created_at, updated_at
```

### **Bookings Table**

```sql
- id (primary key)
- user_id (foreign key -> users.id)
- room_id (foreign key -> rooms.id)
- boarding_house_id (foreign key -> boarding_houses.id)
- booking_code (varchar, unique)
- booking_type (enum: 'monthly', 'yearly')
- check_in_date (date)
- check_out_date (date)
- duration_months (integer)
- duration_days (integer)
- monthly_price (decimal)
- deposit_amount (decimal)
- admin_fee (decimal)
- discount_amount (decimal)
- final_amount (decimal) -- replaces total_price
- status (enum: 'pending', 'confirmed', 'active', 'completed', 'cancelled')
- notes (text)
- created_at, updated_at
```

**Note**: Migration `enhance_bookings_table_for_booking_engine` renamed:

-   `start_date` → `check_in_date`
-   `end_date` → `check_out_date`
-   `total_price` → `final_amount`

### **Payments Table**

```sql
- id (primary key)
- booking_id (foreign key -> bookings.id)
- amount (decimal)
- payment_method (varchar)
- payment_proof (varchar)
- status (enum: 'pending', 'verified', 'rejected')
- payment_date (datetime)
- notes (text)
- created_at, updated_at
```

### **Tickets Table**

```sql
- id (primary key)
- user_id (foreign key -> users.id)
- boarding_house_id (foreign key -> boarding_houses.id, nullable)
- assigned_to (foreign key -> users.id, nullable)
- title (varchar)
- description (text)
- category (enum: 'technical', 'billing', 'general', 'complaint')
- priority (enum: 'low', 'medium', 'high', 'urgent')
- status (enum: 'open', 'in_progress', 'resolved', 'closed')
- admin_response (text)
- created_at, updated_atmitra
```

---

## 🛣️ Routing Structure

### **Authentication Routes**

```php
Route::get('/', function () {
    // Redirect based on user role after login
    if (Auth::check()) {
        if ($user->role === 'owner') return redirect()->route('mitra.dashboard');
        if ($user->role === 'tenant') return redirect('/tenant/dashboard');
        if ($user->role === 'admin') return redirect('/admin/dashboard');
    }
    return redirect()->route('login');
});
```

### **Admin Routes** (`/admin/*`)

-   **Dashboard**: `admin.dashboard`
-   **User Management**: `admin.users.*` (CRUD + activate/deactivate + export)
-   **Property Management**: `admin.properties.*` (CRUD + verify/suspend + export)
-   **Booking Management**: `admin.bookings.*` (CRUD + approve/reject + export)
-   **Payment Management**: `admin.payments.*` (CRUD + verify/reject + export)
-   **Ticket Management**: `admin.tickets.*` (CRUD + status/priority/assign + export)
-   **Reports**: `admin.reports.{revenue|occupancy|performance|users}`
-   **Settings**: `admin.settings.*` (general/email/maintenance)

### **Mitra/Owner Routes** (`/mitra/*`)

-   **Dashboard**: `mitra.dashboard`
-   **Property Management**: `mitra.properties.*`
-   **Room Management**: `mitra.rooms.*` (nested under properties)
-   **Booking Management**: `mitra.bookings.*`
-   **Payment Management**: `mitra.payments.*`
-   **Ticket Management**: `mitra.tickets.*`

### **Tenant Routes** (`/tenant/*`)

-   **Dashboard**: `tenant.dashboard`
-   **Profile Management**: `tenant.profile`
-   **Booking Management**: `tenant.bookings.*`
-   **Payment Management**: `tenant.payments.*`
-   **Ticket Management**: `tenant.tickets.*`

---

## 🎯 Status Development (Updated December 21, 2025)

### ✅ **Yang Sudah Selesai:**

1. **Authentication System**: Multi-role login/logout (Web + API) ✅
2. **Route Structure**: Web routes + API routes (api/v1) ✅
3. **Admin Controllers**: Full CRUD untuk semua modul + Export (Excel/PDF) ✅
4. **Mitra Controllers**: Full CRUD + Room management ✅
5. **Tenant Controllers**: Dashboard, Booking, Payment, Ticket ✅
6. **Database Models**: User, BoardingHouse, Room, Booking, Payment, Ticket dengan relations ✅
7. \*✨ **Fitur Utama yang Sudah Berfungsi:**

8. **Admin Panel**: Full management dashboard dengan analytics
9. **Mitra Panel**: Property & room management, booking verification
10. **Tenant Panel**: Booking system dengan payment via Midtrans
11. **Payment System**: Integrasi lengkap dengan Midtrans Snap
12. **Reporting**: Export ke Excel dan PDF untuk semua data
13. **API Mobile**: RESTful API ready untuk Flutter mobile app
14. **Ticketing**: Support ticket system untuk semua role
15. **Multi-tenancy**: Clean separation antar role

16. **Admin Views**: Banyak view files yang belum dibuat atau tidak konsisten
17. **Mitra Controllers & Views**: Sebagian besar belum diimplement dengan benar
18. **Tenant Controllers & Views**: Masih banyak yang missing
19. **UI Consistency**: Tampilan tidak konsisten, styling berantakan
20. **Functionality**: Banyak fitur yang belum fully functional

### 🚧 **Priority Tasks untuk Development Selanjutnya:**

#### **High Priority:**

1. **Fix Admin Views** - Buat/perbaiki semua view files admin

    - `admin/users/index.blade.php` ✅ (sudah diperbaiki)
    - `admin/bookings/index.blade.php` ❌ (belum ada)
    - `admin/payments/index.blade.php` ❌ (belum ada)
    - `admin/tickets/index.blade.php` ❌ (belum ada)
    - `admin/reports/*.blade.php` ❌ (belum ada)
    - `admin/settings/index.blade.php` ❌ (belum ada)

2. **Fix UI Consistency** - Standardisasi design system

    - Buat component library yang konsisten
    - Standardisasi color scheme, typography, spacing
    - Responsive design yang proper

3. **Complete Mitra Section** - Implement full mitra functionality
    - Dashboard dengan analytics
    - Property & room management
    - Booking & payment verification
    - Ticket handling

#### **Medium Priority:**

4. **Complete Tenant Section** - Implement tenant functionality

    - Dashboard untuk tenant
    - Booking system
    - Payment submission
    - Ticket creation

5. **Data Seeding** - Populate database dengan sample data
6. **Testing** - Unit tests dan feature tests

---

## 🔧 Development Guidelines

### **File Naming Conventions:**

-   Controllers: `PascalCase` (e.g., `UserController.php`)
-   Views: `kebab-case` (e.g., `user-management.blade.php`)
-   Models: `PascalCase` singular (e.g., `BoardingHouse.php`)
-   Routes: `snake_case` (e.g., `admin.users.index`)

### **Code Structure:**

-   Semua controller admin sudah ada di `app/Http/Controllers/Admin/`
-   View files harus di `resources/views/admin/`, `resources/views/mitra/`, `resources/views/tenant/`
-   Shared components di `resources/views/components/`
-   Layouts di `resources/views/layouts/`

### **Database Conventions:**

-   Table names: `snake_case` plural (e.g., `boarding_houses`)
-   Foreign keys: `table_id` (e.g., `user_id`, `boarding_house_id`)
-   Boolean fields: `is_*` or `has_*` (e.g., `is_active`, `has_facilities`)

---

## 🐛 Known Issues & Bugs

### **Critical Issues:**

1. **Missing View Files**: Banyak route yang sudah ada tapi view filenya belum dibuat
2. **Inconsistent Styling**: Setiap halaman punya styling yang berbeda
3. **Broken Functionality**: Filter, export, dan beberapa CRUD operations tidak berfungsi
4. **Data Mismatch**: Field names di database vs controller vs view tidak konsisten

### **UI/UX Issues:**

1. Navigation tidak konsisten antar role
2. Form validation messages tidak ditampilkan dengan baik
3. Loading states tidak ada
4. Error handling kurang baik
5. Mobile responsiveness bermasalah

### **Performance Issues:**

1. N+1 query problems di beberapa controller
2. Pagination tidak optimal
3. Large dataset handling belum dioptimasi

---

## 📚 Resources & Dependencies

### **Laravel Packages:**

```json
{
    "laravel/framework": "^12.0",
    "laravel/sanctum": "^4.2",
    "laravel/tinker": "^2.10.1",
    "barryvdh/laravel-dompdf": "^3.1",
    "maatwebsite/excel": "^3.1",
    "midtrans/midtrans-php": "^2.6",
    "laravel/breeze": "^2.3"
}
```

### **Frontend Dependencies:**

```json
{
    "tailwindcss": "^3.4.0",
    "alpinejs": "^3.13.0",
    "chart.js": "^4.4.0"
}
```

### **CDN Resources:**

-   Chart.js: `https://cdn.jsdelivr.net/npm/chart.js`
-   Alpine.js: `https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js`
-   Fonts: `https://fonts.bunny.net/css?family=figtree:400,500,600`

---

## 🚀 Quick Start Guide untuk Development Selanjutnya

### **1. Setup Environment:**

```bash
cd c:\laragon\www\Livora
composer install
npm install
php artisan serve
```

### **2. Priority Development Order:**

1. **Create Missing View Files** - Mulai dengan admin views
2. **Fix UI Consistency** - Standardisasi design system
3. **Complete CRUD Operations** - Pastikan semua functionality berfungsi
4. **Implement Mitra Section** - Complete owner/mitra features
5. **Implement Tenant Section** - Complete tenant features
6. **Add Data Seeding** - Populate sample data
7. **Testing & Optimization** - Add tests dan optimize performance

### **3. Development Tools:**

-   **Database**: phpMyAdmin via Laragon
-   **Server**: Laravel Artisan serve
-   **IDE**: VS Code dengan Laravel extensions
-   **Browser DevTools**: Untuk debugging frontend issues

---

## 📞 Contact & Notes(Local) / Railway (Production)

**Development Date**: November - December 2025  
**Last Update**: December 21, 2025  
**Status**: ✅ Production Ready - Deployed on Railway  
**Production URL**: https://livora-web-app-production.up.railway.app  
**API Endpoint**: https://livora-web-app-production.up.railway.app/api/v1
**Database**: MySQL via Laragon  
**Development Date**: November 2025  
**Status**: In Development - Core structure complete, features need implementation

**Next Developer Notes:**

-   Fokus pada consistency dan user experience
-   Prioritaskan admin section karena paling kompleks
-   Gunakan existing controller structure sebagai template
-   Test setiap fitur sebelum pindah ke yang lain
-   Dokumentasikan setiap perubahan major

---

_Dokumentasi ini dibuat untuk memudahkan development selanjutnya. Update dokumentasi ini setiap kali ada perubahan major pada system architecture atau feature implementation._
