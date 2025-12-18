# Livora UI Components Guide

## 🎨 Quick Reference untuk Developer

Panduan cepat menggunakan komponen UI modern Livora tanpa merusak fungsi existing.

---

## 🔘 Buttons

### Primary Button (Orange Gradient)

```blade
<a href="#" class="btn btn-primary">
    Simpan Data
</a>
```

### Secondary Button (Gray)

```blade
<button type="button" class="btn btn-secondary">
    Batal
</button>
```

### Outline Button

```blade
<a href="#" class="btn btn-outline">
    Lihat Detail
</a>
```

### Success & Danger

```blade
<button class="btn btn-success">Approve</button>
<button class="btn btn-danger">Reject</button>
```

### Button Sizes

```blade
<button class="btn btn-primary btn-sm">Small</button>
<button class="btn btn-primary">Normal</button>
<button class="btn btn-primary btn-lg">Large</button>
```

---

## 📦 Cards

### Modern Card

```blade
<div class="modern-card">
    <h3 class="text-lg font-bold mb-2">Judul Card</h3>
    <p class="text-gray-600">Konten card...</p>
</div>
```

### Card with Hover Effect

```blade
<div class="modern-card modern-card-hover">
    <!-- Akan lift up saat di-hover -->
    <p>Hover me!</p>
</div>
```

### Glass Card (Efek kaca blur)

```blade
<div class="glass-card">
    <p class="text-gray-800">Glassmorphism effect</p>
</div>
```

### Gradient Card

```blade
<div class="gradient-card">
    <h4 class="font-bold text-orange-700">Featured</h4>
    <p class="text-gray-700">Orange gradient background</p>
</div>
```

### Stats Card (untuk dashboard)

```blade
<div class="stats-card">
    <h3 class="text-gray-500 text-sm font-medium">Total Booking</h3>
    <p class="text-3xl font-bold text-gray-900">150</p>
    <span class="text-emerald-600 text-sm">↑ 12% dari bulan lalu</span>
</div>
```

---

## 📝 Forms

### Input Field

```blade
<div>
    <label class="form-label">Nama Lengkap</label>
    <input type="text" class="form-input" placeholder="Masukkan nama">
</div>
```

### Select Dropdown

```blade
<select class="form-select">
    <option>Pilih kategori</option>
    <option>Putra</option>
    <option>Putri</option>
</select>
```

### Textarea

```blade
<textarea class="form-textarea" placeholder="Deskripsi..."></textarea>
```

### Checkbox & Radio

```blade
<input type="checkbox" class="form-checkbox">
<input type="radio" class="form-radio" name="gender">
```

### Floating Label Input (Modern)

```blade
<div class="floating-input">
    <input type="text" id="email" placeholder=" ">
    <label for="email">Email Address</label>
</div>
```

---

## 🏷️ Badges

```blade
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Verified</span>
<span class="badge badge-warning">Pending</span>
<span class="badge badge-danger">Rejected</span>
<span class="badge badge-info">Info</span>
<span class="badge badge-gray">Inactive</span>
```

**Use Case:**

```blade
<!-- Status booking -->
<span class="badge badge-success">✓ Aktif</span>

<!-- Status payment -->
@if($payment->status === 'verified')
    <span class="badge badge-success">Verified</span>
@elseif($payment->status === 'pending')
    <span class="badge badge-warning">Pending</span>
@else
    <span class="badge badge-danger">Rejected</span>
@endif
```

---

## 📊 Tables

### Modern Table

```blade
<table class="modern-table">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>John Doe</td>
            <td>john@example.com</td>
            <td><span class="badge badge-success">Active</span></td>
        </tr>
    </tbody>
</table>
```

### Striped Table

```blade
<table class="modern-table table-striped">
    <!-- Baris genap akan berwarna abu-abu muda -->
</table>
```

---

## 🧭 Navigation

### Nav Link (untuk sidebar)

```blade
<a href="{{ route('mitra.dashboard') }}"
   class="nav-link {{ request()->routeIs('mitra.dashboard') ? 'active' : '' }}">
    <svg><!-- icon --></svg>
    <span>Dashboard</span>
</a>
```

**Active State:** Otomatis gradient orange dengan shadow jika class `active` ditambahkan.

