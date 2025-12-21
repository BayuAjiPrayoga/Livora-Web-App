# Flutter Dependencies - Livora Mobile

## Core Dependencies

### State Management
```yaml
provider: ^6.1.1
```
**Purpose**: State management untuk ViewModel  
**Usage**: `ChangeNotifier`, `Consumer`, `Provider.of`

---

### Networking
```yaml
dio: ^5.4.0
```
**Purpose**: HTTP client untuk API calls  
**Features**: Interceptors, file upload, timeout, error handling

```yaml
pretty_dio_logger: ^1.3.1
```
**Purpose**: Logging HTTP requests/responses (development only)  
**Usage**: Debug API calls

---

### Dependency Injection
```yaml
get_it: ^7.6.4
injectable: ^2.3.2
```
**Purpose**: Service locator pattern untuk dependency injection  
**Usage**: Register dan resolve dependencies

```yaml
# Dev dependency
injectable_generator: ^2.4.1
build_runner: ^2.4.7
```
**Purpose**: Code generation untuk injectable  
**Usage**: `flutter pub run build_runner build`

---

### Routing
```yaml
go_router: ^13.0.0
```
**Purpose**: Declarative routing dengan deep linking support  
**Features**: Type-safe navigation, auth guards, URL-based routing

---

### Local Storage
```yaml
shared_preferences: ^2.2.2
```
**Purpose**: Simple key-value storage untuk cache  
**Usage**: Cache property list, user preferences

```yaml
flutter_secure_storage: ^9.0.0
```
**Purpose**: Secure storage untuk sensitive data  
**Usage**: Store authentication token

---

### JSON Serialization
```yaml
json_annotation: ^4.8.1
```
**Purpose**: Annotation untuk JSON serialization  
**Usage**: `@JsonSerializable()`, `fromJson()`, `toJson()`

```yaml
# Dev dependency
json_serializable: ^6.7.1
```
**Purpose**: Code generation untuk JSON serialization  
**Usage**: `flutter pub run build_runner build`

---

### Functional Programming
```yaml
dartz: ^0.10.1
```
**Purpose**: Functional programming utilities  
**Usage**: `Either<Failure, Success>` untuk error handling

---

## UI Dependencies

### Image Handling
```yaml
cached_network_image: ^3.3.1
```
**Purpose**: Load dan cache network images  
**Features**: Placeholder, error widget, cache management

```yaml
image_picker: ^1.0.7
```
**Purpose**: Pick images dari gallery atau camera  
**Usage**: KTP upload, avatar upload, payment proof

```yaml
flutter_image_compress: ^2.1.0
```
**Purpose**: Compress images sebelum upload  
**Usage**: Reduce file size (max 1MB)

---

### Date & Time
```yaml
intl: ^0.19.0
```
**Purpose**: Internationalization dan formatting  
**Usage**: Date formatting, currency formatting (Rupiah)

---

### Loading & Animations
```yaml
shimmer: ^3.0.0
```
**Purpose**: Shimmer loading effect  
**Usage**: Loading skeleton untuk property list

```yaml
lottie: ^3.0.0
```
**Purpose**: Lottie animations  
**Usage**: Empty state, success animation

---

### UI Components
```yaml
flutter_svg: ^2.0.9
```
**Purpose**: Render SVG images  
**Usage**: Icons, illustrations

```yaml
smooth_page_indicator: ^1.1.0
```
**Purpose**: Page indicator untuk carousel  
**Usage**: Property image carousel, onboarding

```yaml
pull_to_refresh: ^2.0.0
```
**Purpose**: Pull-to-refresh functionality  
**Usage**: Refresh property list, booking list

```yaml
infinite_scroll_pagination: ^4.0.0
```
**Purpose**: Infinite scroll dengan pagination  
**Usage**: Property list, booking list

---

### Forms & Validation
```yaml
flutter_form_builder: ^9.1.1
```
**Purpose**: Form builder dengan validation  
**Usage**: Login form, register form, booking form

