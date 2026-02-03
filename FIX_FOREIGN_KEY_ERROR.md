# ⚠️ ERROR FOREIGN KEY CONSTRAINT - SOLUSI

## 🔴 Error Yang Terjadi:

```
#1452 - Cannot add or update a child row: a foreign key constraint fails
```

## 💡 Penyebab:

Property dengan ID yang Anda masukkan **TIDAK ADA** di database production.

Contoh: Anda coba insert room dengan `boarding_house_id = 4`, tapi property ID 4 tidak ada di tabel `boarding_houses`.

---

## ✅ SOLUSI - Ikuti Step by Step

### STEP 1: Cek Property Yang Ada ⭐

Jalankan di phpMyAdmin:

```sql
SELECT id, name, user_id FROM boarding_houses ORDER BY id;
```

**Catat hasil**: Property ID berapa saja yang ada? (contoh: 1, 2, 3)

---

### STEP 2: Pilih Solusi Yang Sesuai

#### **OPTION A: Insert Rooms untuk Property Yang Ada** (RECOMMENDED)

File: `insert_rooms.sql` (sudah diupdate)

Gunakan **OPTION A** yang hanya insert untuk property 1, 2, 3:

```sql
INSERT INTO `rooms` (...) VALUES
-- Property 1
(1, 'Kamar A1', ...),
(1, 'Kamar A2', ...),
-- Property 2
(2, 'Kamar B1', ...),
-- Property 3
(3, 'Kamar C1', ...);
```

✅ **SAFE**: Tidak akan error karena hanya insert untuk property yang ada

---

#### **OPTION B: Tambah Property Yang Kurang** (If needed)

Jika Anda ingin punya property 4 dan 5:

1. **Cek owner yang ada**:

```sql
SELECT id, name, email FROM users WHERE role = 'owner';
```

2. **Insert property baru** (ganti user_id sesuai owner yang ada):

```sql
-- File: insert_missing_properties.sql
INSERT INTO boarding_houses (user_id, name, slug, address, city, ...) VALUES
(3, 'Kost Kenanga Yogyakarta', 'kost-kenanga-yogyakarta', ...);
```

3. **Setelah itu baru insert rooms** untuk property baru tersebut

---

## 📋 FILES YANG SUDAH DIUPDATE:

1. ✅ `check_properties.sql` - Cek property yang ada
2. ✅ `insert_rooms.sql` - SAFE version (hanya property 1-3)
3. ✅ `insert_missing_properties.sql` - Insert property 4-5 (optional)

---

## 🎯 LANGKAH AMAN (RECOMMENDED):

### 1. Cek Property:

```sql
-- Di phpMyAdmin
SELECT id, name FROM boarding_houses ORDER BY id;
```

### 2. Insert Rooms (SAFE):

Jalankan **OPTION A** dari `insert_rooms.sql`:

- Hanya untuk property yang BENAR-BENAR ada
- Tidak akan error foreign key

### 3. Verifikasi:

```sql
SELECT COUNT(*) FROM rooms;
-- Harusnya ada 9 rooms (3 per property)
```

---

## 🚨 TROUBLESHOOTING

### Q: Kenapa property 4 dan 5 tidak ada?

**A**: Database production Anda mungkin hanya di-seed sebagian. Ini normal.

### Q: Apakah perlu property 4 dan 5?

**A**: TIDAK. Property 1-3 sudah cukup untuk testing. Tambah nanti via UI kalau perlu.

### Q: Tetap mau tambah property 4 dan 5?

**A**: Gunakan `insert_missing_properties.sql`, tapi:

1. Cek dulu user_id owner yang ada
2. Sesuaikan user_id di SQL
3. Baru jalankan insert

---

## ✅ QUICK FIX (5 Menit):

1. Buka `insert_rooms.sql` yang baru
2. Jalankan **OPTION A** di phpMyAdmin (property 1-3 only)
3. ✅ Done! Rooms berhasil ditambahkan tanpa error

Sekarang coba test create room lagi di UI! 🚀
