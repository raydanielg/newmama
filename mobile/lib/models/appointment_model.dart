class Appointment {
  final int? id;
  final int userId;
  final String title;
  final String? doctorName;
  final String? hospitalName;
  final String? type;
  final DateTime scheduledAt;
  final int? durationMinutes;
  final String? notes;
  final String status; // scheduled, completed, cancelled, missed
  final DateTime? reminderAt;
  final DateTime? createdAt;

  Appointment({
    this.id,
    required this.userId,
    required this.title,
    this.doctorName,
    this.hospitalName,
    this.type,
    required this.scheduledAt,
    this.durationMinutes,
    this.notes,
    this.status = 'scheduled',
    this.reminderAt,
    this.createdAt,
  });

  factory Appointment.fromJson(Map<String, dynamic> json) {
    return Appointment(
      id: json['id'],
      userId: json['user_id'],
      title: json['title'],
      doctorName: json['doctor_name'],
      hospitalName: json['hospital_name'],
      type: json['type'],
      scheduledAt: DateTime.parse(json['scheduled_at']),
      durationMinutes: json['duration_minutes'],
      notes: json['notes'],
      status: json['status'] ?? 'scheduled',
      reminderAt: json['reminder_at'] != null 
          ? DateTime.parse(json['reminder_at']) 
          : null,
      createdAt: json['created_at'] != null 
          ? DateTime.parse(json['created_at']) 
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'title': title,
      'doctor_name': doctorName,
      'hospital_name': hospitalName,
      'type': type,
      'scheduled_at': scheduledAt.toIso8601String(),
      'duration_minutes': durationMinutes,
      'notes': notes,
      'status': status,
      'reminder_at': reminderAt?.toIso8601String(),
      'created_at': createdAt?.toIso8601String(),
    };
  }

  // Helper getters
  bool get isUpcoming => scheduledAt.isAfter(DateTime.now()) && status == 'scheduled';
  bool get isToday {
    final now = DateTime.now();
    return scheduledAt.year == now.year && 
           scheduledAt.month == now.month && 
           scheduledAt.day == now.day;
  }
  bool get isCompleted => status == 'completed';
  bool get isCancelled => status == 'cancelled';
  bool get isMissed => status == 'missed';

  String get formattedTime {
    final hour = scheduledAt.hour.toString().padLeft(2, '0');
    final minute = scheduledAt.minute.toString().padLeft(2, '0');
    return '$hour:$minute';
  }

  String get formattedDate {
    final months = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];
    return '${scheduledAt.day} ${months[scheduledAt.month - 1]}';
  }

  String get statusDisplay {
    switch (status) {
      case 'scheduled':
        return 'Scheduled';
      case 'completed':
        return 'Completed';
      case 'cancelled':
        return 'Cancelled';
      case 'missed':
        return 'Missed';
      default:
        return status.capitalize();
    }
  }

  Color get statusColor {
    switch (status) {
      case 'scheduled':
        return const Color(0xFF1E40AF);
      case 'completed':
        return const Color(0xFF16A34A);
      case 'cancelled':
        return const Color(0xFF94A3B8);
      case 'missed':
        return const Color(0xFFDC2626);
      default:
        return const Color(0xFF1E40AF);
    }
  }
}

extension StringExtension on String {
  String capitalize() {
    if (isEmpty) return this;
    return '${this[0].toUpperCase()}${substring(1)}';
  }
}
