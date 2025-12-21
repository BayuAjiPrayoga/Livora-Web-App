# Environment Configuration - Livora Flutter

## Environment Variables

### Development (.env.development)
```env
# API Configuration
BASE_URL=http://localhost:8000/api/v1
BASE_URL_STORAGE=http://localhost:8000/storage

# App Configuration
APP_NAME=Livora Dev
APP_ENV=development
DEBUG_MODE=true

# Network Configuration
CONNECTION_TIMEOUT=30000
RECEIVE_TIMEOUT=30000

# Pagination
DEFAULT_PAGE_SIZE=15

# Cache Configuration
CACHE_ENABLED=true
CACHE_MAX_AGE=3600

# Logging
ENABLE_API_LOGGING=true
ENABLE_ERROR_LOGGING=true
```

### Staging (.env.staging)
```env
# API Configuration
BASE_URL=https://livora-staging.railway.app/api/v1
BASE_URL_STORAGE=https://livora-staging.railway.app/storage

# App Configuration
APP_NAME=Livora Staging
APP_ENV=staging
DEBUG_MODE=true

# Network Configuration
CONNECTION_TIMEOUT=30000
RECEIVE_TIMEOUT=30000

# Pagination
DEFAULT_PAGE_SIZE=15

# Cache Configuration
CACHE_ENABLED=true
CACHE_MAX_AGE=3600

# Logging
ENABLE_API_LOGGING=true
ENABLE_ERROR_LOGGING=true
```

### Production (.env.production)
```env
# API Configuration
BASE_URL=https://livora-web-app-production.up.railway.app/api/v1
BASE_URL_STORAGE=https://livora-web-app-production.up.railway.app/storage

# App Configuration
APP_NAME=Livora
APP_ENV=production
DEBUG_MODE=false

# Network Configuration
CONNECTION_TIMEOUT=30000
RECEIVE_TIMEOUT=30000

# Pagination
DEFAULT_PAGE_SIZE=15

# Cache Configuration
CACHE_ENABLED=true
CACHE_MAX_AGE=7200

# Logging
ENABLE_API_LOGGING=false
ENABLE_ERROR_LOGGING=true
```

---

## Environment Setup in Flutter

### 1. Install flutter_dotenv
```yaml
# pubspec.yaml
dependencies:
  flutter_dotenv: ^5.1.0
```

### 2. Add .env files to assets
```yaml
# pubspec.yaml
flutter:
  assets:
    - .env.development
    - .env.staging
    - .env.production
```

### 3. Load environment
```dart
// main.dart
import 'package:flutter_dotenv/flutter_dotenv.dart';

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  // Load environment based on build mode
  const environment = String.fromEnvironment('ENV', defaultValue: 'development');
  await dotenv.load(fileName: '.env.$environment');
  
  runApp(MyApp());
}
```

### 4. Access environment variables
```dart
// core/constants/api_constants.dart
class ApiConstants {
  static final String baseUrl = dotenv.env['BASE_URL'] ?? '';
  static final String baseUrlStorage = dotenv.env['BASE_URL_STORAGE'] ?? '';
  static final int connectionTimeout = int.parse(dotenv.env['CONNECTION_TIMEOUT'] ?? '30000');
  static final int receiveTimeout = int.parse(dotenv.env['RECEIVE_TIMEOUT'] ?? '30000');
}
```

---

## Build Modes

### Development
```bash
# Run in development mode
flutter run --dart-define=ENV=development

# Build APK
flutter build apk --dart-define=ENV=development
```

### Staging
```bash
# Run in staging mode
flutter run --dart-define=ENV=staging

# Build APK
flutter build apk --dart-define=ENV=staging
```

### Production
```bash
# Run in production mode
flutter run --dart-define=ENV=production

# Build APK (release)
flutter build apk --release --dart-define=ENV=production

# Build App Bundle (for Play Store)
flutter build appbundle --release --dart-define=ENV=production
```

---

## Build Configuration

