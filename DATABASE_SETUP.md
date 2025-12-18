# Database Setup Guide

## Kredensial Database

**Database:** `livora`  
**User:** `root`  
**Password:** (kosong)

---

## Users Login

### Admin

-   **Email:** `admin@livora.com`
-   **Password:** `admin123`
-   **Role:** Admin

### Owner (Mitra)

-   **Email:** `owner@livora.com`
-   **Password:** `owner123`
-   **Role:** Owner

-   **Email:** `owner2@livora.com`
-   **Password:** `owner123`
-   **Role:** Owner

### Tenant (Penyewa)

-   **Email:** `tenant@livora.com`
-   **Password:** `tenant123`
-   **Role:** Tenant

-   **Email:** `tenant2@livora.com`
-   **Password:** `tenant123`
-   **Role:** Tenant

---

## Reset Database

Untuk mereset database ke kondisi awal dengan data minimal:

```bash
# Jalankan migrasi fresh
php artisan migrate:fresh

# Import data minimal
mysql -u root livora < database_clean_minimal.sql
```

Atau gunakan PowerShell:

```powershell
cmd /c "mysql -u root livora < database_clean_minimal.sql"
```

---

## Data yang Tersedia

-   **5 Users** (1 Admin, 2 Owner, 2 Tenant)
-   **5 Boarding Houses** (Kost)
-   **5 Rooms** (Kamar)
-   **5 Bookings** dengan berbagai status
-   **5 Payments** dengan berbagai status
-   **5 Tickets** (Komplain)
-   **5 Notifications**
-   **5 Facilities**

---

## File Database

-   **`database_clean_minimal.sql`** - Data minimal untuk development
-   **`database_clean_backup_[timestamp].sql`** - Backup otomatis

---

## Catatan

File backup database (`.sql`) tidak di-commit ke Git. Lihat `.gitignore` untuk detail.
