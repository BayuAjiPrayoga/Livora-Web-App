# Laporan Ujian Tengah Semester
## Implementasi Sistem Enterprise (CRM) pada Platform Manajemen Properti "LIVORA"

---

## 1. Latar Belakang dan Tujuan Sistem

### a. Permasalahan Utama dalam Organisasi

Industri pengelolaan properti sewa, khususnya kos (boarding house), secara tradisional masih mengandalkan proses manual yang tidak efisien. Permasalahan utama yang ingin diselesaikan oleh sistem Livora adalah:

1. **Fragmentasi Informasi**  
   Data properti, ketersediaan kamar, informasi penyewa, dan riwayat pembayaran tersebar dan tidak terpusat, menyulitkan pemilik (Mitra) untuk mengelola bisnisnya.

2. **Proses Booking & Pembayaran Manual**  
   Calon penyewa (Tenant) kesulitan mengetahui ketersediaan kamar secara *real-time*. Proses booking, konfirmasi, dan verifikasi pembayaran yang dilakukan melalui chat atau telepon memakan waktu dan rentan terhadap kesalahan (human error).

3. **Penanganan Keluhan yang Lambat**  
   Keluhan atau permintaan perbaikan dari penyewa seringkali tidak terdokumentasi dengan baik, menyebabkan respons yang lambat dan menurunkan tingkat kepuasan pelanggan.

4. **Kesulitan dalam Pengambilan Keputusan**  
   Tanpa data yang terstruktur, pemilik properti tidak dapat menganalisis performa bisnisnya, seperti tingkat hunian (okupansi), pendapatan bulanan, atau efektivitas pemasaran.

### b. Peningkatan Efisiensi dan Pengambilan Keputusan dengan Sistem Enterprise (CRM)

Sistem Livora dirancang sebagai platform CRM terpusat yang mengintegrasikan semua pemangku kepentingan (Tenant, Mitra, Admin) untuk meningkatkan efisiensi dan pengambilan keputusan:

- **Efisiensi Operasional**  
  Mengotomatiskan alur kerja mulai dari pencarian properti, booking online, konfirmasi pembayaran, hingga penjadwalan status sewa. Ini mengurangi beban kerja administratif bagi Mitra dan Admin, serta memberikan pengalaman yang lancar bagi Tenant.

- **Sentralisasi Data Pelanggan (360-Degree View)**  
  Setiap interaksi Tenant—mulai dari booking, riwayat pembayaran, hingga tiket keluhan—tercatat dalam satu sistem. Ini memberikan pandangan menyeluruh bagi Mitra untuk memberikan layanan yang lebih personal dan proaktif.

- **Pengambilan Keputusan Berbasis Data**  
  Dashboard analitik menyediakan metrik kunci seperti tingkat okupansi, tren pendapatan, dan performa properti. Mitra dapat menggunakan data ini untuk strategi penetapan harga, promosi, atau perbaikan layanan. Admin dapat memonitor kesehatan ekosistem secara keseluruhan.

---

## 2. Analisis Kebutuhan Sistem

### a. Kebutuhan Fungsional (Fitur Utama)

Sistem Livora memiliki fitur-fitur utama yang mencerminkan kebutuhan fungsional dari sebuah platform CRM properti:

1. **Manajemen Properti & Kamar**  
   Mitra dapat mendaftarkan properti, mengelola detail kamar, harga, dan fasilitas.

2. **Manajemen Booking**  
   Tenant dapat melakukan booking, dan Mitra dapat mengonfirmasi atau menolak booking tersebut.

3. **Manajemen Pembayaran**  
   Tenant dapat mengunggah bukti bayar, dan Mitra dapat melakukan verifikasi. Sistem juga mendukung pembuatan kwitansi.

4. **Sistem Tiket (Helpdesk)**  
   Tenant dapat membuat tiket keluhan atau permintaan, yang kemudian dikelola oleh Mitra.

5. **Manajemen Pengguna & Hak Akses**  
   Admin dapat mengelola semua pengguna (Admin, Mitra, Tenant) di dalam sistem.

