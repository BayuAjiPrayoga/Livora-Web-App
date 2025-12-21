# Backend-Flutter Mapping - Livora

## Overview

Dokumen ini memetakan controller Laravel ke screen Flutter dan menjelaskan integrasi teknis antara backend dan mobile app.

---

## Authentication Flow

### Laravel Controller
**File**: `app/Http/Controllers/Api/AuthController.php`

**Methods**:
- `login()` - Login user
- `register()` - Register user
- `logout()` - Logout user
- `me()` - Get current user

### Flutter Screens
**Files**:
- `presentation/pages/auth/login_page.dart`
- `presentation/pages/auth/register_page.dart`
- `presentation/pages/profile/profile_page.dart`

### Integration Notes
1. **Token Storage**: Token dari response login disimpan di `FlutterSecureStorage`
2. **Auto-login**: Saat app start, cek token di storage → jika ada, panggil `/me` untuk validasi
3. **Logout**: Hapus token dari storage + panggil API logout
4. **Session Check**: Setiap kali app resume dari background, validasi token dengan `/me`

---

## Property Browsing

### Laravel Controller
**File**: `app/Http/Controllers/Api/V1/PropertyController.php`

**Methods**:
- `index()` - Get property list (paginated, searchable, filterable)
- `show($slug)` - Get property detail by slug

### Flutter Screens
**Files**:
- `presentation/pages/home/home_page.dart` - Property list
- `presentation/pages/property/property_detail_page.dart` - Property detail

### Integration Notes
1. **Pagination**: Gunakan `InfiniteScrollPagination` package untuk load more
2. **Search**: Debounce search input (500ms) sebelum hit API
3. **Filter**: Bottom sheet filter → rebuild list dengan query params baru
4. **Image Loading**: Gunakan `CachedNetworkImage` untuk cache images
5. **Slug Navigation**: Dari list → detail menggunakan slug (bukan ID)

---

## Room Management

### Laravel Controller
**File**: `app/Http/Controllers/Api/V1/RoomController.php`

**Methods**:
- `show($id)` - Get room detail

### Flutter Screens
**Files**:
- `presentation/pages/property/room_detail_page.dart` - Room detail

### Integration Notes
1. **Room Selection**: Dari property detail → room detail → create booking
2. **Availability Check**: Backend handle availability check saat create booking
3. **Facilities**: Display facilities dengan icon mapping (AC → Icons.ac_unit)

---

## Booking Management

### Laravel Controller
**File**: `app/Http/Controllers/Api/V1/BookingController.php`

**Methods**:
- `index()` - Get user bookings (tenant)
- `show($id)` - Get booking detail
- `store()` - Create booking
- `cancel($id)` - Cancel booking
- `ownerBookings()` - Get owner bookings (owner only)
- `verifyPayment($bookingId, $paymentId)` - Verify payment (owner only)
- `rejectPayment($bookingId, $paymentId)` - Reject payment (owner only)

### Flutter Screens (Tenant)
**Files**:
- `presentation/pages/booking/booking_list_page.dart` - Booking list with tabs
- `presentation/pages/booking/booking_detail_page.dart` - Booking detail
- `presentation/pages/booking/create_booking_page.dart` - Create booking form

### Flutter Screens (Owner)
**Files**:
- `presentation/pages/owner/owner_bookings_page.dart` - Owner bookings
- `presentation/pages/owner/booking_detail_page.dart` - Booking detail with verify/reject

### Integration Notes
1. **Create Booking**:
   - Form: Date picker (check-in), duration (months), KTP upload
   - Validation: Check-in >= today, duration 1-12 months, KTP required
   - Upload: Multipart form-data dengan `dio.FormData`
   
2. **Booking Status**:
   - `pending` → Yellow badge, show "Upload Payment" button
   - `confirmed` → Blue badge, show "Cancel" button
   - `active` → Green badge, show "Contact Owner"
   - `completed` → Gray badge, read-only
   - `cancelled` → Red badge, read-only

