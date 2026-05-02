class AppConstants {
  // App Info
  static const String appName = 'Mamacare AI';
  static const String appTagline = 'Maternal Health Management';
  static const String appVersion = '1.0.0';
  
  // API
  static const String baseUrl = 'https://mamacareai.co.tz';
  static const String apiBaseUrl = 'https://mamacareai.co.tz/api';
  static const int apiTimeout = 30000; // milliseconds
  
  // Storage Keys
  static const String tokenKey = 'auth_token';
  static const String userKey = 'user_data';
  static const String onboardingKey = 'onboarding_completed';
  static const String settingsKey = 'app_settings';
  static const String cacheKey = 'app_cache';
  
  // Default Values
  static const int defaultPageSize = 20;
  static const int splashDuration = 3000; // milliseconds
  
  // Contact
  static const String supportEmail = 'support@mamacare.ai';
  static const String supportPhone = '+255 700 000 000';
  static const String whatsappNumber = '+255700000000';
  
  // Social Media
  static const String instagramUrl = 'https://instagram.com/mamacareai';
  static const String facebookUrl = 'https://facebook.com/mamacareai';
  static const String twitterUrl = 'https://twitter.com/mamacareai';
  
  // Pregnancy
  static const int fullTermWeeks = 40;
  static const int trimester1End = 12;
  static const int trimester2End = 27;
  
  // Animation Durations
  static const int animationFast = 200;
  static const int animationNormal = 300;
  static const int animationSlow = 500;
}