6. **Pelaporan & Analitik**  
   Dashboard untuk Mitra dan Admin yang menampilkan laporan pendapatan, okupansi, dan data statistik lainnya.

7. **Otomatisasi Status**  
   Sistem secara otomatis mengubah status booking dari `confirmed` menjadi `active` pada tanggal check-in, mengurangi intervensi manual.

### b. Kebutuhan Non-Fungsional

- **Keamanan**  
  Sistem menggunakan framework Laravel yang memiliki fitur keamanan bawaan seperti proteksi terhadap CSRF, SQL Injection, dan XSS. Otentikasi dan otorisasi berbasis peran (*role-based access control*) memastikan setiap pengguna hanya bisa mengakses data yang menjadi haknya.

- **Kinerja**  
  Penggunaan Vite untuk kompilasi aset frontend dan penjadwalan tugas (*scheduler*) yang berjalan di latar belakang memastikan aplikasi tetap responsif. Database diindeks untuk mempercepat proses query.

- **Skalabilitas**  
  Arsitektur modular pada Laravel memungkinkan penambahan fitur baru di masa depan tanpa mengganggu sistem yang sudah ada. Aplikasi ini dapat di-deploy di lingkungan cloud yang skalabel untuk menangani penambahan jumlah pengguna dan data.

### c. Identifikasi Pengguna Sistem dan Hak Akses (Aktor)

1. **Admin**  
   Memiliki hak akses penuh ke seluruh sistem. Dapat mengelola pengguna, memverifikasi properti Mitra, memonitor semua transaksi, dan melihat laporan global.

2. **Mitra (Owner)**  
   Dapat mengelola properti dan kamar miliknya sendiri, mengonfirmasi booking, memverifikasi pembayaran dari penyewanya, dan menanggapi tiket keluhan.

3. **Tenant (Penyewa)**  
   Dapat mencari properti, melakukan booking, mengunggah bukti pembayaran, dan membuat tiket keluhan untuk properti yang disewanya.

4. **Guest (Pengguna Publik)**  
   Hanya dapat melihat dan mencari properti yang tersedia tanpa bisa melakukan transaksi.

---

## 3. Desain Awal Sistem (Prototype/Diagram)

### a. Diagram Arsitektur Sistem (High-Level Architecture)

Sistem Livora mengadopsi arsitektur **Three-Tier** yang umum digunakan pada aplikasi web modern.

```
+-----------------------------------------------------------------+
|                        PENGGUNA (AKTOR)                         |
| (Admin, Mitra, Tenant, Guest via Web Browser)                   |
+-----------------------------------------------------------------+
             | (HTTPS Request)
             v
+-----------------------------------------------------------------+
| Tier 1: PRESENTATION LAYER (Frontend)                           |
|-----------------------------------------------------------------|
| - Blade Templates (HTML Rendering)                              |
| - Tailwind CSS (Styling)                                        |
| - Alpine.js / JavaScript (Interaktivitas)                       |
+-----------------------------------------------------------------+
             | (Controller Actions, API Calls)
             v
+-----------------------------------------------------------------+
| Tier 2: LOGIC LAYER (Backend) - LARAVEL FRAMEWORK               |
|-----------------------------------------------------------------|
| - Routing: Mengarahkan request ke controller yang sesuai.       |
| - Controllers: Memproses logika bisnis (Booking, Payment, etc). |
| - Models (Eloquent): Berinteraksi dengan database.              |
| - Middleware: Menangani otentikasi & otorisasi (hak akses).     |
| - Scheduler: Menjalankan tugas otomatis (update status).        |
+-----------------------------------------------------------------+
             | (SQL Queries via Eloquent ORM)
             v
+-----------------------------------------------------------------+
| Tier 3: DATA LAYER (Database)                                   |
|-----------------------------------------------------------------|
| - MySQL Database                                                |
|   - Tabel: users, boarding_houses, rooms, bookings, payments,   |
|     tickets, facilities, dll.                                   |
+-----------------------------------------------------------------+
```

### b. Tiga Modul Terintegrasi