```yaml
form_builder_validators: ^9.1.0
```
**Purpose**: Pre-built validators  
**Usage**: Email, phone, required field validation

---

### Dialogs & Modals
```yaml
awesome_dialog: ^3.1.0
```
**Purpose**: Beautiful dialogs  
**Usage**: Success, error, confirmation dialogs

```yaml
modal_bottom_sheet: ^3.0.0
```
**Purpose**: Custom bottom sheets  
**Usage**: Filter sheet, sort sheet

---

## Utility Dependencies

### App Info
```yaml
package_info_plus: ^5.0.1
```
**Purpose**: Get app version, build number  
**Usage**: Display version di profile page

```yaml
device_info_plus: ^9.1.1
```
**Purpose**: Get device information  
**Usage**: Analytics, debugging

---

### Permissions
```yaml
permission_handler: ^11.2.0
```
**Purpose**: Handle runtime permissions  
**Usage**: Camera, storage, location permissions

---

### URL Launcher
```yaml
url_launcher: ^6.2.3
```
**Purpose**: Launch URLs, phone calls, emails  
**Usage**: Contact owner, open maps, open website

---

### Environment
```yaml
flutter_dotenv: ^5.1.0
```
**Purpose**: Load environment variables  
**Usage**: API URL, app configuration

---

## Payment Integration (Future)

### Midtrans
```yaml
midtrans_sdk: ^0.2.0
```
**Purpose**: Midtrans payment gateway integration  
**Usage**: Online payment via credit card, e-wallet, bank transfer

---

## Analytics & Monitoring (Future)

### Firebase
```yaml
firebase_core: ^2.24.2
firebase_analytics: ^10.8.0
firebase_crashlytics: ^3.4.9
firebase_messaging: ^14.7.10
```
**Purpose**: Analytics, crash reporting, push notifications  
**Usage**: Track user behavior, monitor crashes, send notifications

---

## Testing Dependencies

```yaml
dev_dependencies:
  flutter_test:
    sdk: flutter
  
  # Unit Testing
  mockito: ^5.4.4
  build_runner: ^2.4.7
  
  # Widget Testing
  flutter_driver:
    sdk: flutter
  
  # Integration Testing
  integration_test:
    sdk: flutter
  
  # Code Analysis
  flutter_lints: ^3.0.1
```

---

## Complete pubspec.yaml

```yaml
name: livora
description: Livora Mobile - Boarding House Booking App
publish_to: 'none'
version: 1.0.0+1

environment:
  sdk: '>=3.2.0 <4.0.0'

dependencies:
  flutter:
    sdk: flutter
  
  # State Management
  provider: ^6.1.1
  
  # Networking
  dio: ^5.4.0
  pretty_dio_logger: ^1.3.1
  
  # Dependency Injection
  get_it: ^7.6.4
  injectable: ^2.3.2
  
  # Routing
  go_router: ^13.0.0
  
  # Local Storage
  shared_preferences: ^2.2.2
  flutter_secure_storage: ^9.0.0
  
  # JSON Serialization
  json_annotation: ^4.8.1
  
  # Functional Programming
  dartz: ^0.10.1
  
  # Image Handling
  cached_network_image: ^3.3.1
  image_picker: ^1.0.7
  flutter_image_compress: ^2.1.0
  
  # Date & Time
  intl: ^0.19.0
  
  # Loading & Animations
  shimmer: ^3.0.0
  lottie: ^3.0.0
  
  # UI Components
  flutter_svg: ^2.0.9
  smooth_page_indicator: ^1.1.0
  pull_to_refresh: ^2.0.0
  infinite_scroll_pagination: ^4.0.0
  
  # Forms & Validation
  flutter_form_builder: ^9.1.1
  form_builder_validators: ^9.1.0
  
  # Dialogs & Modals
  awesome_dialog: ^3.1.0
  modal_bottom_sheet: ^3.0.0
  
  # Utility
  package_info_plus: ^5.0.1
  device_info_plus: ^9.1.1
  permission_handler: ^11.2.0
  url_launcher: ^6.2.3
  flutter_dotenv: ^5.1.0
  
  # Icons
  cupertino_icons: ^1.0.6

dev_dependencies:
  flutter_test:
    sdk: flutter
  
  # Code Generation
  build_runner: ^2.4.7
  json_serializable: ^6.7.1
  injectable_generator: ^2.4.1
  
  # Testing
  mockito: ^5.4.4
  
  # Code Analysis
  flutter_lints: ^3.0.1

flutter:
  uses-material-design: true
  
  assets:
    - assets/images/
    - assets/icons/
    - assets/animations/
    - .env.development
    - .env.staging
    - .env.production
  
  fonts:
    - family: Inter
      fonts:
        - asset: assets/fonts/Inter-Regular.ttf
        - asset: assets/fonts/Inter-Medium.ttf
          weight: 500
        - asset: assets/fonts/Inter-SemiBold.ttf
          weight: 600
        - asset: assets/fonts/Inter-Bold.ttf
          weight: 700
```

