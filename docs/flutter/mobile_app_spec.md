# Mobile App Specification - Livora

## App Overview

**App Name**: Livora Mobile  
**Platform**: Android (Primary), iOS (Future)  
**Type**: Boarding House Booking Application  
**Backend**: Laravel REST API  
**Authentication**: Laravel Sanctum (Bearer Token)  
**Base URL Production**: `https://livora-web-app-production.up.railway.app/api/v1`  
**Base URL Local**: `http://localhost:8000/api/v1`

## Platform

- **Primary Target**: Android 7.0+ (API Level 24+)
- **Future Target**: iOS 12.0+
- **Framework**: Flutter 3.x
- **Language**: Dart 3.x

## User Roles

### 1. Guest (Unauthenticated)
- Browse boarding houses
- View property details
- View room details
- Search and filter properties

### 2. Tenant (Authenticated - Role: tenant)
- All guest features
- Create bookings
- View booking history
- Upload payment proof
- Pay via Midtrans
- View payment history
- Create support tickets
- Manage profile

### 3. Owner/Mitra (Authenticated - Role: owner)
- View dashboard statistics
- View owned properties
- View bookings for owned properties
- Verify/reject tenant payments
- Manage support tickets

### 4. Admin (Authenticated - Role: admin)
- Full system access (Web only for MVP)

## Core Features

### Authentication
- **Login**: Email + Password
- **Register**: Name, Email, Password, Phone (optional), Role selection
- **Logout**: Revoke token
- **Session Check**: Get current user data
- **Auto-login**: Store token locally
- **Token Refresh**: Handle 401 responses

### Property Browsing (Guest + Authenticated)
- **List Properties**: Paginated list with images, name, city, price range
- **Search**: By name, city, address
- **Filter**: By city, price range (min/max)
- **Sort**: By created date, name, price
- **Property Detail**: Full info with rooms, facilities, owner info, location
- **Room Detail**: Price, capacity, size, images, facilities, availability

### Booking Management (Tenant)
- **Create Booking**: Select room, check-in date, duration (months), upload KTP image
- **View Bookings**: List all bookings with status
- **Booking Detail**: Full booking info with payment status
- **Cancel Booking**: Cancel pending/confirmed bookings
- **Filter Bookings**: By status (pending, confirmed, active, completed, cancelled)

### Payment Management (Tenant)
- **Upload Payment Proof**: Manual transfer proof with amount and notes
- **Midtrans Payment**: Online payment via credit card, e-wallet, bank transfer
- **View Payment History**: List all payments with status
- **Payment Status**: pending, verified, rejected, settlement, expired, cancelled

### Owner Features (Mitra)
- **Dashboard**: Revenue stats, booking stats, property performance
- **View Properties**: List owned properties with room count
- **View Bookings**: List bookings for owned properties
- **Verify Payment**: Approve tenant payment proof
- **Reject Payment**: Reject payment with notes

### Profile Management (Authenticated)
- **View Profile**: Name, email, phone, address, avatar, role
- **Update Profile**: Edit personal information
- **Change Password**: Update password
- **Upload Avatar**: Profile picture

## Non-Functional Requirements

### Performance
- **API Response Time**: < 2 seconds
- **Image Loading**: Progressive loading with placeholder
- **Pagination**: 15 items per page
- **Offline Support**: Cache property list (optional for MVP)

### Security
- **Token Storage**: Secure local storage (flutter_secure_storage)
- **HTTPS Only**: All API calls via HTTPS
- **Input Validation**: Client-side validation before API call
- **Image Upload**: Max 5MB, JPEG/PNG only

### UX/UI
- **Design Language**: Material Design 3
- **Theme**: Light mode (Dark mode optional)
- **Navigation**: Bottom navigation for main sections
- **Loading States**: Shimmer effect for loading
- **Error Handling**: User-friendly error messages
- **Empty States**: Informative empty state screens

### Localization
- **Primary Language**: Indonesian (Bahasa Indonesia)
- **Secondary Language**: English (Future)

## Design Reference

### Color Scheme
- **Primary**: Blue (#2563EB) - Trust, reliability
- **Secondary**: Green (#10B981) - Success, available
- **Accent**: Orange (#F59E0B) - Pending, warning
- **Error**: Red (#EF4444) - Cancelled, error
- **Background**: White (#FFFFFF), Gray (#F9FAFB)
- **Text**: Dark Gray (#1F2937), Light Gray (#6B7280)

### Typography
- **Headings**: Inter Bold, 20-24px
- **Body**: Inter Regular, 14-16px
- **Caption**: Inter Regular, 12px

### Key Screens
1. **Splash Screen**: App logo with loading
2. **Onboarding**: 3 slides explaining app features
3. **Login/Register**: Email/password form
4. **Home**: Property list with search and filter
5. **Property Detail**: Images, info, rooms, location map
6. **Booking Form**: Date picker, duration, KTP upload
7. **Booking List**: Status-based tabs
8. **Payment**: Upload proof or Midtrans integration
9. **Profile**: User info with edit option
10. **Dashboard (Owner)**: Stats and charts

### Navigation Structure
```
Bottom Navigation (Tenant):
├── Home (Browse properties)
├── Bookings (My bookings)
├── Payments (Payment history)
└── Profile (User profile)

Bottom Navigation (Owner):
├── Dashboard (Stats)
├── Properties (My properties)
├── Bookings (Property bookings)
└── Profile (User profile)
```

## Technical Constraints

- **Minimum Android Version**: Android 7.0 (API 24)
- **Maximum APK Size**: < 50MB
- **Internet Required**: Yes (no offline mode for MVP)
- **Permissions Required**: 
  - Internet
  - Camera (for KTP upload)
  - Storage (for image upload)
  - Location (optional, for nearby properties)

## Success Metrics

- **User Registration**: Successful account creation
- **Property Browsing**: View property details
- **Booking Creation**: Complete booking flow
- **Payment Upload**: Upload payment proof or pay via Midtrans
- **Payment Verification**: Owner can verify payments
- **App Stability**: < 1% crash rate
