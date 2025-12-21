# Navigation Flow - Livora Mobile

## App Navigation Structure

### Guest Flow (Unauthenticated)
```
Splash Screen
    ↓
Onboarding (first time only)
    ↓
Login/Register
    ↓
Home (Browse Properties)
    ↓
Property Detail
    ↓
Room Detail
    ↓
[Redirect to Login if try to book]
```

### Tenant Flow (Authenticated)
```
Splash Screen
    ↓
[Auto-login if token exists]
    ↓
Home (Bottom Navigation)
├── Tab 1: Home (Browse Properties)
│   ├── Property Detail
│   │   ├── Room Detail
│   │   │   └── Create Booking
│   │   │       └── Upload Payment
│   │   └── View on Map
│   └── Search & Filter
│
├── Tab 2: Bookings
│   ├── Booking List (Status Tabs)
│   │   ├── All
│   │   ├── Pending
│   │   ├── Confirmed
│   │   ├── Active
│   │   └── Completed
│   └── Booking Detail
│       ├── Upload Payment
│       ├── Cancel Booking
│       └── Contact Owner
│
├── Tab 3: Payments
│   ├── Payment History
│   └── Payment Detail
│
└── Tab 4: Profile
    ├── Edit Profile
    ├── Change Password
    ├── Settings
    └── Logout
```

### Owner Flow (Authenticated)
```
Splash Screen
    ↓
[Auto-login if token exists]
    ↓
Owner Dashboard (Bottom Navigation)
├── Tab 1: Dashboard
│   ├── Statistics
│   ├── Revenue Chart
│   └── Recent Bookings
│
├── Tab 2: Properties
│   ├── Property List
│   └── Property Detail
│       └── Room List
│
├── Tab 3: Bookings
│   ├── Booking List (Status Tabs)
│   └── Booking Detail
│       ├── Verify Payment
│       └── Reject Payment
│
└── Tab 4: Profile
    ├── Edit Profile
    ├── Settings
    └── Logout
```

---

## Detailed Screen Flows

### 1. Authentication Flow

#### First Time User
```
App Launch
    ↓
Splash Screen (2 seconds)
    ↓
Check if first time
    ↓
Onboarding (3 slides)
    ↓
Login Screen
    ├── Login Form
    │   ├── Email input
    │   ├── Password input
    │   └── Login button → Home
    │
    └── Register button → Register Screen
        ├── Name input
        ├── Email input
        ├── Password input
        ├── Confirm Password input
        ├── Phone input (optional)
        ├── Role selection (Tenant/Owner)
        └── Register button → Home
```

#### Returning User
```
App Launch
    ↓
Splash Screen
    ↓
Check token in secure storage
    ↓
If token exists:
    ↓
Call /me API
    ↓
If valid → Home (based on role)
If invalid → Login Screen
```

---

### 2. Property Browsing Flow

```
Home Screen
    ↓
Property List (Paginated)
    ├── Search bar (debounced)
    ├── Filter button → Filter Bottom Sheet
    │   ├── City filter
    │   ├── Price range filter
    │   └── Apply button
    │
    ├── Sort button → Sort Bottom Sheet
    │   ├── Latest
    │   ├── Price: Low to High
    │   ├── Price: High to Low
    │   └── Name: A to Z
    │
    └── Property Card (tap) → Property Detail
        ├── Image Carousel
        ├── Property Info
        ├── Location Map
        ├── Room List
        │   └── Room Card (tap) → Room Detail
        │       ├── Room Images
        │       ├── Room Info
        │       ├── Facilities
        │       └── Book Now button → Create Booking
        │
        └── Contact Owner button → Phone/WhatsApp
```

---

### 3. Booking Flow (Tenant)

