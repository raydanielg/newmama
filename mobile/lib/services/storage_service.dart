import 'dart:convert';
import 'package:get_storage/get_storage.dart';
import '../core/constants/app_constants.dart';
import '../models/user_model.dart';

class StorageService {
  final GetStorage _storage = GetStorage();

  // Token Management
  Future<void> setToken(String token) async {
    await _storage.write(AppConstants.tokenKey, token);
  }

  String? getToken() {
    return _storage.read<String>(AppConstants.tokenKey);
  }

  Future<void> removeToken() async {
    await _storage.remove(AppConstants.tokenKey);
  }

  bool hasToken() {
    return _storage.hasData(AppConstants.tokenKey);
  }

  // User Management
  Future<void> setUser(User user) async {
    await _storage.write(AppConstants.userKey, jsonEncode(user.toJson()));
  }

  User? getUser() {
    final userData = _storage.read<String>(AppConstants.userKey);
    if (userData != null) {
      try {
        return User.fromJson(jsonDecode(userData));
      } catch (e) {
        return null;
      }
    }
    return null;
  }

  Future<void> removeUser() async {
    await _storage.remove(AppConstants.userKey);
  }

  // Onboarding
  Future<void> setOnboardingCompleted(bool completed) async {
    await _storage.write(AppConstants.onboardingKey, completed);
  }

  bool isOnboardingCompleted() {
    return _storage.read<bool>(AppConstants.onboardingKey) ?? false;
  }

  // Settings
  Future<void> setSettings(Map<String, dynamic> settings) async {
    await _storage.write(AppConstants.settingsKey, jsonEncode(settings));
  }

  Map<String, dynamic>? getSettings() {
    final settingsData = _storage.read<String>(AppConstants.settingsKey);
    if (settingsData != null) {
      try {
        return jsonDecode(settingsData);
      } catch (e) {
        return null;
      }
    }
    return null;
  }

  // Cache Management
  Future<void> setCache(String key, dynamic value, {Duration? expiry}) async {
    final cacheData = {
      'data': value,
      'timestamp': DateTime.now().millisecondsSinceEpoch,
      'expiry': expiry?.inMilliseconds,
    };
    await _storage.write('${AppConstants.cacheKey}_$key', jsonEncode(cacheData));
  }

  dynamic getCache(String key) {
    final cacheData = _storage.read<String>('${AppConstants.cacheKey}_$key');
    if (cacheData != null) {
      try {
        final decoded = jsonDecode(cacheData);
        final timestamp = decoded['timestamp'] as int;
        final expiry = decoded['expiry'] as int?;
        
        if (expiry != null) {
          final expiryTime = timestamp + expiry;
          if (DateTime.now().millisecondsSinceEpoch > expiryTime) {
            removeCache(key);
            return null;
          }
        }
        
        return decoded['data'];
      } catch (e) {
        return null;
      }
    }
    return null;
  }

  Future<void> removeCache(String key) async {
    await _storage.remove('${AppConstants.cacheKey}_$key');
  }

  Future<void> clearAllCache() async {
    final keys = _storage.getKeys();
    for (final key in keys) {
      if (key.startsWith(AppConstants.cacheKey)) {
        await _storage.remove(key);
      }
    }
  }

  // General Storage
  Future<void> write(String key, dynamic value) async {
    await _storage.write(key, value);
  }

  T? read<T>(String key) {
    return _storage.read<T>(key);
  }

  Future<void> remove(String key) async {
    await _storage.remove(key);
  }

  Future<void> clearAll() async {
    await _storage.erase();
  }

  // Check if key exists
  bool hasData(String key) {
    return _storage.hasData(key);
  }

  // Get all keys
  List<String> getKeys() {
    return _storage.getKeys().toList();
  }
}