3. **Cancel Booking**:
   - Only allowed for `pending` and `confirmed` status
   - Show confirmation dialog before cancel

4. **Owner Actions**:
   - Verify payment → Booking status jadi `active`
   - Reject payment → Payment status jadi `rejected`, tenant bisa upload ulang

---

## Payment Management

### Laravel Controller
**File**: `app/Http/Controllers/Api/V1/PaymentController.php`

**Methods**:
- `index()` - Get payment history
- `store()` - Upload payment proof

### Flutter Screens
**Files**:
- `presentation/pages/payment/payment_list_page.dart` - Payment history
- `presentation/pages/payment/upload_payment_page.dart` - Upload payment proof

### Integration Notes
1. **Upload Payment**:
   - Form: Amount, proof image, notes
   - Validation: Amount <= booking final_amount, image max 5MB
   - Upload: Multipart form-data
   
2. **Payment Status**:
   - `pending` → Yellow, "Menunggu Verifikasi"
   - `verified` → Green, "Terverifikasi"
   - `rejected` → Red, "Ditolak" + show rejection notes
   - `settlement` → Green, "Berhasil" (Midtrans)
   - `expired` → Gray, "Kadaluarsa" (Midtrans)

3. **Midtrans Integration** (Future):
   - Backend generate snap_token → Flutter show Midtrans WebView
   - Callback dari Midtrans → Backend update payment status
   - Flutter poll payment status atau gunakan webhook

---

## Owner Dashboard

### Laravel Controller
**File**: `app/Http/Controllers/Api/V1/DashboardController.php`

**Methods**:
- `ownerStats()` - Get owner dashboard statistics

### Flutter Screens
**Files**:
- `presentation/pages/owner/owner_dashboard_page.dart` - Owner dashboard

### Integration Notes
1. **Stats Display**:
   - Total properties, total rooms, active bookings
   - Monthly revenue (formatted as Rupiah)
   - Occupancy rate (percentage)
   
2. **Charts** (Optional):
   - Revenue chart (fl_chart package)
   - Occupancy trend

---

## Profile Management

### Laravel Endpoint
**File**: `app/Http/Controllers/Api/AuthController.php`

**Methods**:
- `me()` - Get current user data

### Flutter Screens
**Files**:
- `presentation/pages/profile/profile_page.dart` - View profile
- `presentation/pages/profile/edit_profile_page.dart` - Edit profile

### Integration Notes
1. **Display**: Show user info (name, email, phone, role, avatar)
2. **Edit**: Update name, phone, address, avatar
3. **Avatar Upload**: Multipart form-data
4. **Change Password**: Separate endpoint (future)

---

## Error Handling Mapping

### Backend Error → Flutter Handling

| Backend Status | Backend Message | Flutter Action |
|----------------|-----------------|----------------|
| 400 | Validation error | Show field errors in form |
| 401 | Unauthenticated | Clear token, redirect to login |
| 403 | Forbidden | Show "Access denied" dialog |
| 404 | Not found | Show "Data not found" message |
| 500 | Server error | Show "Server error, try again" |

### Implementation
```dart
// In ViewModel
result.fold(
  (failure) {
    if (failure is ServerFailure) {
      _errorMessage = 'Server error, please try again';
    } else if (failure is NetworkFailure) {
      _errorMessage = 'No internet connection';
    } else if (failure is ValidationFailure) {
      _errorMessage = failure.message; // Show specific validation error
    }
    notifyListeners();
  },
  (data) {
    // Success handling
  },
);
```

---

## Data Synchronization

### Real-time Updates (Future)
1. **Booking Status**: Polling setiap 30 detik di booking detail page
2. **Payment Status**: Polling setiap 10 detik setelah upload payment
3. **Notifications**: Firebase Cloud Messaging untuk push notifications