```
Room Detail
    ↓
Book Now button
    ↓
Create Booking Screen
    ├── Check-in Date Picker
    ├── Duration Selector (1-12 months)
    ├── Total Price Display (auto-calculate)
    ├── KTP Number Input
    ├── KTP Image Upload
    │   ├── Camera button
    │   └── Gallery button
    ├── Notes Input (optional)
    └── Create Booking button
        ↓
    Loading
        ↓
    Success Dialog
        ↓
    Booking Detail Screen
        ├── Booking Info
        ├── Payment Status: Pending
        └── Upload Payment button → Upload Payment Screen
            ├── Amount Input
            ├── Payment Proof Image Upload
            ├── Notes Input
            └── Upload button
                ↓
            Success Dialog
                ↓
            Booking Detail (Payment: Pending Verification)
```

---

### 4. Booking Management Flow (Tenant)

```
Bookings Tab
    ↓
Booking List (Tabs)
    ├── All
    ├── Pending (Yellow badge)
    ├── Confirmed (Blue badge)
    ├── Active (Green badge)
    └── Completed (Gray badge)
    
Booking Card (tap) → Booking Detail
    ├── Booking Info
    ├── Room Info
    ├── Payment Status
    ├── Actions (based on status):
    │   ├── Pending: Upload Payment, Cancel
    │   ├── Confirmed: Cancel, Contact Owner
    │   ├── Active: Contact Owner
    │   └── Completed: View Only
    │
    └── Payment History
        └── Payment Card (tap) → Payment Detail
```

---

### 5. Owner Dashboard Flow

```
Owner Dashboard
    ├── Statistics Cards
    │   ├── Total Properties
    │   ├── Total Rooms
    │   ├── Active Bookings
    │   └── Monthly Revenue
    │
    ├── Revenue Chart (Last 6 months)
    │
    └── Recent Bookings List
        └── Booking Card (tap) → Booking Detail
```

---

### 6. Owner Booking Management Flow

```
Owner Bookings Tab
    ↓
Booking List (Filter by Property)
    ├── All Properties
    ├── Property A
    └── Property B
    
Booking Card (tap) → Booking Detail
    ├── Tenant Info
    ├── Room Info
    ├── Payment Info
    │   ├── Amount
    │   ├── Proof Image (tap to view full)
    │   └── Status
    │
    └── Actions (if payment pending):
        ├── Verify Payment button
        │   ↓
        │   Confirmation Dialog
        │   ↓
        │   Success → Booking Status: Active
        │
        └── Reject Payment button
            ↓
            Reject Dialog (with notes input)
            ↓
            Success → Payment Status: Rejected
```

---

### 7. Profile Flow

```
Profile Tab
    ├── User Info Display
    │   ├── Avatar
    │   ├── Name
    │   ├── Email
    │   ├── Phone
    │   └── Role Badge
    │
    ├── Edit Profile button → Edit Profile Screen
    │   ├── Avatar Upload
    │   ├── Name Input
    │   ├── Phone Input
    │   ├── Address Input
    │   └── Save button
    │
    ├── Change Password button → Change Password Screen
    │   ├── Current Password Input
    │   ├── New Password Input
    │   ├── Confirm Password Input
    │   └── Change button
    │
    ├── Settings button → Settings Screen
    │   ├── Notifications Toggle
    │   ├── Language Selection
    │   └── Theme Selection (future)
    │
    ├── About button → About Screen
    │   ├── App Version
    │   ├── Terms & Conditions
    │   └── Privacy Policy
    │
    └── Logout button
        ↓
        Confirmation Dialog
        ↓
        Clear token → Login Screen
```

---

## Navigation Patterns

### Bottom Navigation (Tenant)
```dart
BottomNavigationBar(
  items: [
    BottomNavigationBarItem(icon: Icons.home, label: 'Home'),
    BottomNavigationBarItem(icon: Icons.book, label: 'Bookings'),
    BottomNavigationBarItem(icon: Icons.payment, label: 'Payments'),
    BottomNavigationBarItem(icon: Icons.person, label: 'Profile'),
  ],
)
```