Proyek Livora memiliki lebih dari tiga modul yang saling terintegrasi. Tiga modul utamanya adalah:

#### 1. Modul Manajemen Booking
- **Aktor:** Tenant, Mitra, Admin
- **Fungsi:** Tenant membuat booking, yang kemudian masuk ke antrean Mitra. Mitra mengonfirmasi booking, yang mengubah statusnya. Sistem Scheduler kemudian mengambil alih untuk aktivasi otomatis.

#### 2. Modul Manajemen Pembayaran
- **Aktor:** Tenant, Mitra, Admin
- **Fungsi:** Terhubung langsung dengan Modul Booking. Setelah booking dikonfirmasi, sistem menghasilkan tagihan. Tenant mengunggah bukti bayar, yang kemudian diverifikasi oleh Mitra.

#### 3. Modul Layanan Pelanggan (Ticketing)
- **Aktor:** Tenant, Mitra, Admin
- **Fungsi:** Terhubung dengan data Tenant dan Properti. Tenant hanya bisa membuat tiket untuk properti yang sedang ia sewa. Tiket ini langsung masuk ke dashboard Mitra yang bersangkutan.

### c. Hubungan Antar Modul dan Alur Data

Alur data utama menggambarkan siklus hidup pelanggan (penyewa):

```
Pencarian Properti (Guest) 
  → Membuat Booking (Tenant) 
  → [MODUL BOOKING] 
  → Konfirmasi oleh Mitra 
  → [MODUL PEMBAYARAN] 
  → Tenant Membayar 
  → Verifikasi oleh Mitra 
  → Booking menjadi "Confirmed" 
  → [SCHEDULER] 
  → Tanggal Check-in Tiba 
  → Status menjadi "Active" 
  → Selama Masa Sewa, Tenant membuat keluhan 
  → [MODUL TICKETING] 
  → Mitra Merespons
```

Data mengalir secara linear dan logis. ID Booking menjadi kunci utama yang menghubungkan data di modul pembayaran, sementara ID User dan ID Properti menghubungkan data di modul ticketing.

---

## 4. Integrasi Teknologi

### a. Teknologi yang Digunakan

- **Web App & Framework**  
  Aplikasi web dibangun menggunakan **Laravel 11 (PHP)**, sebuah framework modern yang menyediakan struktur MVC (Model-View-Controller).

- **Database**  
  Menggunakan **MySQL** sebagai sistem manajemen database relasional untuk menyimpan semua data transaksional dan operasional.

- **Frontend Technology**  
  **Blade** sebagai *templating engine*, **Tailwind CSS** untuk styling, dan **JavaScript (Alpine.js)** untuk interaktivitas frontend.

- **API**  
  Meskipun saat ini belum terekspos secara publik, arsitektur Laravel memungkinkan pembuatan **RESTful API** di masa depan untuk mendukung aplikasi mobile. Interaksi frontend (seperti verifikasi pembayaran) sudah menggunakan **Fetch API** untuk komunikasi *asynchronous*.

### b. Hubungan dengan Layanan Cloud

Saat ini sistem berjalan di lingkungan development lokal (Laragon). Namun, arsitektur ini **siap untuk di-deploy ke layanan cloud** seperti AWS, Google Cloud, atau DigitalOcean.

- **Deployment**  
  Kode dapat di-hosting di server virtual (misal: AWS EC2, DigitalOcean Droplet).

- **Database as a Service (DBaaS)**  
  Database MySQL dapat dipindahkan ke layanan terkelola seperti **Amazon RDS** atau **Google Cloud SQL** untuk meningkatkan keandalan, skalabilitas, dan kemudahan backup.

- **File Storage**  
  Penyimpanan file (gambar properti, bukti bayar) saat ini menggunakan disk lokal (`storage:link`). Ini dapat dengan mudah diubah untuk menggunakan layanan *object storage* seperti **Amazon S3** dengan mengubah satu baris konfigurasi di Laravel, memungkinkan skalabilitas penyimpanan yang hampir tak terbatas.

---

## 5. Rencana Implementasi

### a. Timeline Pengerjaan hingga UAS

