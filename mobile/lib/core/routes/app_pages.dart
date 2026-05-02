import 'package:get/get.dart';
import '../../modules/splash/splash_screen.dart';
import '../../modules/welcome/welcome_screen.dart';
import '../../modules/onboarding/onboarding_screen.dart';
import '../../modules/auth/login_screen.dart';
import '../../modules/auth/register_screen.dart';
import '../../modules/auth/forgot_password_screen.dart';
import '../../modules/dashboard/dashboard_screen.dart';
import '../../modules/articles/articles_screen.dart';
import '../../modules/articles/article_detail_screen.dart';
import '../../modules/articles/categories_screen.dart';
import '../../modules/health/health_screen.dart';
import '../../modules/health/weight_log_screen.dart';
import '../../modules/health/blood_pressure_screen.dart';
import '../../modules/health/kick_count_screen.dart';
import '../../modules/appointments/appointments_screen.dart';
import '../../modules/appointments/book_appointment_screen.dart';
import '../../modules/emergency/emergency_screen.dart';
import '../../modules/chat/chat_screen.dart';
import '../../modules/profile/profile_screen.dart';
import '../../modules/profile/edit_profile_screen.dart';
import '../../modules/settings/settings_screen.dart';
import '../../modules/settings/notifications_screen.dart';
import '../../modules/about/about_screen.dart';
import '../../modules/help/help_support_screen.dart';

import '../../modules/auth/controllers/auth_controller.dart';
import '../../modules/dashboard/controllers/dashboard_controller.dart';
import '../../modules/articles/controllers/articles_controller.dart';
import '../../modules/health/controllers/health_controller.dart';
import '../../modules/appointments/controllers/appointments_controller.dart';
import '../../modules/profile/controllers/profile_controller.dart';
import '../../modules/settings/controllers/settings_controller.dart';
import 'app_routes.dart';

class AppPages {
  static final pages = [
    // Splash & Onboarding
    GetPage(
      name: AppRoutes.splash,
      page: () => const SplashScreen(),
      transition: Transition.fadeIn,
    ),
    GetPage(
      name: AppRoutes.welcome,
      page: () => const WelcomeScreen(),
      transition: Transition.rightToLeft,
    ),
    GetPage(
      name: AppRoutes.onboarding,
      page: () => const OnboardingScreen(),
      transition: Transition.rightToLeft,
    ),
    
    // Auth
    GetPage(
      name: AppRoutes.login,
      page: () => const LoginScreen(),
      binding: AuthBinding(),
      transition: Transition.rightToLeft,
    ),
    GetPage(
      name: AppRoutes.register,
      page: () => const RegisterScreen(),
      binding: AuthBinding(),
      transition: Transition.rightToLeft,
    ),
    GetPage(
      name: AppRoutes.forgotPassword,
      page: () => const ForgotPasswordScreen(),
      binding: AuthBinding(),
      transition: Transition.rightToLeft,
    ),
    
    // Dashboard
    GetPage(
      name: AppRoutes.dashboard,
      page: () => const DashboardScreen(),
      binding: DashboardBinding(),
      transition: Transition.fadeIn,
    ),
    
    // Articles
    GetPage(
      name: AppRoutes.articles,
      page: () => const ArticlesScreen(),
      binding: ArticlesBinding(),
      transition: Transition.rightToLeft,
    ),
    GetPage(
      name: AppRoutes.articleDetail,
      page: () => const ArticleDetailScreen(),
      binding: ArticlesBinding(),
      transition: Transition.cupertino,
    ),
    GetPage(
      name: AppRoutes.categories,
      page: () => const CategoriesScreen(),
      binding: ArticlesBinding(),
      transition: Transition.rightToLeft,
    ),
    
    // Health
    GetPage(
      name: AppRoutes.health,
      page: () => const HealthScreen(),
      binding: HealthBinding(),
      transition: Transition.rightToLeft,
    ),
    GetPage(
      name: AppRoutes.weightLog,
      page: () => const WeightLogScreen(),
      binding: HealthBinding(),
      transition: Transition.rightToLeft,
    ),
    GetPage(
      name: AppRoutes.bloodPressure,
      page: () => const BloodPressureScreen(),
      binding: HealthBinding(),
      transition: Transition.rightToLeft,
    ),
    GetPage(
      name: AppRoutes.kickCount,
      page: () => const KickCountScreen(),
      binding: HealthBinding(),
      transition: Transition.rightToLeft,
    ),
    
    // Appointments
    GetPage(
      name: AppRoutes.appointments,
      page: () => const AppointmentsScreen(),
      binding: AppointmentsBinding(),
      transition: Transition.rightToLeft,
    ),
    GetPage(
      name: AppRoutes.bookAppointment,
      page: () => const BookAppointmentScreen(),
      binding: AppointmentsBinding(),
      transition: Transition.cupertino,
    ),
    
    // Emergency
    GetPage(
      name: AppRoutes.emergency,
      page: () => const EmergencyScreen(),
      transition: Transition.zoom,
    ),
    
    // Chat
    GetPage(
      name: AppRoutes.chat,
      page: () => const ChatScreen(),
      transition: Transition.rightToLeft,
    ),
    
    // Profile
    GetPage(
      name: AppRoutes.profile,
      page: () => const ProfileScreen(),
      binding: ProfileBinding(),
      transition: Transition.rightToLeft,
    ),
    GetPage(
      name: AppRoutes.editProfile,
      page: () => const EditProfileScreen(),
      binding: ProfileBinding(),
      transition: Transition.rightToLeft,
    ),
    
    // Settings
    GetPage(
      name: AppRoutes.settings,
      page: () => const SettingsScreen(),
      binding: SettingsBinding(),
      transition: Transition.rightToLeft,
    ),
    GetPage(
      name: AppRoutes.notifications,
      page: () => const NotificationsScreen(),
      binding: SettingsBinding(),
      transition: Transition.rightToLeft,
    ),
    
    // Info Pages
    GetPage(
      name: AppRoutes.about,
      page: () => const AboutScreen(),
      transition: Transition.rightToLeft,
    ),
    GetPage(
      name: AppRoutes.helpSupport,
      page: () => const HelpSupportScreen(),
      transition: Transition.rightToLeft,
    ),
  ];
}

// Bindings
class AuthBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<AuthController>(() => AuthController());
  }
}

class DashboardBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<DashboardController>(() => DashboardController());
  }
}

class ArticlesBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<ArticlesController>(() => ArticlesController());
  }
}

class HealthBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<HealthController>(() => HealthController());
  }
}

class AppointmentsBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<AppointmentsController>(() => AppointmentsController());
  }
}

class ProfileBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<ProfileController>(() => ProfileController());
  }
}

class SettingsBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<SettingsController>(() => SettingsController());
  }
}
