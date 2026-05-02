import 'package:flutter/material.dart';

class AppColors {
  // Primary Colors
  static const Color primary = Color(0xFF1E40AF);
  static const Color primaryLight = Color(0xFF3B82F6);
  static const Color primaryDark = Color(0xFF1E3A8A);
  static const Color primary50 = Color(0xFFEEF2FF);
  static const Color primary100 = Color(0xFFE0E7FF);
  static const Color primary200 = Color(0xFFC7D2FE);
  
  // Secondary Colors
  static const Color secondary = Color(0xFFD63384);
  static const Color secondaryLight = Color(0xFFEC4899);
  static const Color secondaryDark = Color(0xFFBE185D);
  static const Color secondary50 = Color(0xFFFDF2F8);
  static const Color secondary100 = Color(0xFFFCE7F3);
  
  // Background Colors
  static const Color background = Color(0xFFFDFBF7);
  static const Color surface = Color(0xFFFFFFFF);
  static const Color surfaceVariant = Color(0xFFF8FAFC);
  
  // Text Colors
  static const Color textPrimary = Color(0xFF1E293B);
  static const Color textSecondary = Color(0xFF64748B);
  static const Color textTertiary = Color(0xFF94A3B8);
  static const Color textOnPrimary = Color(0xFFFFFFFF);
  
  // Semantic Colors
  static const Color success = Color(0xFF16A34A);
  static const Color success50 = Color(0xFFF0FDF4);
  static const Color warning = Color(0xFFF59E0B);
  static const Color warning50 = Color(0xFFFEF3C7);
  static const Color error = Color(0xFFDC2626);
  static const Color error50 = Color(0xFFFEF2F2);
  static const Color info = Color(0xFF0EA5E9);
  static const Color info50 = Color(0xFFE0F2FE);
  
  // Border Colors
  static const Color border = Color(0xFFE2E8F0);
  static const Color borderLight = Color(0xFFF1F5F9);
  static const Color divider = Color(0xFFE2E8F0);
  
  // Shadow
  static Color shadowLight = const Color(0xFF1E40AF).withOpacity(0.05);
  static Color shadowMedium = const Color(0xFF1E40AF).withOpacity(0.1);
  static Color shadowDark = const Color(0xFF1E40AF).withOpacity(0.15);
  
  // Gradients
  static const LinearGradient primaryGradient = LinearGradient(
    colors: [primary, primaryLight],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );
  
  static const LinearGradient secondaryGradient = LinearGradient(
    colors: [secondary, secondaryLight],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );
  
  static const LinearGradient backgroundGradient = LinearGradient(
    colors: [background, surface],
    begin: Alignment.topCenter,
    end: Alignment.bottomCenter,
  );
}
