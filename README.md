# 🤰 MamaCare AI - Maternal Health Management System

<div align="center">

[![Laravel](https://img.shields.io/badge/Laravel-13.x-red?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Flutter](https://img.shields.io/badge/Flutter-3.x-02569B?style=for-the-badge&logo=flutter)](https://flutter.dev)
[![Node.js](https://img.shields.io/badge/Node.js-18.x-339933?style=for-the-badge&logo=node.js)](https://nodejs.org)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**MamaCare AI** is a comprehensive maternal health management platform combining web ERP, mobile applications, and WhatsApp integration to provide end-to-end healthcare solutions for mothers in Tanzania and beyond.

[🌐 Live Demo](https://mamcareai.co.tz) • [📖 Documentation](#documentation) • [🚀 Quick Start](#quick-start) • [📱 Mobile App](#mobile-application)

</div>

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Architecture](#-architecture)
- [Technology Stack](#-technology-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Modules](#-modules)
- [API Documentation](#-api-documentation)
- [Mobile Application](#-mobile-application)
- [WhatsApp Integration](#-whatsapp-integration)
- [Screenshots](#-screenshots)
- [Contributing](#-contributing)
- [License](#-license)
- [Support](#-support)

---

## 🌟 Overview

MamaCare AI is an innovative healthcare technology solution designed to transform maternal health management in Tanzania. The platform addresses critical challenges in maternal healthcare by providing:

- **Digital Health Records** - Complete pregnancy journey tracking
- **AI-Powered Reminders** - Automated health reminders via WhatsApp
- **Emergency Response** - Quick access to emergency hotlines (114, 112, 999)
- **Health Monitoring** - Blood pressure, weight, and kick count tracking
- **Appointment Management** - Clinic visit scheduling and reminders
- **Educational Resources** - Pregnancy and maternal health education
- **ERP Integration** - Full business management for healthcare providers

### 🎯 Mission

To reduce maternal mortality rates and improve pregnancy outcomes through accessible, technology-driven healthcare solutions that connect mothers, healthcare providers, and emergency services.

---

## ✨ Features

### 🤰 Mother-Centric Features

| Feature | Description | Status |
|---------|-------------|--------|
| **Pregnancy Tracking** | Week-by-week pregnancy progression monitoring | ✅ Live |
| **Health Metrics** | BP, weight, kick count logging with visual charts | ✅ Live |
| **Appointment Scheduler** | Book and manage clinic appointments | ✅ Live |
| **WhatsApp Reminders** | Automated daily, weekly, and appointment reminders | ✅ Live |
| **Emergency SOS** | One-click emergency hotline access | ✅ Live |
| **Health Checklist** | Interactive trimester-based task management | ✅ Live |
| **Daily Health Log** | Symptoms, mood, and health status tracking | ✅ Live |
| **Educational Content** | Articles and videos on maternal health | ✅ Live |
| **AI Health Alerts** | Smart alerts for abnormal health readings | ✅ Live |

### 🏥 Administrative Features (ERP)

| Module | Features | Status |
|--------|----------|--------|
| **Accounting** | Double-entry bookkeeping, journals, vouchers, financial reports | ✅ Live |
| **Inventory** | Stock management, products, purchase orders | ✅ Live |
| **Sales & POS** | Point of sale, sales invoices, quotations | ✅ Live |
| **CRM** | Customer management, loyalty programs, feedback, automations | ✅ Live |
| **HRM** | Employee management, payroll, leave, attendance, assets | ✅ Live |
| **ELMS** | Education learning management system for courses | ✅ Live |
| **Investors** | Investment tracking, portfolio management, reports | ✅ Live |
| **Imports** | Import order management with CSV bulk upload | ✅ Live |

---

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        MamaCare AI Platform                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │   Web App    │  │  Mobile App  │  │  WhatsApp    │          │
│  │   (Laravel)  │  │  (Flutter)   │  │    Bot       │          │
│  │              │  │              │  │  (Node.js)   │          │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘          │
│         │                 │                 │                    │
│         └─────────────────┼─────────────────┘                    │
│                           │                                      │
│  ┌────────────────────────┴────────────────────────┐             │
│  │              API Layer (REST)                    │             │
│  └────────────────────────┬────────────────────────┘             │
│                           │                                      │
│  ┌────────────────────────┴────────────────────────┐             │
│  │           Application Layer (Laravel)              │             │
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌────────┐ │             │
│  │  │ Models  │ │Services │ │Controllers│ │Middleware│ │             │
│  │  └─────────┘ └─────────┘ └─────────┘ └────────┘ │             │
│  └────────────────────────┬────────────────────────┘             │
│                           │                                      │
│  ┌────────────────────────┴────────────────────────┐             │
│  │              Database Layer                      │             │
│  │         (SQLite / MySQL / PostgreSQL)            │             │
│  └─────────────────────────────────────────────────┘             │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### System Components

1. **Web Application (Laravel 13)**
   - Admin Dashboard with 15+ modules
   - Mother Portal with health tracking
   - Landing page with CMS features
   - Multi-role authentication system

2. **Mobile Application (Flutter)**
   - Cross-platform (iOS & Android)
   - Offline capability
   - Push notifications
   - Native performance

3. **WhatsApp Bot (Node.js)**
   - Real-time messaging
   - Automated reminders
   - Two-way communication
   - Message queuing system

---

## 💻 Technology Stack

### Backend
- **Framework**: Laravel 13.x (PHP 8.3)
- **Database**: SQLite (dev) / MySQL (production)
- **Cache**: Database/Redis
- **Queue**: Database-driven
- **Authentication**: Laravel Auth + Socialite (Google, Apple)

### Frontend
- **CSS Framework**: Tailwind CSS 4.x
- **JavaScript**: Vanilla JS + Alpine.js patterns
- **Build Tool**: Vite 8.x
- **UI Components**: Bootstrap 5.x (admin), Custom (landing)

### Mobile
- **Framework**: Flutter 3.x
- **State Management**: Provider + GetX
- **HTTP Client**: Dart http
- **Storage**: SharedPreferences + GetStorage

### WhatsApp Bot
- **Runtime**: Node.js 18+
- **Framework**: Express.js
- **Library**: whatsapp-web.js
- **Logging**: Winston

### DevOps
- **Version Control**: Git
- **Deployment**: VPS-ready with automated scripts
- **Monitoring**: Laravel Pail
- **Testing**: PHPUnit, Laravel Dusk

---

## 🚀 Installation

### Prerequisites

- PHP 8.3+
- Composer 2.x
- Node.js 18+
- NPM 9+
- SQLite / MySQL
- Flutter SDK (for mobile)

### Quick Start

```bash
# 1. Clone the repository
git clone https://github.com/your-org/mamacare-ai.git
cd mamacare-ai

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Environment setup
cp .env.example .env
php artisan key:generate

# 5. Database setup
touch database/database.sqlite
php artisan migrate --seed

# 6. Build assets
npm run build

# 7. Start development server
composer run dev
```

### Alternative: One-Command Setup

```bash
composer run setup
```

This single command will:
- Install all PHP and Node dependencies
- Set up the environment file
- Generate application key
- Run migrations with seeders
- Build frontend assets

---

## ⚙️ Configuration

### Environment Variables

Key configurations in `.env`:

```env
# Application
APP_NAME="MamaCare AI"
APP_URL=http://localhost

# Database
DB_CONNECTION=sqlite
# DB_CONNECTION=mysql (for production)

# WhatsApp Integration
WHATSAPP_ENABLED=true
WHATSAPP_BOT_URL=http://localhost:3000
WHATSAPP_API_KEY=your_secure_key_here

# Emergency Hotlines
EMERGENCY_HOTLINE_1=114
EMERGENCY_HOTLINE_2=112
EMERGENCY_HOTLINE_3=999

# Social Login (Optional)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_secret

# Health Thresholds
BP_SYSTOLIC_THRESHOLD=140
BP_DIASTOLIC_THRESHOLD=90
```

See `.env.example` for complete configuration options.

### WhatsApp Bot Setup

```bash
cd whatsapp-bot
npm install
cp .env.example .env
# Edit .env with your settings
npm start
```

---

## 📦 Modules

### 🤰 MamaCare (Maternal Health)

```php
// Key Models
Mother                  # Mother profile with pregnancy data
MotherAppointment       # Clinic appointments
MotherBloodPressure     # BP readings with alerts
MotherWeightLog         # Weight tracking
MotherKickCount         # Fetal movement tracking
MotherDailyLog          # Daily health journal
MotherHealthAlert       # AI-generated health alerts
MotherChecklistItem     # Pregnancy task checklist
```

**Features:**
- Trimester-based health tracking
- Automated WhatsApp reminders
- Health metric visualization
- Emergency SOS functionality
- Educational content delivery

### 📊 Accounting Module

```php
Account                 # Chart of accounts
Journal                 # Accounting journals
JournalLine             # Journal entries
Voucher                 # Financial vouchers
VoucherLine             # Voucher details
```

**Features:**
- Double-entry bookkeeping
- Multi-account support
- Financial reports (P&L, Balance Sheet, Trial Balance)
- Voucher types: Cash Payment, Sales Invoice, Purchase Order, etc.

### 👥 CRM Module

```php
CrmInboxMessage         # Customer messages
CrmAutomation           # Automated workflows
CrmLoyaltyAccount       # Loyalty program
CrmLoyaltyTransaction   # Points transactions
CrmPreorder             # Pre-order management
CrmReferral             # Referral tracking
CrmFeedbackEntry        # Customer feedback
CrmUpsellCampaign       # Upselling campaigns
```

### 👨‍💼 HRM Module

```php
Employee                # Employee records
HrmAsset                # Asset management
HrmLeaveType            # Leave categories
HrmLeaveRequest         # Leave applications
HrmRestModels           # Performance & recruitment
PayrollRun              # Payroll processing
Payslip                 # Employee payslips
AttendanceLog           # Attendance tracking
```

### 📚 ELMS Module

```php
ElmsCourse              # Online courses
ElmsCourseFee           # Course pricing
ElmsLevel               # Education levels
ElmsArticle             # Educational articles
ElmsArticleCategory     # Article categories
ElmsTrainer             # Course instructors
```

---

## 🔌 API Documentation

### Authentication

```http
POST /api/login
Content-Type: application/json

{
  "email": "mother@example.com",
  "password": "password"
}
```

### Mother Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/mothers/approved` | GET | List approved mothers |
| `/api/mothers/{id}/health-data` | GET | Get health metrics |
| `/api/mothers/{id}/appointments` | POST | Book appointment |

### WhatsApp Webhooks

```http
POST /api/webhooks/whatsapp-incoming
# Handle incoming WhatsApp messages

POST /api/webhooks/whatsapp-status
# Handle message delivery status

GET /api/webhooks/whatsapp-stats
# Get message statistics
```

---

## 📱 Mobile Application

The Flutter mobile app provides mothers with on-the-go access to their health information.

### Screens

| Screen | Description |
|--------|-------------|
| `SplashScreen` | App initialization and auth check |
| `WelcomeScreen` | Onboarding and feature highlights |
| `LoginScreen` | Authentication (Email, Google, Apple) |
| `RegisterScreen` | New mother registration |
| `DashboardScreen` | Health overview and quick actions |
| `ArticlesScreen` | Educational content browsing |
| `ArticleDetailScreen` | Full article reading |

### Features

- **Offline Support**: Access data without internet
- **Push Notifications**: Appointment and health reminders
- **Biometric Auth**: Fingerprint/Face ID login
- **Data Sync**: Automatic synchronization with web app

### Build Instructions

```bash
cd mobile

# Get dependencies
flutter pub get

# Run on device
flutter run

# Build APK
flutter build apk --release

# Build iOS
flutter build ios --release
```

---

## 💬 WhatsApp Integration

The WhatsApp bot provides automated communication with mothers through their preferred messaging platform.

### Message Types

| Type | Description | Frequency |
|------|-------------|-----------|
| `welcome` | Registration confirmation | On signup |
| `registration` | MK Number and login info | On signup |
| `weekly_update` | Pregnancy progress tips | Weekly |
| `daily_reminder` | Health task reminders | Daily at 9:00 AM |
| `appointment_reminder` | Clinic visit alerts | 24 hours before |
| `health_alert` | Critical health warnings | As needed |

### Sample WhatsApp Message

```
🤰 *MamaCare - Wiki ya 24*

Habari Mary,

Uko trimester 2, wiki 24.

💡 *Ushauri wa Wiki:*
• Ongeza chakula chenye chuma (iron)
• Anza maswali ya kliniki
• Sikiliza mpigo wa moyo wa mtoto

🔗 *Angalia Dashboard yako:*
https://mamcareai.co.tz/mother/dashboard

🚨 *Dharura? Piga 114*
```

---

## 📸 Screenshots

### Mother Dashboard
```
┌─────────────────────────────┐
│  🤰 MamaCare AI             │
│  Habari, Mary!              │
│                             │
│  ┌─────────────────────┐   │
│  │  Week 24            │   │
│  │  Trimester 2        │   │
│  │  ████████░░ 60%     │   │
│  └─────────────────────┘   │
│                             │
│  📊 Health Metrics:         │
│  • BP: 120/80 ✅          │
│  • Weight: 68kg (+2kg)    │
│                             │
│  📅 Next Appointment:       │
│  Tomorrow, 10:00 AM         │
│                             │
│  [🚨 Emergency] [📋 Log]   │
└─────────────────────────────┘
```

### Admin ERP Dashboard
```
┌─────────────────────────────────────────────┐
│  MamaCare AI Admin              [👤 Admin] │
├─────────────────────────────────────────────┤
│                                             │
│  📊 Quick Stats:                            │
│  ┌──────────┬──────────┬──────────┐      │
│  │ Mothers  │ Pending  │ Alerts   │      │
│  │  1,234   │   45     │   12     │      │
│  └──────────┴──────────┴──────────┘      │
│                                             │
│  🏥 Modules:                                │
│  [Accounting] [Inventory] [Sales] [CRM]  │
│  [HRM] [ELMS] [Investors] [Reports]        │
│                                             │
│  📈 Revenue Chart:                          │
│  ▁▂▄▅▇███▇▅▄▂▁ (Last 30 days)            │
│                                             │
└─────────────────────────────────────────────┘
```

---

## 🤝 Contributing

We welcome contributions! Please follow these guidelines:

### Development Workflow

1. **Fork** the repository
2. **Create** a feature branch: `git checkout -b feature/amazing-feature`
3. **Commit** changes: `git commit -m 'Add amazing feature'`
4. **Push** to branch: `git push origin feature/amazing-feature`
5. **Open** a Pull Request

### Code Standards

- Follow **PSR-12** for PHP code
- Use **Laravel Pint** for code formatting: `composer run lint`
- Write **tests** for new features
- Update **documentation** as needed

### Commit Message Format

```
type(scope): subject

body (optional)

footer (optional)
```

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

```
MIT License

Copyright (c) 2026 MamaCare AI

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```

---

## 🆘 Support

### Help Channels

| Channel | Link | Response Time |
|---------|------|---------------|
| 🐛 Bug Reports | [GitHub Issues](../../issues) | 24-48 hours |
| 💡 Feature Requests | [GitHub Discussions](../../discussions) | 48-72 hours |
| 📧 Email | support@mamcareai.co.tz | 24 hours |
| 📱 WhatsApp | +255 123 456 789 | 1-2 hours |

### Emergency

For medical emergencies, **always call:**
- 🚨 **114** - Tanzania Health Emergency
- 🚨 **112** - General Emergency
- 🚨 **999** - Police/Rescue

---

## 🙏 Acknowledgments

- **Laravel Team** for the amazing framework
- **Flutter Team** for cross-platform development
- **WhatsApp Web.js** for WhatsApp integration
- **MamaCare Team** for healthcare expertise
- **Tanzania Ministry of Health** for guidelines

---

<div align="center">

**Made with ❤️ in Tanzania**

[⬆ Back to Top](#-mamacare-ai---maternal-health-management-system)

</div>
