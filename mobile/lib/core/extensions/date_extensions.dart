import 'package:intl/intl.dart';

extension DateExtensions on DateTime {
  // Formatters
  String get formatDate {
    return DateFormat('MMM dd, yyyy').format(this);
  }
  
  String get formatDateTime {
    return DateFormat('MMM dd, yyyy - HH:mm').format(this);
  }
  
  String get formatTime {
    return DateFormat('HH:mm').format(this);
  }
  
  String get formatDayMonth {
    return DateFormat('dd MMM').format(this);
  }
  
  String get formatMonthYear {
    return DateFormat('MMMM yyyy').format(this);
  }
  
  // Relative time
  String get timeAgo {
    final now = DateTime.now();
    final difference = now.difference(this);
    
    if (difference.inDays > 365) {
      return '${(difference.inDays / 365).floor()} years ago';
    } else if (difference.inDays > 30) {
      return '${(difference.inDays / 30).floor()} months ago';
    } else if (difference.inDays > 7) {
      return '${(difference.inDays / 7).floor()} weeks ago';
    } else if (difference.inDays > 0) {
      return '${difference.inDays} days ago';
    } else if (difference.inHours > 0) {
      return '${difference.inHours} hours ago';
    } else if (difference.inMinutes > 0) {
      return '${difference.inMinutes} minutes ago';
    } else {
      return 'Just now';
    }
  }
  
  // Pregnancy related
  int get weeksUntil {
    final now = DateTime.now();
    final difference = this.difference(now);
    return (difference.inDays / 7).ceil();
  }
  
  int get daysUntil {
    final now = DateTime.now();
    final difference = this.difference(now);
    return difference.inDays;
  }
  
  bool get isToday {
    final now = DateTime.now();
    return year == now.year && month == now.month && day == now.day;
  }
  
  bool get isTomorrow {
    final tomorrow = DateTime.now().add(const Duration(days: 1));
    return year == tomorrow.year && month == tomorrow.month && day == tomorrow.day;
  }
  
  bool get isThisWeek {
    final now = DateTime.now();
    final weekStart = now.subtract(Duration(days: now.weekday - 1));
    final weekEnd = weekStart.add(const Duration(days: 6));
    return isAfter(weekStart) && isBefore(weekEnd);
  }
  
  bool get isThisMonth {
    final now = DateTime.now();
    return year == now.year && month == now.month;
  }
  
  // Pregnancy week calculation
  int getPregnancyWeek({required DateTime lastMenstrualPeriod}) {
    final difference = this.difference(lastMenstrualPeriod);
    return (difference.inDays / 7).floor() + 1;
  }
  
  // Trimester
  String getTrimester(int week) {
    if (week <= 12) return 'First Trimester';
    if (week <= 27) return 'Second Trimester';
    return 'Third Trimester';
  }
  
  // Estimated Due Date (EDD) calculation
  static DateTime calculateEDD(DateTime lastMenstrualPeriod) {
    // Naegele's rule: LMP + 280 days (40 weeks)
    return lastMenstrualPeriod.add(const Duration(days: 280));
  }
}