Proyek ini telah melalui beberapa fase pengembangan dan perbaikan. Rencana selanjutnya hingga Ujian Akhir Semester (UAS) adalah:

- **Minggu 1-2**  
  Finalisasi dan pengujian menyeluruh pada semua fitur yang ada di role Admin dan Mitra. Memastikan tidak ada bug kritikal pada alur booking dan pembayaran.

- **Minggu 3**  
  Penyempurnaan UI/UX pada sisi Tenant. Memastikan alur pencarian, booking, dan pembayaran sangat intuitif dan mudah digunakan.

- **Minggu 4**  
  Implementasi fitur minor yang tertunda, seperti notifikasi real-time dan penyempurnaan modul laporan (menambahkan lebih banyak filter dan grafik).

- **Minggu 5-6**  
  Pengujian akhir (UAT - User Acceptance Testing) dengan skenario pengguna nyata, optimasi *query* database, dan persiapan dokumentasi akhir untuk UAS.

### b. Pembagian Tugas dalam Tim Proyek

Proyek ini dikerjakan oleh tim yang terdiri dari 5 orang dengan pembagian tugas sebagai berikut:

#### 1. Bayu Aji Prayoga - Frontend Developer & UI/UX Designer
**Tanggung Jawab:**
- Desain UI/UX dan wireframing untuk semua role (Admin, Mitra, Tenant)
- Implementasi frontend menggunakan Blade templates
- Styling dengan Tailwind CSS dan responsiveness
- Integrasi Alpine.js untuk interaktivitas
- Pembuatan dashboard analitik untuk Mitra dan Admin

#### 2. Damar Satriatama Putra - Backend Developer (Lead)
**Tanggung Jawab:**
- Implementasi arsitektur Laravel dan struktur MVC
- Pengembangan modul manajemen booking dan pembayaran
- Implementasi authentication, authorization, dan middleware
- Integrasi dengan database MySQL dan query optimization
- Pembuatan scheduler untuk otomatisasi status booking

#### 3. Fauzi Rikhsana - Backend Developer & API Specialist
**Tanggung Jawab:**
- Pengembangan modul manajemen properti dan kamar
- Implementasi sistem tiket (helpdesk) dan handling keluhan
- Pembuatan API endpoints untuk komunikasi frontend-backend
- Database design dan implementasi migration & seeder
- Implementasi fitur upload file (gambar properti, bukti bayar)

#### 4. Nazwa Khoerunnisa - Project Manager & System Analyst
**Tanggung Jawab:**
- Koordinasi keseluruhan proyek dan manajemen timeline
- Analisis kebutuhan sistem dan dokumentasi requirements
- Pembuatan use case diagram, flowchart, dan dokumentasi teknis
- Quality assurance dan testing integrasi antar modul
- Penyusunan laporan UTS dan UAS

#### 5. Sofia Risa Aulia - Database Administrator & QA Tester
**Tanggung Jawab:**
- Database design, normalisasi, dan relationship mapping
- Implementasi indexing dan optimasi query performance
- Setup backup dan recovery procedures
- User Acceptance Testing (UAT) untuk semua fitur
- Bug tracking, reporting, dan koordinasi perbaikan

---

## Kesimpulan

Platform Livora merupakan implementasi sistem Enterprise CRM yang dirancang khusus untuk industri manajemen properti sewa. Dengan mengintegrasikan tiga pemangku kepentingan utama (Admin, Mitra, dan Tenant) dalam satu ekosistem digital, sistem ini berhasil mengatasi permasalahan fragmentasi informasi, proses manual yang tidak efisien, dan kesulitan dalam pengambilan keputusan berbasis data.

Melalui pendekatan arsitektur three-tier dan teknologi modern (Laravel, MySQL, Tailwind CSS), Livora menyediakan solusi yang scalable, secure, dan user-friendly untuk transformasi digital dalam pengelolaan properti kos.

---

**Disusun oleh:** [Nama Mahasiswa]  
**NIM:** [Nomor Induk Mahasiswa]  
**Mata Kuliah:** Sistem Enterprise  
**Tanggal:** November 2025