### Bottom Navigation (Owner)
```dart
BottomNavigationBar(
  items: [
    BottomNavigationBarItem(icon: Icons.dashboard, label: 'Dashboard'),
    BottomNavigationBarItem(icon: Icons.home_work, label: 'Properties'),
    BottomNavigationBarItem(icon: Icons.book, label: 'Bookings'),
    BottomNavigationBarItem(icon: Icons.person, label: 'Profile'),
  ],
)
```

### Navigation Methods

#### Push (Forward Navigation)
```dart
context.push('/property/kost-melati');
```

#### Pop (Back Navigation)
```dart
context.pop();
```

#### Replace (Replace Current Route)
```dart
context.replace('/home');
```

#### Go (Navigate to Root)
```dart
context.go('/login');
```

---

## Deep Linking

### Supported Deep Links
```
livora://property/{slug}           → Property Detail
livora://room/{id}                 → Room Detail
livora://booking/{id}              → Booking Detail
livora://payment/{id}              → Payment Detail
```

### Implementation
```dart
// In app_router.dart
GoRoute(
  path: '/property/:slug',
  builder: (context, state) {
    final slug = state.pathParameters['slug']!;
    return PropertyDetailPage(slug: slug);
  },
),
```

---

## Auth Guards

### Protected Routes
```dart
redirect: (context, state) {
  final authViewModel = getIt<AuthViewModel>();
  final isLoggedIn = authViewModel.isLoggedIn;
  final isGoingToLogin = state.matchedLocation == '/login';
  
  // Redirect to login if not authenticated
  if (!isLoggedIn && !isGoingToLogin) {
    return '/login';
  }
  
  // Redirect to home if already logged in
  if (isLoggedIn && isGoingToLogin) {
    return '/';
  }
  
  return null; // No redirect
}
```

### Role-Based Access
```dart
redirect: (context, state) {
  final authViewModel = getIt<AuthViewModel>();
  final userRole = authViewModel.user?.role;
  
  // Owner-only routes
  if (state.matchedLocation.startsWith('/owner')) {
    if (userRole != 'owner') {
      return '/'; // Redirect to home
    }
  }
  
  return null;
}
```

---

## Transition Animations

### Default Transition
```dart
// Slide from right (default)
pageBuilder: (context, state) {
  return MaterialPage(
    key: state.pageKey,
    child: PropertyDetailPage(),
  );
}
```

### Custom Transition
```dart
// Fade transition
pageBuilder: (context, state) {
  return CustomTransitionPage(
    key: state.pageKey,
    child: PropertyDetailPage(),
    transitionsBuilder: (context, animation, secondaryAnimation, child) {
      return FadeTransition(
        opacity: animation,
        child: child,
      );
    },
  );
}
```

---

## Back Button Handling

### Android Back Button
```dart
// In main pages (Home, Dashboard)
WillPopScope(
  onWillPop: () async {
    // Show exit confirmation dialog
    final shouldExit = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Exit App?'),
        content: Text('Are you sure you want to exit?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(context).pop(false),
            child: Text('Cancel'),
          ),
          TextButton(
            onPressed: () => Navigator.of(context).pop(true),
            child: Text('Exit'),
          ),
        ],
      ),
    );
    return shouldExit ?? false;
  },
  child: Scaffold(...),
)
```

---

## Error Navigation

### 404 Not Found
```dart
// In app_router.dart
errorBuilder: (context, state) {
  return NotFoundPage(
    message: 'Page not found: ${state.matchedLocation}',
  );
}
```

### Network Error
```dart
// Show retry dialog
showDialog(
  context: context,
  builder: (context) => AlertDialog(
    title: Text('Network Error'),
    content: Text('Failed to load data. Please try again.'),
    actions: [
      TextButton(
        onPressed: () {
          Navigator.of(context).pop();
          // Retry action
        },
        child: Text('Retry'),
      ),
    ],
  ),
);
```
