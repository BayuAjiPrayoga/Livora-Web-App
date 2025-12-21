# Flutter Architecture - Livora Mobile

## Architecture Pattern

**Selected**: **Clean Architecture with MVVM (Model-View-ViewModel)**

### Rationale
- **Separation of Concerns**: Clear separation between UI, business logic, and data
- **Testability**: Easy to unit test business logic independently
- **Scalability**: Easy to add new features without affecting existing code
- **Maintainability**: Code is organized and easy to understand
- **Industry Standard**: Widely adopted in Flutter community

## Layer Responsibility

### 1. Presentation Layer (UI)
**Location**: `lib/presentation/`

**Responsibility**:
- Display UI components
- Handle user interactions
- Observe ViewModel state changes
- Navigate between screens

**Components**:
- **Pages**: Full-screen widgets (e.g., `HomePage`, `LoginPage`)
- **Widgets**: Reusable UI components (e.g., `PropertyCard`, `BookingCard`)
- **ViewModels**: State management and business logic orchestration

**Example**:
```dart
// presentation/pages/home/home_page.dart
class HomePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Consumer<HomeViewModel>(
      builder: (context, viewModel, child) {
        return Scaffold(
          body: viewModel.isLoading 
            ? LoadingWidget() 
            : PropertyListWidget(properties: viewModel.properties),
        );
      },
    );
  }
}
```

---

### 2. Domain Layer (Business Logic)
**Location**: `lib/domain/`

**Responsibility**:
- Define business entities
- Define use cases (business operations)
- Define repository interfaces (contracts)
- Pure Dart code (no Flutter dependencies)

**Components**:
- **Entities**: Business models (e.g., `User`, `Property`, `Booking`)
- **Use Cases**: Single responsibility operations (e.g., `LoginUseCase`, `CreateBookingUseCase`)
- **Repository Interfaces**: Abstract contracts for data operations

**Example**:
```dart
// domain/entities/property.dart
class Property {
  final int id;
  final String name;
  final String city;
  final double priceStart;
  final List<String> images;
  
  Property({required this.id, required this.name, ...});
}

// domain/usecases/get_properties_usecase.dart
class GetPropertiesUseCase {
  final PropertyRepository repository;
  
  GetPropertiesUseCase(this.repository);
  
  Future<Either<Failure, List<Property>>> call({
    String? search,
    String? city,
    int page = 1,
  }) async {
    return await repository.getProperties(
      search: search,
      city: city,
      page: page,
    );
  }
}
```

---

### 3. Data Layer (Data Management)
**Location**: `lib/data/`

**Responsibility**:
- Implement repository interfaces
- Handle API calls
- Parse JSON responses
- Cache data (optional)
- Handle errors

**Components**:
- **Models**: Data transfer objects (DTOs) with JSON serialization
- **Data Sources**: Remote (API) and Local (Cache/Database)
- **Repositories**: Implementation of domain repository interfaces

**Example**:
```dart
// data/models/property_model.dart
class PropertyModel {
  final int id;
  final String name;
  final String city;
  
  PropertyModel({required this.id, required this.name, required this.city});
  
  factory PropertyModel.fromJson(Map<String, dynamic> json) {
    return PropertyModel(
      id: json['id'],
      name: json['name'],
      city: json['city'],
    );
  }
  
  Property toEntity() {
    return Property(id: id, name: name, city: city);
  }
}

// data/repositories/property_repository_impl.dart
class PropertyRepositoryImpl implements PropertyRepository {
  final PropertyRemoteDataSource remoteDataSource;
  
  PropertyRepositoryImpl(this.remoteDataSource);
  
  @override
  Future<Either<Failure, List<Property>>> getProperties({
    String? search,
    String? city,
    int page = 1,
  }) async {
    try {
      final models = await remoteDataSource.getProperties(
        search: search,
        city: city,
        page: page,
      );
      final entities = models.map((m) => m.toEntity()).toList();
      return Right(entities);
    } on ServerException {
      return Left(ServerFailure());
    }
  }
}
```

---

### 4. Core Layer (Shared Utilities)
**Location**: `lib/core/`

**Responsibility**:
- Shared utilities and helpers
- Constants and configurations
- Error handling
- Network client setup
- Dependency injection setup

**Components**:
- **Network**: HTTP client, interceptors, error handling
- **Utils**: Date formatters, validators, helpers
- **Constants**: API URLs, app constants
- **Errors**: Custom exceptions and failures
- **DI**: Dependency injection container

