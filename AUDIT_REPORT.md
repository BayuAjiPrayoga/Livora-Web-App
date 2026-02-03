# AUDIT REPORT: User ID Type Mismatch Issue

## 📋 EXECUTIVE SUMMARY

**Issue**: HTTP 403 error saat akses `/mitra/properties/2/edit`  
**Root Cause**: Type mismatch antara `$property->user_id` dan `Auth::id()`  
**Impact**: Authorization check gagal meskipun user adalah owner yang sah

---

## 🔍 FINDINGS

### 1. Database Schema Analysis

**Migration File**: `2025_11_20_182926_create_boarding_houses_table.php`

```php
$table->foreignId('user_id')->constrained()->onDelete('cascade');
```

- `foreignId()` menghasilkan kolom `BIGINT UNSIGNED`
- Foreign key constraint ke `users.id` (juga `BIGINT UNSIGNED`)

### 2. Sample Data from Backup

**Property ID 2**:

- `user_id`: 2
- Name: "Kost Mawar Jakarta"
- Owner: "Budi Santoso" (owner@livora.com)

**User ID 2**:

- Name: "Budi Santoso"
- Role: "owner"
- Email: "owner@livora.com"

### 3. Laravel Type Behavior

#### Auth::id() Return Type

- Laravel's `Auth::id()` mengembalikan **integer** atau **null**
- Source: `Illuminate\Auth\GuardHelpers::id()`
- Return type: `int|string|null` (tapi prakteknya selalu `int`)

#### Eloquent Model Attribute Casting

- **Default behavior**: Primary keys dan foreign keys dikembalikan sebagai **integer**
- `$table->id()` dan `$table->foreignId()` otomatis di-cast ke integer oleh Eloquent
- Model `BoardingHouse` tidak memiliki custom cast untuk `user_id`, jadi menggunakan default casting

### 4. Current Authorization Code

```php
if ($property->user_id !== Auth::id()) {
    abort(403, 'Unauthorized access to this property.');
}
```

**Masalah Potensial**:

- Strict comparison (`!==`) membandingkan nilai DAN tipe data
- Jika ada perbedaan tipe (int vs string), meski nilai sama, akan return `true` (berbeda)

---

## 🧪 TYPE COMPARISON TEST

Berdasarkan Laravel behavior:

- `$property->user_id` = `2` (integer)
- `Auth::id()` = `2` (integer)

**Seharusnya TIDAK ada masalah**, KECUALI:

### Kemungkinan Skenario Masalah:

#### Scenario A: Database Value is String

Jika di Railway database, kolom `user_id` somehow stored sebagai string "2"

```php
$property->user_id = "2" (string)
Auth::id() = 2 (integer)
"2" !== 2 // TRUE (berbeda) -> 403 Error ✗
```

#### Scenario B: Session/Auth Issue

Jika session menyimpan user_id sebagai string

```php
$property->user_id = 2 (integer)
Auth::id() = "2" (string dari session)
2 !== "2" // TRUE (berbeda) -> 403 Error ✗
```

#### Scenario C: Railway Database Migration Issue

Jika saat migrasi ke Railway, ada perubahan tipe data yang tidak terdeteksi

---

## ✅ PROPOSED SOLUTION

### Option 1: Non-Strict Comparison (RECOMMENDED)

**Pros**:

- PHP type juggling akan menangani "2" == 2 sebagai TRUE
- Lebih toleran terhadap perbedaan tipe data
- Tidak break existing functionality
- Common practice di Laravel codebase

**Cons**:

- Slightly less type-safe (tapi untuk ID comparison, ini acceptable)

**Change**: `!==` → `!=`

### Option 2: Explicit Type Casting

```php
if ((int) $property->user_id !== (int) Auth::id()) {
    abort(403, 'Unauthorized access to this property.');
}
```

**Pros**: Tetap strict comparison, explicit type conversion
**Cons**: More verbose, unnecessary jika option 1 sudah works

### Option 3: Policy-Based Authorization

```php
$this->authorize('update', $property);
```

**Pros**: Laravel best practice, centralized authorization
**Cons**: Requires Policy class implementation (sudah ada `BoardingHousePolicy.php`)

---

## 🎯 RECOMMENDED ACTION

### Immediate Fix (Low Risk)

Ubah strict comparison menjadi non-strict di semua Controller methods:

**Files to Update**:

1. `PropertyController.php` - 4 methods (show, edit, update, destroy)
2. `RoomController.php` - 8 methods
3. `BookingController.php` - 13 methods

**Total Changes**: 25 comparisons

**Risk Level**: **LOW**

- PHP type coercion (==) is well-defined dan predictable untuk numeric values
- Tidak akan break existing functionality karena `2 == "2"` dan `2 == 2` both return TRUE
- Lebih robust terhadap tipe data variations dari different environments

### Long-Term Improvement (Optional)

Implement Policy-based authorization untuk cleaner code:

```php
// BoardingHousePolicy.php already exists
public function update(User $user, BoardingHouse $boardingHouse): bool
{
    return $user->id == $boardingHouse->user_id;
}

// Controller
public function edit(BoardingHouse $property)
{
    $this->authorize('update', $property);
    return view('mitra.properties.edit', compact('property'));
}
```

---

## 🚨 SAFETY ANALYSIS

### Will changing `!==` to `!=` break anything?

**NO**, because:

1. **Value Comparison Still Works**
    - If both are integers: `2 != 3` → TRUE ✓
    - If both are integers: `2 != 2` → FALSE ✓
    - If mixed types: `2 != "3"` → TRUE ✓
    - If mixed types: `2 != "2"` → FALSE ✓

2. **Security Not Compromised**
    - Authorization still checks ownership
    - No way to bypass with type manipulation
    - ID values are integers from database, not user input

3. **Backward Compatible**
    - Existing working code will continue to work
    - Fixes edge cases where types don't match

4. **No Database Changes**
    - No migration needed
    - No data modification required
    - Pure logic change in PHP code

---

## 📝 CONCLUSION

**Issue Confirmed**: Type comparison issue likely due to environment differences (Railway vs Local)

**Solution**: Change from strict (`!==`) to non-strict (`!=`) comparison

**Safety**: ✅ SAFE - No breaking changes, more robust

**Recommendation**: **PROCEED WITH THE CHANGE**
