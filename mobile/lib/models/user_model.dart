class User {
  final int id;
  final String name;
  final String phone;
  final String? email;
  final String? avatar;
  final String? pregnancyStatus;
  final int? pregnancyWeek;
  final DateTime? dueDate;
  final DateTime createdAt;

  User({
    required this.id,
    required this.name,
    required this.phone,
    this.email,
    this.avatar,
    this.pregnancyStatus,
    this.pregnancyWeek,
    this.dueDate,
    required this.createdAt,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      phone: json['phone'] ?? json['login'],
      email: json['email'],
      avatar: json['avatar'],
      pregnancyStatus: json['pregnancy_status'],
      pregnancyWeek: json['pregnancy_week'],
      dueDate: json['due_date'] != null 
          ? DateTime.parse(json['due_date']) 
          : null,
      createdAt: DateTime.parse(json['created_at']),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'phone': phone,
      'email': email,
      'avatar': avatar,
      'pregnancy_status': pregnancyStatus,
      'pregnancy_week': pregnancyWeek,
      'due_date': dueDate?.toIso8601String(),
      'created_at': createdAt.toIso8601String(),
    };
  }
}