### Android (android/app/build.gradle)
```gradle
android {
    defaultConfig {
        applicationId "com.livora.app"
        minSdkVersion 24
        targetSdkVersion 34
        versionCode flutterVersionCode.toInteger()
        versionName flutterVersionName
    }

    buildTypes {
        debug {
            applicationIdSuffix ".dev"
            versionNameSuffix "-dev"
            debuggable true
        }
        
        staging {
            applicationIdSuffix ".staging"
            versionNameSuffix "-staging"
            debuggable true
            signingConfig signingConfigs.debug
        }
        
        release {
            debuggable false
            minifyEnabled true
            shrinkResources true
            proguardFiles getDefaultProguardFile('proguard-android-optimize.txt'), 'proguard-rules.pro'
            signingConfig signingConfigs.release
        }
    }
}
```

### iOS (ios/Runner/Info.plist)
```xml
<key>CFBundleDisplayName</key>
<string>$(APP_DISPLAY_NAME)</string>

<key>CFBundleIdentifier</key>
<string>$(PRODUCT_BUNDLE_IDENTIFIER)</string>
```

---

## Network Configuration

### Timeout Settings
```dart
// core/network/dio_client.dart
class DioClient {
  Dio get dio {
    final dio = Dio();
    
    dio.options.baseUrl = ApiConstants.baseUrl;
    dio.options.connectTimeout = Duration(
      milliseconds: ApiConstants.connectionTimeout,
    );
    dio.options.receiveTimeout = Duration(
      milliseconds: ApiConstants.receiveTimeout,
    );
    
    return dio;
  }
}
```

### Retry Configuration
```dart
// core/network/retry_interceptor.dart
class RetryInterceptor extends Interceptor {
  final int maxRetries = 3;
  final Duration retryDelay = const Duration(seconds: 2);
  
  @override
  void onError(DioException err, ErrorInterceptorHandler handler) async {
    if (err.type == DioExceptionType.connectionTimeout ||
        err.type == DioExceptionType.receiveTimeout) {
      
      final retryCount = err.requestOptions.extra['retryCount'] ?? 0;
      
      if (retryCount < maxRetries) {
        await Future.delayed(retryDelay);
        
        err.requestOptions.extra['retryCount'] = retryCount + 1;
        
        try {
          final response = await dio.fetch(err.requestOptions);
          handler.resolve(response);
        } catch (e) {
          handler.next(err);
        }
      } else {
        handler.next(err);
      }
    } else {
      handler.next(err);
    }
  }
}
```

---

## Cache Configuration

### Shared Preferences Setup
```dart
// core/cache/cache_manager.dart
class CacheManager {
  final SharedPreferences prefs;
  
  CacheManager(this.prefs);
  
  Future<void> cacheData(String key, String data) async {
    await prefs.setString(key, data);
    await prefs.setInt('${key}_timestamp', DateTime.now().millisecondsSinceEpoch);
  }
  
  String? getCachedData(String key, {int maxAgeSeconds = 3600}) {
    final timestamp = prefs.getInt('${key}_timestamp');
    if (timestamp == null) return null;
    
    final age = DateTime.now().millisecondsSinceEpoch - timestamp;
    if (age > maxAgeSeconds * 1000) {
      // Cache expired
      return null;
    }
    
    return prefs.getString(key);
  }
}
```

---

## Logging Configuration

### Development Logging
```dart
// core/utils/logger.dart
class AppLogger {
  static final bool _isDebugMode = dotenv.env['DEBUG_MODE'] == 'true';
  static final bool _enableApiLogging = dotenv.env['ENABLE_API_LOGGING'] == 'true';
  
  static void logApi(String message) {
    if (_enableApiLogging) {
      print('[API] $message');
    }
  }
  
  static void logError(String message, [dynamic error, StackTrace? stackTrace]) {
    if (_isDebugMode) {
      print('[ERROR] $message');
      if (error != null) print('Error: $error');
      if (stackTrace != null) print('StackTrace: $stackTrace');
    }
  }
  
  static void logInfo(String message) {
    if (_isDebugMode) {
      print('[INFO] $message');
    }
  }
}
```

