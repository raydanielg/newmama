extension StringExtensions on String {
  // Validation
  bool get isValidEmail {
    final emailRegExp = RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$');
    return emailRegExp.hasMatch(this);
  }
  
  bool get isValidPhone {
    // Support both Tanzanian and international formats
    final phoneRegExp = RegExp(r'^(\+255|0)[0-9]{9}$');
    return phoneRegExp.hasMatch(this);
  }
  
  bool get isValidPassword {
    return length >= 6;
  }
  
  bool get isNotNullOrEmpty {
    return trim().isNotEmpty;
  }
  
  // Formatting
  String get capitalize {
    if (isEmpty) return this;
    return '${this[0].toUpperCase()}${substring(1)}';
  }
  
  String get toTitleCase {
    if (isEmpty) return this;
    return split(' ').map((word) => word.capitalize).join(' ');
  }
  
  String truncate(int maxLength, {String suffix = '...'}) {
    if (length <= maxLength) return this;
    return '${substring(0, maxLength)}$suffix';
  }
  
  // Phone formatting
  String get formatPhone {
    if (startsWith('+255')) {
      return this;
    } else if (startsWith('0')) {
      return '+255${substring(1)}';
    }
    return this;
  }
  
  // URL formatting
  String get ensureHttps {
    if (startsWith('http://') || startsWith('https://')) {
      return this;
    }
    return 'https://$this';
  }
  
  // Date parsing helpers
  DateTime? get toDateTime {
    try {
      return DateTime.parse(this);
    } catch (e) {
      return null;
    }
  }
  
  // Slug creation
  String get toSlug {
    return toLowerCase()
        .replaceAll(RegExp(r'[^\w\s-]'), '')
        .replaceAll(RegExp(r'\s+'), '-')
        .replaceAll(RegExp(r'-+'), '-');
  }
}