---

## State Management

**Selected**: **Provider**

### Rationale
- **Official**: Recommended by Flutter team
- **Simple**: Easy to learn and implement
- **Lightweight**: Minimal boilerplate
- **Scalable**: Works well for small to large apps
- **Community**: Large community support

### Alternative Considered
- **Riverpod**: More modern, but steeper learning curve
- **Bloc**: More boilerplate, overkill for this app
- **GetX**: Less separation of concerns

### Implementation Pattern

```dart
// presentation/viewmodels/home_viewmodel.dart
class HomeViewModel extends ChangeNotifier {
  final GetPropertiesUseCase getPropertiesUseCase;
  
  HomeViewModel(this.getPropertiesUseCase);
  
  // State
  List<Property> _properties = [];
  bool _isLoading = false;
  String? _errorMessage;
  
  // Getters
  List<Property> get properties => _properties;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  
  // Actions
  Future<void> loadProperties({String? search, String? city}) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();
    
    final result = await getPropertiesUseCase(search: search, city: city);
    
    result.fold(
      (failure) {
        _errorMessage = _mapFailureToMessage(failure);
        _isLoading = false;
        notifyListeners();
      },
      (properties) {
        _properties = properties;
        _isLoading = false;
        notifyListeners();
      },
    );
  }
  
  String _mapFailureToMessage(Failure failure) {
    if (failure is ServerFailure) return 'Server error';
    if (failure is NetworkFailure) return 'No internet connection';
    return 'Unexpected error';
  }
}
```

---

## Networking

### HTTP Client: **Dio**

**Rationale**:
- **Interceptors**: Easy to add auth token, logging
- **Error Handling**: Better error handling than http package
- **File Upload**: Built-in multipart support
- **Timeout**: Easy timeout configuration

### Setup

```dart
// core/network/dio_client.dart
class DioClient {
  final Dio dio;
  final AuthLocalDataSource authLocalDataSource;
  
  DioClient(this.authLocalDataSource) : dio = Dio() {
    dio
      ..options.baseUrl = AppConstants.baseUrl
      ..options.connectTimeout = const Duration(seconds: 30)
      ..options.receiveTimeout = const Duration(seconds: 30)
      ..options.headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      }
      ..interceptors.add(AuthInterceptor(authLocalDataSource))
      ..interceptors.add(LogInterceptor(
        request: true,
        requestBody: true,
        responseBody: true,
        error: true,
      ));
  }
}

// core/network/auth_interceptor.dart
class AuthInterceptor extends Interceptor {
  final AuthLocalDataSource authLocalDataSource;
  
  AuthInterceptor(this.authLocalDataSource);
  
  @override
  void onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    final token = await authLocalDataSource.getToken();
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }
    handler.next(options);
  }
  
  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    if (err.response?.statusCode == 401) {
      // Handle unauthorized - logout user
      authLocalDataSource.clearToken();
    }
    handler.next(err);
  }
}
```

---

## Dependency Injection

**Selected**: **GetIt + Injectable**

**Rationale**:
- **Service Locator**: Simple and powerful
- **Code Generation**: Injectable reduces boilerplate
- **Singleton Support**: Easy singleton management
- **Lazy Loading**: Dependencies loaded when needed

### Setup

```dart
// core/di/injection.dart
final getIt = GetIt.instance;

@InjectableInit()
Future<void> configureDependencies() async {
  // External dependencies
  final sharedPreferences = await SharedPreferences.getInstance();
  getIt.registerSingleton<SharedPreferences>(sharedPreferences);
  
  final secureStorage = const FlutterSecureStorage();
  getIt.registerSingleton<FlutterSecureStorage>(secureStorage);
  
  // Network
  getIt.registerLazySingleton<DioClient>(
    () => DioClient(getIt<AuthLocalDataSource>()),
  );
  
  // Data Sources
  getIt.registerLazySingleton<AuthLocalDataSource>(
    () => AuthLocalDataSourceImpl(getIt<FlutterSecureStorage>()),
  );
  
  getIt.registerLazySingleton<PropertyRemoteDataSource>(
    () => PropertyRemoteDataSourceImpl(getIt<DioClient>()),
  );
  
  // Repositories
  getIt.registerLazySingleton<AuthRepository>(
    () => AuthRepositoryImpl(
      remoteDataSource: getIt<AuthRemoteDataSource>(),
      localDataSource: getIt<AuthLocalDataSource>(),
    ),
  );
  
  // Use Cases
  getIt.registerLazySingleton(() => LoginUseCase(getIt<AuthRepository>()));
  getIt.registerLazySingleton(() => GetPropertiesUseCase(getIt<PropertyRepository>()));
  
  // ViewModels
  getIt.registerFactory(() => LoginViewModel(getIt<LoginUseCase>()));
  getIt.registerFactory(() => HomeViewModel(getIt<GetPropertiesUseCase>()));
}
```