### Dio Logging Interceptor
```dart
// core/network/logging_interceptor.dart
class LoggingInterceptor extends Interceptor {
  final bool enableLogging = dotenv.env['ENABLE_API_LOGGING'] == 'true';
  
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    if (enableLogging) {
      AppLogger.logApi('REQUEST[${options.method}] => PATH: ${options.path}');
      AppLogger.logApi('Headers: ${options.headers}');
      AppLogger.logApi('Data: ${options.data}');
    }
    handler.next(options);
  }
  
  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) {
    if (enableLogging) {
      AppLogger.logApi('RESPONSE[${response.statusCode}] => PATH: ${response.requestOptions.path}');
      AppLogger.logApi('Data: ${response.data}');
    }
    handler.next(response);
  }
  
  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    if (enableLogging) {
      AppLogger.logError('ERROR[${err.response?.statusCode}] => PATH: ${err.requestOptions.path}');
      AppLogger.logError('Message: ${err.message}');
    }
    handler.next(err);
  }
}
```

---

## App Versioning

### Version Management
```yaml
# pubspec.yaml
version: 1.0.0+1
# Format: MAJOR.MINOR.PATCH+BUILD_NUMBER
```

### Version Display
```dart
// presentation/pages/profile/profile_page.dart
import 'package:package_info_plus/package_info_plus.dart';

class ProfilePage extends StatefulWidget {
  @override
  _ProfilePageState createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage> {
  String _appVersion = '';
  
  @override
  void initState() {
    super.initState();
    _loadAppVersion();
  }
  
  Future<void> _loadAppVersion() async {
    final packageInfo = await PackageInfo.fromPlatform();
    setState(() {
      _appVersion = '${packageInfo.version} (${packageInfo.buildNumber})';
    });
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        children: [
          Text('Version: $_appVersion'),
        ],
      ),
    );
  }
}
```

---

## Security Configuration

### SSL Pinning (Production Only)
```dart
// core/network/ssl_pinning.dart
class SslPinning {
  static Future<SecurityContext> get securityContext async {
    final context = SecurityContext.defaultContext;
    
    if (dotenv.env['APP_ENV'] == 'production') {
      // Load certificate
      final cert = await rootBundle.load('assets/certificates/livora.pem');
      context.setTrustedCertificatesBytes(cert.buffer.asUint8List());
    }
    
    return context;
  }
}
```

### Obfuscation (Release Build)
```bash
# Build with obfuscation
flutter build apk --release --obfuscate --split-debug-info=build/app/outputs/symbols
```

---

## CI/CD Configuration

### GitHub Actions (.github/workflows/flutter.yml)
```yaml
name: Flutter CI

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main, develop ]

jobs:
  build:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3
    
    - uses: subosito/flutter-action@v2
      with:
        flutter-version: '3.16.0'
    
    - name: Install dependencies
      run: flutter pub get
    
    - name: Run tests
      run: flutter test
    
    - name: Build APK (Development)
      run: flutter build apk --dart-define=ENV=development
    
    - name: Upload APK
      uses: actions/upload-artifact@v3
      with:
        name: app-dev.apk
        path: build/app/outputs/flutter-apk/app-release.apk
```

---

## Environment Checklist

### Before Development
- [ ] Copy `.env.example` to `.env.development`
- [ ] Set `BASE_URL` to local Laravel server
- [ ] Enable debug mode and logging
- [ ] Test API connection

### Before Staging
- [ ] Create `.env.staging`
- [ ] Set `BASE_URL` to staging server
- [ ] Test all API endpoints
- [ ] Verify payment integration

### Before Production
- [ ] Create `.env.production`
- [ ] Set `BASE_URL` to production server
- [ ] Disable debug mode and API logging
- [ ] Enable SSL pinning
- [ ] Test with production data
- [ ] Generate signed APK/App Bundle
