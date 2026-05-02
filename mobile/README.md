# MamaCare AI - Mobile Application

<div align="center">

[![Flutter](https://img.shields.io/badge/Flutter-3.x-02569B?style=for-the-badge&logo=flutter)](https://flutter.dev)
[![Dart](https://img.shields.io/badge/Dart-3.x-00B4AB?style=for-the-badge&logo=dart)](https://dart.dev)
[![Platform](https://img.shields.io/badge/Platform-iOS%20%7C%20Android-6E6E6E?style=for-the-badge)](https://flutter.dev)

**Cross-platform mobile application for maternal health management**

[Features](#features) • [Getting Started](#getting-started) • [Screenshots](#screenshots)

</div>

---

## Features

### Core Features

| Feature | Description | Status |
|---------|-------------|--------|
| **Authentication** | Email, Google, and Apple Sign-in | Live |
| **Pregnancy Tracking** | Week-by-week pregnancy progress | Live |
| **Health Metrics** | BP, weight, and kick count tracking | Live |
| **Appointments** | View and manage clinic visits | Live |
| **Daily Log** | Track symptoms and health status | Live |
| **Educational Articles** | Browse maternal health content | Live |
| **Emergency SOS** | Quick access to emergency numbers | Live |
| **Offline Support** | Access data without internet | Live |
| **Push Notifications** | Health reminders and alerts | Live |

### User Flow

```
Splash Screen → Welcome Screen → Login/Register → Dashboard
                                                    ↓
                    ┌───────────────────────────────┼───────────────────────────────┐
                    ↓                               ↓                               ↓
              Appointments                    Health Data                    Education
                    ↓                               ↓                               ↓
              Book/Manage                    BP/Weight/Kicks                  Articles
```

---

## Getting Started

### Prerequisites

- Flutter SDK 3.0.0 or higher
- Dart SDK 3.0.0 or higher
- Android Studio / VS Code with Flutter extension
- Android SDK (for Android development)
- Xcode (for iOS development - macOS only)

### Installation

```bash
# 1. Navigate to mobile directory
cd mobile

# 2. Install dependencies
flutter pub get

# 3. Run the app
flutter run

# For specific device
flutter run -d <device_id>
```

### Building for Production

```bash
# Android APK
flutter build apk --release

# Android App Bundle
flutter build appbundle --release

# iOS
flutter build ios --release

# Web
flutter build web --release
```

---

## Project Structure

```
lib/
├── main.dart                 # App entry point
├── screens/                  # UI Screens
│   ├── splash_screen.dart    # App initialization
│   ├── welcome_screen.dart   # Onboarding
│   ├── login_screen.dart     # Authentication
│   ├── register_screen.dart  # Registration
│   ├── dashboard_screen.dart # Main dashboard
│   ├── articles_screen.dart  # Articles list
│   └── article_detail_screen.dart # Article detail
├── models/                   # Data models
├── services/                 # API services
├── providers/                # State management
├── widgets/                  # Reusable widgets
└── utils/                    # Utilities & helpers
```

---

## Screens

### 1. Splash Screen
- App logo animation
- Authentication status check
- Auto-navigation to appropriate screen

### 2. Welcome Screen
- Feature highlights
- Call-to-action for login/register
- Beautiful animations

### 3. Login Screen
- Email/password authentication
- Google Sign-in
- Apple Sign-in (iOS)
- Password reset option

### 4. Register Screen
- New mother registration
- Form validation
- Progress indicators

### 5. Dashboard Screen
- Pregnancy week display
- Health metrics overview
- Quick action buttons
- Upcoming appointments

### 6. Articles Screen
- Educational content list
- Category filtering
- Search functionality

---

## Dependencies

```yaml
dependencies:
  flutter:
    sdk: flutter
  cupertino_icons: ^1.0.2
  http: ^1.1.0              # API communication
  shared_preferences: ^2.2.2  # Local storage
  provider: ^6.1.1          # State management
  flutter_svg: ^2.0.9       # SVG support
  google_fonts: ^6.1.0      # Custom fonts
  intl: ^0.19.0             # Internationalization
  flutter_screenutil: ^5.9.0 # Responsive UI
  cached_network_image: ^3.3.0 # Image caching
  shimmer: ^3.0.0           # Loading effects
  flutter_spinkit: ^5.2.0   # Loading animations
  animate_do: ^3.1.2        # Animations
  flutter_launcher_icons: ^0.13.1 # App icons
  url_launcher: ^6.2.2      # Launch URLs
  fluttertoast: ^8.2.4      # Toast messages
  get: ^4.6.6               # Navigation & state
  get_storage: ^2.1.1       # Storage helper
```

---

## API Integration

The app connects to the MamaCare AI Laravel backend:

```dart
// Base API URL
const String API_BASE_URL = "https://mamcareai.co.tz/api";

// Authentication
POST /api/auth/login
POST /api/auth/register
POST /api/auth/logout

// Mother Data
GET  /api/mother/dashboard
GET  /api/mother/health-data
POST /api/mother/appointments
```

---

## Design System

### Colors
- Primary: `#1E40AF` (Blue)
- Secondary: `#F97316` (Orange)
- Success: `#22C55E` (Green)
- Danger: `#EF4444` (Red)
- Background: `#F8FAFC` (Light Gray)

### Typography
- Headings: Inter / Poppins
- Body: Roboto / Open Sans

---

## Development Guidelines

### Code Style
- Follow Dart style guide
- Use meaningful variable names
- Add comments for complex logic
- Keep widgets small and reusable

### State Management
- Use Provider for global state
- Use GetX for navigation
- Use setState for local UI state only

### Performance
- Use const constructors where possible
- Implement image caching
- Lazy load heavy widgets
- Use shimmer for loading states

---

## Troubleshooting

### Common Issues

**Build fails on Android:**
```bash
flutter clean
flutter pub get
cd android && ./gradlew clean && cd ..
flutter build apk
```

**iOS build issues:**
```bash
cd ios
pod deintegrate
pod install
```

**Hot reload not working:**
- Check that you're in debug mode
- Save the file explicitly (Ctrl+S)

---

## Contributing

1. Create a feature branch
2. Make your changes
3. Test on both iOS and Android
4. Submit a pull request

---

## License

This project is part of MamaCare AI and is licensed under the MIT License.

---

<div align="center">

**Made with care in Tanzania**

[Back to Top](#mamacare-ai---mobile-application)

</div>