---

## ✨ Animations

### Fade In

```blade
<div class="animate-fade-in">
    <!-- Konten yang muncul dengan fade -->
</div>
```

### Slide Up

```blade
<div class="animate-slide-up">
    <!-- Slide dari bawah -->
</div>
```

### Scale In

```blade
<div class="animate-scale-in">
    <!-- Zoom in smooth -->
</div>
```

---

## 🎭 Loading States

### Skeleton Loader

```blade
<!-- Skeleton untuk teks -->
<div class="skeleton-text"></div>
<div class="skeleton-text w-3/4"></div>

<!-- Skeleton untuk card -->
<div class="skeleton h-32 rounded-2xl"></div>
```

### Loading State (disable interaction)

```blade
<div class="loading">
    <!-- Konten yang sedang loading (opacity 50%, no pointer events) -->
</div>
```

---

## 📭 Empty State

```blade
<div class="empty-state">
    <svg class="empty-state-icon">
        <path d="...inbox icon..."/>
    </svg>
    <h3 class="empty-state-title">Belum Ada Data</h3>
    <p class="empty-state-text">
        Anda belum memiliki booking apapun.
        Mulai dengan membuat booking pertama Anda.
    </p>
    <a href="#" class="btn btn-primary mt-4">Buat Booking Baru</a>
</div>
```

---

## 🎨 Utility Classes

### Gradient Text

```blade
<h1 class="text-4xl font-bold gradient-text">
    Welcome to Livora
</h1>
```

### Glassmorphism

```blade
<div class="glass p-6 rounded-2xl">
    Transparent blurred background
</div>
```

### Hover Lift Effect

```blade
<div class="card hover-lift">
    <!-- Card akan naik sedikit saat di-hover -->
</div>
```

### Custom Scrollbar

```blade
<div class="scrollbar-thin overflow-y-auto max-h-96">
    <!-- Konten dengan scrollbar tipis orange -->
</div>
```

### Hide Scrollbar

```blade
<div class="no-scrollbar overflow-auto">
    <!-- Scrollbar tersembunyi tapi tetap bisa scroll -->
</div>
```

### Dividers

```blade
<hr class="divider"> <!-- Horizontal divider -->
<div class="divider-vertical"></div> <!-- Vertical divider -->
```

---

## 🎨 Color Palette

### Tailwind Classes (Livora Brand)

```blade
<!-- Background -->
<div class="bg-livora-500">Primary Orange</div>
<div class="bg-livora-50">Very Light Orange</div>
<div class="bg-livora-900">Very Dark Orange</div>

<!-- Text -->
<p class="text-livora-600">Dark Orange Text</p>

<!-- Border -->
<div class="border-2 border-livora-500">Orange Border</div>

<!-- Gradient -->
<div class="bg-gradient-to-r from-livora-500 to-livora-300">
    Orange Gradient
</div>
```

### Legacy Support

```blade
<!-- Masih support untuk backward compatibility -->
<div class="text-livora-primary">Var(--livora-primary)</div>
<div class="bg-livora-primary">Orange Background</div>
```

---

## 📱 Responsive Container

```blade
<div class="container-custom">
    <!-- Max width 7xl, responsive padding -->
    <h1>Konten Container</h1>
</div>
```

---

## 🖨️ Print Utilities

```blade
<!-- Hide saat print -->
<button class="no-print">Download PDF</button>

<!-- Full width saat print -->
<table class="print-full-width">
    <!-- Table akan full width saat di-print -->
</table>
```

---

## 💡 Tips & Best Practices

### 1. **Gunakan Modern Classes untuk UI Baru**

```blade
<!-- ❌ Old way -->
<div class="bg-white rounded-lg shadow p-4">

<!-- ✅ Modern way -->
<div class="modern-card">
```

### 2. **Backward Compatibility**

Semua class lama masih berfungsi:

-   `.card` → Masih bekerja dengan style lama
-   `.btn` → Masih bekerja dengan enhancement baru
-   `.badge-error` → Alias untuk `.badge-danger`

### 3. **Combine dengan Tailwind**

```blade
<button class="btn btn-primary mt-4 w-full">
    <!-- btn-primary (custom) + mt-4 w-full (tailwind) -->
    Submit Form
</button>
```

