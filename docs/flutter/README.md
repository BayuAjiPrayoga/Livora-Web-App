# Flutter Documentation Index - Livora Mobile

## 📚 Dokumentasi Teknis Flutter

Dokumentasi lengkap untuk development aplikasi mobile Livora menggunakan Flutter.

---

## 📋 Daftar Dokumen

### [WAJIB] Core Documentation

#### 1. [Mobile App Specification](mobile_app_spec.md)
**Deskripsi**: Spesifikasi lengkap aplikasi mobile Livora  
**Isi**:
- App overview dan platform target
- User roles (Guest, Tenant, Owner)
- Core features dan flow
- Non-functional requirements
- Design reference dan color scheme
- Success metrics

**Kapan Dibaca**: Sebelum memulai development

---

#### 2. [API Contract](api_contract.md)
**Deskripsi**: Kontrak API lengkap antara Flutter dan Laravel backend  
**Isi**:
- Base URL dan authentication
- 18 API endpoints dengan detail:
  - Authentication (Login, Register, Logout, Get User)
  - Properties (Browse, Detail)
  - Rooms (Detail)
  - Bookings (List, Create, Detail, Cancel)
  - Payments (Upload, History)
  - Owner (Dashboard, Verify/Reject Payment)
- Request/response format lengkap
- Validation rules
- Error handling

**Kapan Dibaca**: Saat implementasi API integration

---

#### 3. [Flutter Architecture](flutter_architecture.md)
**Deskripsi**: Arsitektur aplikasi Flutter dengan Clean Architecture + MVVM  
**Isi**:
- Architecture pattern (Clean Architecture + MVVM)
- Layer responsibility (Presentation, Domain, Data, Core)
- State management (Provider)
- Networking (Dio)
- Dependency injection (GetIt)
- Error handling pattern
- Navigation (GoRouter)
- Data flow diagram

**Kapan Dibaca**: Sebelum setup project structure

---

#### 4. [Project Structure](project_structure.md)
**Deskripsi**: Struktur folder dan file Flutter project  
**Isi**:
- Complete folder hierarchy
- Penjelasan setiap folder dan file
- Naming conventions
- Import organization
- Development workflow
- Testing structure

**Kapan Dibaca**: Saat setup project baru

---

### [DISARANKAN] Integration Documentation

#### 5. [Backend-Flutter Mapping](backend_flutter_mapping.md)
**Deskripsi**: Mapping controller Laravel ke screen Flutter  
**Isi**:
- Authentication flow mapping
- Property browsing integration
- Booking management integration
- Payment management integration
- Owner dashboard integration
- Error handling mapping
- Image handling
- Date & currency handling
- Role-based UI

**Kapan Dibaca**: Saat implementasi fitur spesifik

---

#### 6. [Environment Configuration](env_config.md)
**Deskripsi**: Setup environment untuk development, staging, dan production  
**Isi**:
- Environment variables (.env files)
- Build modes (dev, staging, production)
- Network configuration (timeout, retry)
- Cache configuration
- Logging configuration
- App versioning
- Security (SSL pinning, obfuscation)
- CI/CD setup

**Kapan Dibaca**: Saat setup project dan deployment

---

#### 7. [Flutter Dependencies](flutter_dependencies.md)
**Deskripsi**: Daftar lengkap package Flutter yang digunakan  
**Isi**:
- Core dependencies (Provider, Dio, GetIt, GoRouter)
- UI dependencies (Image, Date, Loading, Forms)
- Utility dependencies (Storage, Permissions, URL Launcher)
- Complete pubspec.yaml
- Installation commands
- Platform-specific configuration
- Troubleshooting

**Kapan Dibaca**: Saat setup dependencies

---

#### 8. [Navigation Flow](navigation_flow.md)
**Deskripsi**: Alur navigasi aplikasi untuk semua user roles  
**Isi**:
- Guest flow (unauthenticated)
- Tenant flow (authenticated)
- Owner flow (authenticated)
- Detailed screen flows (Auth, Browse, Booking, Payment, Profile)
- Navigation patterns (Bottom Nav, Push, Pop)
- Deep linking
- Auth guards
- Transition animations

**Kapan Dibaca**: Saat implementasi navigation

---

### [OPSIONAL] Reference Documentation

#### 9. [ERD Reference](erd_reference.md)
**Deskripsi**: Referensi database entities dan relationships  
**Isi**:
- Core entities (User, BoardingHouse, Room, Booking, Payment, Facility, Ticket, Notification)
- Fields dan data types
- Relationships (1:N, N:M)
- Business rules
- Status flows
- Flutter model mapping
- Data integrity rules

