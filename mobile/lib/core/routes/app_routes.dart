import 'package:get/get.dart';

class AppRoutes {
  // Route Names
  static const String splash = '/';
  static const String welcome = '/welcome';
  static const String onboarding = '/onboarding';
  static const String login = '/login';
  static const String register = '/register';
  static const String forgotPassword = '/forgot-password';
  static const String dashboard = '/dashboard';
  static const String home = '/home';
  static const String articles = '/articles';
  static const String articleDetail = '/article/:id';
  static const String categories = '/categories';
  static const String categoryDetail = '/category/:id';
  static const String health = '/health';
  static const String healthData = '/health-data';
  static const String appointments = '/appointments';
  static const String bookAppointment = '/book-appointment';
  static const String weightLog = '/weight-log';
  static const String bloodPressure = '/blood-pressure';
  static const String kickCount = '/kick-count';
  static const String symptoms = '/symptoms';
  static const String medication = '/medication';
  static const String chat = '/chat';
  static const String whatsapp = '/whatsapp';
  static const String emergency = '/emergency';
  static const String profile = '/profile';
  static const String editProfile = '/edit-profile';
  static const String settings = '/settings';
  static const String notifications = '/notifications';
  static const String helpSupport = '/help-support';
  static const String about = '/about';
  static const String privacy = '/privacy';
  static const String terms = '/terms';
  static const String faq = '/faq';
  
  // Get route paths with parameters
  static String articleDetailPath(String id) => '/article/$id';
  static String categoryDetailPath(String id) => '/category/$id';
}
