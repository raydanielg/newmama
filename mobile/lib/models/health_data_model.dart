class HealthData {
  final int? id;
  final int userId;
  final String type; // weight, blood_pressure, kick_count, symptom
  final double? value;
  final double? value2; // for blood pressure diastolic
  final String? unit;
  final String? notes;
  final DateTime recordedAt;
  final DateTime? createdAt;

  HealthData({
    this.id,
    required this.userId,
    required this.type,
    this.value,
    this.value2,
    this.unit,
    this.notes,
    required this.recordedAt,
    this.createdAt,
  });

  factory HealthData.fromJson(Map<String, dynamic> json) {
    return HealthData(
      id: json['id'],
      userId: json['user_id'],
      type: json['type'],
      value: json['value'] != null ? double.parse(json['value'].toString()) : null,
      value2: json['value2'] != null ? double.parse(json['value2'].toString()) : null,
      unit: json['unit'],
      notes: json['notes'],
      recordedAt: DateTime.parse(json['recorded_at']),
      createdAt: json['created_at'] != null 
          ? DateTime.parse(json['created_at']) 
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'type': type,
      'value': value,
      'value2': value2,
      'unit': unit,
      'notes': notes,
      'recorded_at': recordedAt.toIso8601String(),
      'created_at': createdAt?.toIso8601String(),
    };
  }

  // Factory methods for specific types
  static HealthData weight({
    required int userId,
    required double weight,
    String unit = 'kg',
    String? notes,
    DateTime? recordedAt,
  }) {
    return HealthData(
      userId: userId,
      type: 'weight',
      value: weight,
      unit: unit,
      notes: notes,
      recordedAt: recordedAt ?? DateTime.now(),
    );
  }

  static HealthData bloodPressure({
    required int userId,
    required int systolic,
    required int diastolic,
    String? notes,
    DateTime? recordedAt,
  }) {
    return HealthData(
      userId: userId,
      type: 'blood_pressure',
      value: systolic.toDouble(),
      value2: diastolic.toDouble(),
      unit: 'mmHg',
      notes: notes,
      recordedAt: recordedAt ?? DateTime.now(),
    );
  }

  static HealthData kickCount({
    required int userId,
    required int count,
    String? notes,
    DateTime? recordedAt,
  }) {
    return HealthData(
      userId: userId,
      type: 'kick_count',
      value: count.toDouble(),
      unit: 'kicks',
      notes: notes,
      recordedAt: recordedAt ?? DateTime.now(),
    );
  }

  static HealthData symptom({
    required int userId,
    required String symptom,
    String severity = 'mild',
    String? notes,
    DateTime? recordedAt,
  }) {
    return HealthData(
      userId: userId,
      type: 'symptom',
      value: severity == 'mild' ? 1 : severity == 'moderate' ? 2 : 3,
      unit: severity,
      notes: '$symptom${notes != null ? ' - $notes' : ''}',
      recordedAt: recordedAt ?? DateTime.now(),
    );
  }
}
