# Project Structure - Livora Flutter

## Folder Structure

```
lib/
├── main.dart                           # App entry point
├── app.dart                            # Root app widget with routing
│
├── core/                               # Core utilities & shared code
│   ├── constants/
│   │   ├── api_constants.dart          # API URLs, endpoints
│   │   ├── app_constants.dart          # App-wide constants
│   │   └── asset_constants.dart        # Asset paths
│   │
│   ├── di/
│   │   └── injection.dart              # Dependency injection setup (GetIt)
│   │
│   ├── errors/
│   │   ├── exceptions.dart             # Custom exceptions
│   │   └── failures.dart               # Failure classes
│   │
│   ├── network/
│   │   ├── dio_client.dart             # Dio HTTP client setup
│   │   ├── auth_interceptor.dart       # Auth token interceptor
│   │   └── api_response.dart           # Generic API response wrapper
│   │
│   ├── router/
│   │   └── app_router.dart             # GoRouter configuration
│   │
│   ├── theme/
│   │   ├── app_theme.dart              # App theme configuration
│   │   ├── app_colors.dart             # Color palette
│   │   └── app_text_styles.dart        # Text styles
│   │
│   └── utils/
│       ├── date_formatter.dart         # Date formatting utilities
│       ├── currency_formatter.dart     # Currency formatting (Rupiah)
│       ├── validators.dart             # Input validators
│       └── image_picker_helper.dart    # Image picker wrapper
│
├── data/                               # Data layer
│   ├── datasources/
│   │   ├── local/
│   │   │   ├── auth_local_datasource.dart          # Token storage
│   │   │   └── auth_local_datasource_impl.dart
│   │   │
│   │   └── remote/
│   │       ├── auth_remote_datasource.dart         # Auth API calls
│   │       ├── auth_remote_datasource_impl.dart
│   │       ├── property_remote_datasource.dart     # Property API calls
│   │       ├── property_remote_datasource_impl.dart
│   │       ├── booking_remote_datasource.dart      # Booking API calls
│   │       ├── booking_remote_datasource_impl.dart
│   │       ├── payment_remote_datasource.dart      # Payment API calls
│   │       └── payment_remote_datasource_impl.dart
│   │
│   ├── models/
│   │   ├── user_model.dart             # User DTO with JSON serialization
│   │   ├── property_model.dart         # Property DTO
│   │   ├── room_model.dart             # Room DTO
│   │   ├── booking_model.dart          # Booking DTO
│   │   ├── payment_model.dart          # Payment DTO
│   │   └── facility_model.dart         # Facility DTO
│   │
│   └── repositories/
│       ├── auth_repository_impl.dart           # Auth repository implementation
│       ├── property_repository_impl.dart       # Property repository implementation
│       ├── booking_repository_impl.dart        # Booking repository implementation
│       └── payment_repository_impl.dart        # Payment repository implementation
│
├── domain/                             # Domain layer (Business logic)
│   ├── entities/
│   │   ├── user.dart                   # User entity
│   │   ├── property.dart               # Property entity
│   │   ├── room.dart                   # Room entity
│   │   ├── booking.dart                # Booking entity
│   │   ├── payment.dart                # Payment entity
│   │   └── facility.dart               # Facility entity
│   │
│   ├── repositories/
│   │   ├── auth_repository.dart        # Auth repository interface
│   │   ├── property_repository.dart    # Property repository interface
│   │   ├── booking_repository.dart     # Booking repository interface
│   │   └── payment_repository.dart     # Payment repository interface
│   │
│   └── usecases/
│       ├── auth/
│       │   ├── login_usecase.dart              # Login use case
│       │   ├── register_usecase.dart           # Register use case
│       │   ├── logout_usecase.dart             # Logout use case
│       │   └── get_current_user_usecase.dart   # Get current user
│       │
│       ├── property/
│       │   ├── get_properties_usecase.dart     # Get property list
│       │   └── get_property_detail_usecase.dart # Get property detail
│       │
│       ├── booking/
│       │   ├── get_bookings_usecase.dart       # Get user bookings
│       │   ├── get_booking_detail_usecase.dart # Get booking detail
│       │   ├── create_booking_usecase.dart     # Create booking
│       │   └── cancel_booking_usecase.dart     # Cancel booking
│       │
│       └── payment/
│           ├── get_payments_usecase.dart       # Get payment history
│           └── upload_payment_proof_usecase.dart # Upload payment proof
│
└── presentation/                       # Presentation layer (UI)
    ├── viewmodels/
    │   ├── auth/
    │   │   ├── login_viewmodel.dart            # Login state management
    │   │   └── register_viewmodel.dart         # Register state management
    │   │
    │   ├── property/
    │   │   ├── home_viewmodel.dart             # Home/browse state
    │   │   └── property_detail_viewmodel.dart  # Property detail state
    │   │
    │   ├── booking/
    │   │   ├── booking_list_viewmodel.dart     # Booking list state
    │   │   ├── booking_detail_viewmodel.dart   # Booking detail state
    │   │   └── create_booking_viewmodel.dart   # Create booking state
    │   │
    │   ├── payment/
    │   │   └── payment_viewmodel.dart          # Payment state
    │   │
    │   └── profile/
    │       └── profile_viewmodel.dart          # Profile state
    │
    ├── pages/
    │   ├── splash/
    │   │   └── splash_page.dart                # Splash screen
    │   │
    │   ├── onboarding/
    │   │   └── onboarding_page.dart            # Onboarding slides
    │   │
    │   ├── auth/
    │   │   ├── login_page.dart                 # Login screen
    │   │   └── register_page.dart              # Register screen
    │   │
    │   ├── home/
    │   │   └── home_page.dart                  # Property browse screen
    │   │
    │   ├── property/
    │   │   ├── property_detail_page.dart       # Property detail screen
    │   │   └── room_detail_page.dart           # Room detail screen
    │   │
    │   ├── booking/
    │   │   ├── booking_list_page.dart          # Booking list screen
    │   │   ├── booking_detail_page.dart        # Booking detail screen
    │   │   └── create_booking_page.dart        # Create booking form
    │   │
    │   ├── payment/
    │   │   ├── payment_list_page.dart          # Payment history screen
    │   │   └── upload_payment_page.dart        # Upload payment proof
    │   │
    │   ├── profile/
    │   │   ├── profile_page.dart               # Profile screen
    │   │   └── edit_profile_page.dart          # Edit profile screen
    │   │
    │   └── owner/
    │       ├── owner_dashboard_page.dart       # Owner dashboard
    │       ├── owner_properties_page.dart      # Owner properties
    │       └── owner_bookings_page.dart        # Owner bookings
    │
    └── widgets/
        ├── common/
        │   ├── custom_button.dart              # Reusable button
        │   ├── custom_text_field.dart          # Reusable text field
        │   ├── loading_widget.dart             # Loading indicator
        │   ├── error_widget.dart               # Error display
        │   ├── empty_state_widget.dart         # Empty state
        │   └── image_picker_widget.dart        # Image picker
        │
        ├── property/
        │   ├── property_card.dart              # Property list item
        │   ├── property_filter_sheet.dart      # Filter bottom sheet
        │   └── room_card.dart                  # Room list item
        │
        ├── booking/
        │   ├── booking_card.dart               # Booking list item
        │   └── booking_status_badge.dart       # Status badge
        │
        └── payment/
            ├── payment_card.dart               # Payment list item
            └── payment_status_badge.dart       # Payment status badge
```