---

## Installation Commands

### Install Dependencies
```bash
flutter pub get
```

### Code Generation
```bash
# Generate JSON serialization & Injectable
flutter pub run build_runner build --delete-conflicting-outputs

# Watch mode (auto-generate on file changes)
flutter pub run build_runner watch --delete-conflicting-outputs
```

### Clean Build
```bash
flutter clean
flutter pub get
flutter pub run build_runner build --delete-conflicting-outputs
```

---

## Dependency Management

### Update Dependencies
```bash
# Check outdated packages
flutter pub outdated

# Update to latest compatible versions
flutter pub upgrade

# Update to latest versions (may break)
flutter pub upgrade --major-versions
```

### Lock Dependencies
```bash
# Generate pubspec.lock
flutter pub get

# Commit pubspec.lock to version control
git add pubspec.lock
git commit -m "Lock dependencies"
```

---

## Platform-Specific Configuration

### Android (android/app/build.gradle)
```gradle
android {
    defaultConfig {
        minSdkVersion 24  // Required for flutter_secure_storage
        targetSdkVersion 34
        multiDexEnabled true  // Required if method count > 64K
    }
}

dependencies {
    implementation 'androidx.multidex:multidex:2.0.1'
}
```

### iOS (ios/Podfile)
```ruby
platform :ios, '12.0'

# Uncomment if using Firebase
# use_frameworks!

post_install do |installer|
  installer.pods_project.targets.each do |target|
    flutter_additional_ios_build_settings(target)
    target.build_configurations.each do |config|
      config.build_settings['IPHONEOS_DEPLOYMENT_TARGET'] = '12.0'
    end
  end
end
```

---

## Troubleshooting

### Common Issues

**1. Build Runner Conflicts**
```bash
flutter pub run build_runner build --delete-conflicting-outputs
```

**2. Dependency Conflicts**
```bash
flutter pub upgrade --major-versions
```

**3. iOS Pod Install Fails**
```bash
cd ios
pod deintegrate
pod install
cd ..
flutter clean
flutter pub get
```

**4. Android Build Fails (MultiDex)**
```gradle
# Add to android/app/build.gradle
defaultConfig {
    multiDexEnabled true
}

dependencies {
    implementation 'androidx.multidex:multidex:2.0.1'
}
```

**5. Image Picker Permission Denied**
```xml
<!-- Add to android/app/src/main/AndroidManifest.xml -->
<uses-permission android:name="android.permission.CAMERA" />
<uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" />
<uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE" />
```

```xml
<!-- Add to ios/Runner/Info.plist -->
<key>NSCameraUsageDescription</key>
<string>We need camera access to upload KTP and payment proof</string>
<key>NSPhotoLibraryUsageDescription</key>
<string>We need photo library access to upload images</string>
```