**Kapan Dibaca**: Saat perlu memahami struktur data backend

---

## 🚀 Quick Start Guide

### 1. Pahami Requirement
```
Baca: mobile_app_spec.md
```

### 2. Setup Project
```
Baca: project_structure.md
Baca: flutter_dependencies.md
Baca: env_config.md
```

### 3. Implementasi Architecture
```
Baca: flutter_architecture.md
Setup: Dependency Injection, Networking, State Management
```

### 4. Implementasi Fitur
```
Baca: api_contract.md
Baca: backend_flutter_mapping.md
Baca: navigation_flow.md
Implementasi: Authentication → Browse → Booking → Payment
```

### 5. Testing & Deployment
```
Baca: env_config.md (CI/CD section)
Test: Unit, Widget, Integration
Build: APK/App Bundle
```

---

## 📖 Reading Order by Role

### Flutter Developer (Baru di Project)
1. mobile_app_spec.md - Pahami app overview
2. flutter_architecture.md - Pahami arsitektur
3. project_structure.md - Pahami struktur folder
4. flutter_dependencies.md - Setup dependencies
5. api_contract.md - Pahami API
6. backend_flutter_mapping.md - Pahami integrasi
7. navigation_flow.md - Implementasi navigation
8. erd_reference.md - Referensi data

### Backend Developer (Ingin Tahu Flutter Integration)
1. mobile_app_spec.md - Pahami requirement mobile
2. api_contract.md - Lihat API yang dibutuhkan
3. backend_flutter_mapping.md - Pahami integrasi
4. erd_reference.md - Validasi data structure

### UI/UX Designer
1. mobile_app_spec.md - Design reference
2. navigation_flow.md - User flow
3. backend_flutter_mapping.md - Screen mapping

### Project Manager / Tech Lead
1. mobile_app_spec.md - Requirement overview
2. flutter_architecture.md - Technical approach
3. flutter_dependencies.md - Technology stack
4. env_config.md - Deployment strategy

---

## 🔗 Related Documentation

### Backend Documentation
- **README.md** - Livora backend overview
- **docs/MODELS.md** - Laravel models
- **docs/CONTROLLERS.md** - Laravel controllers
- **docs/ROUTES.md** - Laravel routes
- **docs/ENVIRONMENT.md** - Laravel environment setup

### API Documentation
- **Production API**: https://livora-web-app-production.up.railway.app/api/v1
- **Postman Collection**: (Available on request)

---

## 📝 Document Maintenance

### Update Frequency
- **mobile_app_spec.md**: Update saat ada perubahan requirement
- **api_contract.md**: Update saat ada perubahan API
- **flutter_architecture.md**: Update saat ada perubahan arsitektur
- **project_structure.md**: Update saat ada perubahan struktur folder
- **backend_flutter_mapping.md**: Update saat ada perubahan integrasi
- **env_config.md**: Update saat ada perubahan environment
- **flutter_dependencies.md**: Update saat ada perubahan dependencies
- **navigation_flow.md**: Update saat ada perubahan flow
- **erd_reference.md**: Update saat ada perubahan database schema

### Version Control
- Semua dokumen di-track di Git
- Gunakan commit message yang jelas saat update dokumentasi
- Review dokumentasi setiap sprint

---

## 💡 Tips

### Untuk Developer Baru
1. Jangan skip `mobile_app_spec.md` - ini foundation understanding
2. Pahami `flutter_architecture.md` sebelum coding - ini akan save waktu
3. Gunakan `api_contract.md` sebagai referensi saat implementasi API
4. Ikuti `project_structure.md` untuk konsistensi code

### Untuk Code Review
1. Pastikan code mengikuti arsitektur di `flutter_architecture.md`
2. Validasi API integration sesuai `api_contract.md`
3. Check navigation flow sesuai `navigation_flow.md`
4. Verify dependencies sesuai `flutter_dependencies.md`

### Untuk Debugging
1. Check API response format di `api_contract.md`
2. Verify data mapping di `backend_flutter_mapping.md`
3. Check entity structure di `erd_reference.md`
4. Review error handling di `flutter_architecture.md`

---

## 📞 Support

Jika ada pertanyaan atau butuh klarifikasi:
1. Check dokumentasi terkait terlebih dahulu
2. Diskusi dengan team lead
3. Update dokumentasi jika ada informasi baru

---

**Last Updated**: 21 Desember 2025  
**Version**: 1.0.0  
**Status**: ✅ Complete - Ready for Development

---

**LIVORA Mobile** - Live Better, Stay Better 🏠✨