---

## Folder Explanation

### `main.dart`
- **Purpose**: App entry point
- **Responsibilities**:
  - Initialize dependencies (GetIt)
  - Setup error handling
  - Run the app

### `app.dart`
- **Purpose**: Root app widget
- **Responsibilities**:
  - Configure MaterialApp with GoRouter
  - Setup theme
  - Provide global ViewModels

---

### `core/`
Shared code used across the entire app.

#### `core/constants/`
- **api_constants.dart**: API base URLs, endpoints
- **app_constants.dart**: App name, version, pagination size
- **asset_constants.dart**: Image, icon, animation paths

#### `core/di/`
- **injection.dart**: GetIt dependency injection setup

#### `core/errors/`
- **exceptions.dart**: Custom exceptions (ServerException, NetworkException)
- **failures.dart**: Failure classes for error handling

#### `core/network/`
- **dio_client.dart**: Dio HTTP client configuration
- **auth_interceptor.dart**: Automatically add Bearer token to requests
- **api_response.dart**: Generic API response wrapper

#### `core/router/`
- **app_router.dart**: GoRouter configuration with routes and auth guards

#### `core/theme/`
- **app_theme.dart**: Material theme configuration
- **app_colors.dart**: Color palette constants
- **app_text_styles.dart**: Text style constants

#### `core/utils/`
- **date_formatter.dart**: Format dates (e.g., "15 Jan 2024")
- **currency_formatter.dart**: Format currency (e.g., "Rp 1.500.000")
- **validators.dart**: Email, password, phone validators
- **image_picker_helper.dart**: Helper for picking images

---

### `data/`
Data layer - handles data operations.