### 4. **Animation Usage**

Gunakan animation untuk:

-   Page load (fade-in, slide-up)
-   Modal appearances (scale-in)
-   Feedback interactions

```blade
<div x-show="open"
     x-transition:enter="animate-scale-in"
     class="modal">
    <!-- Alpine.js + custom animation -->
</div>
```

---

## 🚀 Quick Start Examples

### Dashboard Stats Grid

```blade
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="stats-card animate-fade-in">
        <h3 class="text-gray-500 text-sm font-medium">Total Booking</h3>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalBookings }}</p>
        <span class="text-emerald-600 text-sm mt-1">↑ 12%</span>
    </div>

    <div class="stats-card animate-fade-in" style="animation-delay: 0.1s">
        <h3 class="text-gray-500 text-sm font-medium">Revenue</h3>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $revenue }}</p>
        <span class="text-emerald-600 text-sm mt-1">↑ 8%</span>
    </div>
    <!-- More stats... -->
</div>
```

### Modern Form

```blade
<form class="modern-card max-w-2xl animate-slide-up">
    <h2 class="text-2xl font-bold gradient-text mb-6">Create New Booking</h2>

    <div class="space-y-4">
        <div>
            <label class="form-label">Tenant Name</label>
            <input type="text" class="form-input" placeholder="John Doe">
        </div>

        <div>
            <label class="form-label">Room Type</label>
            <select class="form-select">
                <option>Select room type</option>
                <option>Single Room</option>
                <option>Shared Room</option>
            </select>
        </div>

        <div>
            <label class="form-label">Notes</label>
            <textarea class="form-textarea" placeholder="Additional notes..."></textarea>
        </div>
    </div>

    <div class="flex gap-3 mt-6">
        <button type="submit" class="btn btn-primary">Save Booking</button>
        <a href="#" class="btn btn-secondary">Cancel</a>
    </div>
</form>
```

### List dengan Empty State

```blade
@if($bookings->isEmpty())
    <div class="empty-state">
        <svg class="empty-state-icon" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
        </svg>
        <h3 class="empty-state-title">No Bookings Yet</h3>
        <p class="empty-state-text">
            You don't have any bookings. Start by creating your first booking.
        </p>
        <a href="{{ route('bookings.create') }}" class="btn btn-primary mt-4">
            Create Booking
        </a>
    </div>
@else
    <div class="grid gap-4">
        @foreach($bookings as $booking)
            <div class="modern-card modern-card-hover">
                <!-- Booking card content -->
            </div>
        @endforeach
    </div>
@endif
```

---

## 🔄 Migration dari UI Lama

### Button Migration

```blade
<!-- Before -->
<button class="bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded text-white">
    Submit
</button>

<!-- After (lebih simple) -->
<button class="btn btn-primary">
    Submit
</button>
```

### Card Migration

```blade
<!-- Before -->
<div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
    Content
</div>

<!-- After -->
<div class="modern-card">
    Content
</div>
```

### Badge Migration

```blade
<!-- Before -->
<span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
    Active
</span>

<!-- After -->
<span class="badge badge-success">
    Active
</span>
```

---

## 📚 Resources

-   **Tailwind Config:** `tailwind.config.js` - Extended dengan livora colors
-   **CSS Variables:** `resources/css/app.css` - Full design system
-   **Dokumentasi:** `UI_MODERNIZATION_PLAN.md` - Detailed plan

---

## 🎯 Common Patterns

### Dashboard Header

```blade
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }}</p>
    </div>
    <a href="#" class="btn btn-primary">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Create New
    </a>
</div>
```

### Filter Bar

```blade
<div class="modern-card mb-6">
    <div class="flex flex-wrap gap-4">
        <input type="search" class="form-input flex-1 min-w-[200px]" placeholder="Search...">
        <select class="form-select w-48">
            <option>All Status</option>
            <option>Active</option>
            <option>Inactive</option>
        </select>
        <button class="btn btn-primary">Apply Filters</button>
        <button class="btn btn-outline">Reset</button>
    </div>
</div>
```

---

**✨ Selamat menggunakan Livora Modern Design System!**

Jika ada pertanyaan atau butuh komponen baru, silakan update file ini atau hubungi team developer.