---

## Error Handling

### Failure Classes

```dart
// core/errors/failures.dart
abstract class Failure {
  final String message;
  Failure(this.message);
}

class ServerFailure extends Failure {
  ServerFailure([String message = 'Server error']) : super(message);
}

class NetworkFailure extends Failure {
  NetworkFailure([String message = 'No internet connection']) : super(message);
}

class CacheFailure extends Failure {
  CacheFailure([String message = 'Cache error']) : super(message);
}

class ValidationFailure extends Failure {
  ValidationFailure(String message) : super(message);
}
```

### Exception Classes

```dart
// core/errors/exceptions.dart
class ServerException implements Exception {
  final String message;
  ServerException(this.message);
}

class NetworkException implements Exception {}

class CacheException implements Exception {}
```

### Error Mapping

```dart
// In Repository
try {
  final response = await remoteDataSource.login(email, password);
  return Right(response);
} on ServerException catch (e) {
  return Left(ServerFailure(e.message));
} on SocketException {
  return Left(NetworkFailure());
} catch (e) {
  return Left(ServerFailure('Unexpected error'));
}
```

---

## Navigation

**Selected**: **GoRouter**

**Rationale**:
- **Declarative**: URL-based routing
- **Deep Linking**: Easy deep link support
- **Type Safety**: Type-safe navigation
- **Redirect**: Easy auth guard implementation

### Setup

```dart
// core/router/app_router.dart
final goRouter = GoRouter(
  initialLocation: '/',
  redirect: (context, state) {
    final authViewModel = getIt<AuthViewModel>();
    final isLoggedIn = authViewModel.isLoggedIn;
    final isGoingToLogin = state.matchedLocation == '/login';
    
    if (!isLoggedIn && !isGoingToLogin) {
      return '/login';
    }
    
    if (isLoggedIn && isGoingToLogin) {
      return '/';
    }
    
    return null;
  },
  routes: [
    GoRoute(
      path: '/',
      builder: (context, state) => const HomePage(),
    ),
    GoRoute(
      path: '/login',
      builder: (context, state) => const LoginPage(),
    ),
    GoRoute(
      path: '/property/:slug',
      builder: (context, state) {
        final slug = state.pathParameters['slug']!;
        return PropertyDetailPage(slug: slug);
      },
    ),
    GoRoute(
      path: '/booking/create',
      builder: (context, state) {
        final roomId = state.uri.queryParameters['roomId']!;
        return CreateBookingPage(roomId: int.parse(roomId));
      },
    ),
  ],
);
```

---

## Data Flow Summary

```
User Interaction (UI)
    ↓
ViewModel (State Management)
    ↓
Use Case (Business Logic)
    ↓
Repository Interface (Domain)
    ↓
Repository Implementation (Data)
    ↓
Data Source (Remote API / Local Cache)
    ↓
Network Client (Dio)
    ↓
Backend API
```

**Response Flow** (reverse):
```
Backend API Response
    ↓
Network Client (Dio)
    ↓
Data Source (Parse JSON to Model)
    ↓
Repository (Convert Model to Entity)
    ↓
Use Case (Return Either<Failure, Entity>)
    ↓
ViewModel (Update State)
    ↓
UI (Rebuild with new state)
```

---

## Best Practices

1. **Single Responsibility**: Each class has one clear purpose
2. **Dependency Inversion**: Depend on abstractions, not implementations
3. **Immutable Entities**: Domain entities should be immutable
4. **Error Handling**: Always use Either<Failure, Success> pattern
5. **Null Safety**: Enable null safety, avoid null checks
6. **Code Generation**: Use freezed for entities, json_serializable for models
7. **Testing**: Write unit tests for use cases and view models
8. **Documentation**: Document complex business logic
9. **Naming**: Use clear, descriptive names
10. **Folder Structure**: Follow consistent folder structure