#### `data/datasources/`
**local/**: Local data storage (token, cache)
- **auth_local_datasource.dart**: Save/get/delete token using FlutterSecureStorage

**remote/**: API calls using Dio
- **auth_remote_datasource.dart**: Login, register, logout API calls
- **property_remote_datasource.dart**: Get properties, property detail
- **booking_remote_datasource.dart**: CRUD bookings
- **payment_remote_datasource.dart**: Upload payment, get payments

#### `data/models/`
Data Transfer Objects (DTOs) with JSON serialization.
- **user_model.dart**: `fromJson()`, `toJson()`, `toEntity()`
- **property_model.dart**: Parse API response to model
- **booking_model.dart**: Booking data model
- **payment_model.dart**: Payment data model

#### `data/repositories/`
Implementation of domain repository interfaces.
- **auth_repository_impl.dart**: Implements `AuthRepository`
- **property_repository_impl.dart**: Implements `PropertyRepository`
- Handles error mapping (Exception → Failure)

---

### `domain/`
Domain layer - pure business logic (no Flutter dependencies).

#### `domain/entities/`
Business entities (immutable classes).
- **user.dart**: User entity with id, name, email, role
- **property.dart**: Property entity
- **booking.dart**: Booking entity
- **payment.dart**: Payment entity

#### `domain/repositories/`
Repository interfaces (abstract classes).
- **auth_repository.dart**: `Future<Either<Failure, User>> login(...)`
- **property_repository.dart**: `Future<Either<Failure, List<Property>>> getProperties(...)`

#### `domain/usecases/`
Single-responsibility business operations.
- **login_usecase.dart**: Handle login logic
- **get_properties_usecase.dart**: Get property list with filters
- **create_booking_usecase.dart**: Create booking with validation

---

### `presentation/`
Presentation layer - UI and state management.

#### `presentation/viewmodels/`
State management using Provider (ChangeNotifier).
- **login_viewmodel.dart**: Login state, loading, error
- **home_viewmodel.dart**: Property list state, search, filter
- **booking_list_viewmodel.dart**: Booking list state

#### `presentation/pages/`
Full-screen widgets (pages/screens).
- **splash_page.dart**: Splash screen with logo
- **login_page.dart**: Login form
- **home_page.dart**: Property browse with search
- **property_detail_page.dart**: Property detail with rooms
- **booking_list_page.dart**: User bookings with tabs

#### `presentation/widgets/`
Reusable UI components.

**common/**: Generic widgets
- **custom_button.dart**: Styled button with loading state
- **custom_text_field.dart**: Styled text field with validation
- **loading_widget.dart**: Shimmer or circular progress indicator
- **error_widget.dart**: Error message with retry button

**property/**: Property-specific widgets
- **property_card.dart**: Property card for list
- **property_filter_sheet.dart**: Filter bottom sheet

**booking/**: Booking-specific widgets
- **booking_card.dart**: Booking card with status
- **booking_status_badge.dart**: Colored status badge

---

## File Naming Conventions

- **Files**: `snake_case.dart` (e.g., `login_page.dart`)
- **Classes**: `PascalCase` (e.g., `LoginPage`)
- **Variables**: `camelCase` (e.g., `isLoading`)
- **Constants**: `camelCase` or `SCREAMING_SNAKE_CASE` (e.g., `baseUrl` or `BASE_URL`)
- **Private**: Prefix with `_` (e.g., `_isLoading`)

---

## Import Organization

```dart
// 1. Dart imports
import 'dart:async';
import 'dart:io';

// 2. Flutter imports
import 'package:flutter/material.dart';

// 3. Package imports
import 'package:provider/provider.dart';
import 'package:dio/dio.dart';

// 4. Project imports
import 'package:livora/core/constants/api_constants.dart';
import 'package:livora/domain/entities/user.dart';
import 'package:livora/presentation/viewmodels/login_viewmodel.dart';
```

---

## Key Design Decisions

1. **Clean Architecture**: Separation of concerns, testability
2. **MVVM Pattern**: Clear separation between UI and business logic
3. **Provider**: Simple and official state management
4. **GetIt**: Dependency injection for loose coupling
5. **GoRouter**: Declarative routing with deep linking support
6. **Dio**: Powerful HTTP client with interceptors
7. **Either Pattern**: Functional error handling (dartz package)
8. **Immutable Entities**: Domain entities are immutable (freezed package)
9. **JSON Serialization**: Code generation for models (json_serializable)
10. **Feature-First**: Organized by feature (auth, property, booking)

---

## Development Workflow

1. **Define Entity** (domain/entities/)
2. **Define Repository Interface** (domain/repositories/)
3. **Create Use Case** (domain/usecases/)
4. **Create Model** (data/models/)
5. **Create Data Source** (data/datasources/)
6. **Implement Repository** (data/repositories/)
7. **Create ViewModel** (presentation/viewmodels/)
8. **Create UI** (presentation/pages/ & widgets/)
9. **Register Dependencies** (core/di/injection.dart)
10. **Add Route** (core/router/app_router.dart)

---

## Testing Structure

```
test/
├── unit/
│   ├── domain/
│   │   └── usecases/
│   │       └── login_usecase_test.dart
│   │
│   └── presentation/
│       └── viewmodels/
│           └── login_viewmodel_test.dart
│
├── widget/
│   └── pages/
│       └── login_page_test.dart
│
└── integration/
    └── auth_flow_test.dart
```