### Offline Support (Future)
1. **Property List**: Cache di local database (Hive/Drift)
2. **Booking List**: Cache untuk offline viewing
3. **Sync**: Auto-sync saat online kembali

---

## Image Handling

### Backend Storage
- **Path**: `storage/app/public/`
- **Folders**: `properties/`, `rooms/`, `payments/`, `bookings/ktp/`
- **URL**: `https://livora-web-app-production.up.railway.app/storage/{path}`

### Flutter Implementation
1. **Display**: `CachedNetworkImage` dengan placeholder dan error widget
2. **Upload**: `ImagePicker` → compress → upload via `dio.FormData`
3. **Compression**: `flutter_image_compress` (max 1MB, quality 80%)

```dart
// Upload example
final formData = FormData.fromMap({
  'ktp_image': await MultipartFile.fromFile(
    imagePath,
    filename: 'ktp_${userId}_${timestamp}.jpg',
  ),
  'room_id': roomId,
  'start_date': startDate,
  'duration': duration,
});

final response = await dio.post('/bookings', data: formData);
```

---

## Date Handling

### Backend Format
- **Date**: `YYYY-MM-DD` (e.g., `2024-02-01`)
- **DateTime**: ISO 8601 (e.g., `2024-01-15T10:00:00.000000Z`)

### Flutter Implementation
```dart
// Parse from API
final checkInDate = DateTime.parse(json['check_in_date']);

// Format for API
final formattedDate = DateFormat('yyyy-MM-dd').format(selectedDate);

// Display to user
final displayDate = DateFormat('dd MMM yyyy', 'id_ID').format(checkInDate);
// Output: "01 Feb 2024"
```

---

## Currency Handling

### Backend Format
- **Type**: Decimal (2 decimal places)
- **Example**: `1500000.00`

### Flutter Implementation
```dart
// Parse from API
final price = double.parse(json['price']);

// Format for display
final formattedPrice = NumberFormat.currency(
  locale: 'id_ID',
  symbol: 'Rp ',
  decimalDigits: 0,
).format(price);
// Output: "Rp 1.500.000"
```

---

## Role-Based UI

### Tenant UI
- Bottom Navigation: Home, Bookings, Payments, Profile
- Actions: Browse, Book, Upload Payment, Cancel Booking

### Owner UI
- Bottom Navigation: Dashboard, Properties, Bookings, Profile
- Actions: View Stats, Verify Payment, Reject Payment, View Bookings

### Implementation
```dart
// In app.dart or main navigation
Widget _buildBottomNavigation(String role) {
  if (role == 'tenant') {
    return TenantBottomNavigation();
  } else if (role == 'owner') {
    return OwnerBottomNavigation();
  }
  return Container(); // Fallback
}
```

---

## Testing Integration

### API Testing
1. **Postman Collection**: Import dari backend docs
2. **Mock Server**: Gunakan `mockito` untuk unit test
3. **Integration Test**: Test full flow (login → browse → book → payment)

### Test Accounts
- **Tenant**: `tenant@livora.com` / `password`
- **Owner**: `mitra@livora.com` / `password`
- **Admin**: `admin@livora.com` / `password` (web only)

---

## Performance Optimization

1. **Pagination**: Load 15 items per page
2. **Image Caching**: `CachedNetworkImage` dengan `maxCacheSize: 100`
3. **Debouncing**: Search input debounce 500ms
4. **Lazy Loading**: Load images only when visible (ListView.builder)
5. **API Timeout**: 30 seconds untuk request, 30 seconds untuk response

---

## Security Considerations

1. **Token Storage**: `FlutterSecureStorage` untuk token (encrypted)
2. **HTTPS Only**: Semua API call via HTTPS
3. **Input Validation**: Client-side validation sebelum API call
4. **File Upload**: Validate file type dan size sebelum upload
5. **Sensitive Data**: Jangan log token atau password di console
