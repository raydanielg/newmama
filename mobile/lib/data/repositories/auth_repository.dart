import '../../models/user_model.dart';
import '../../services/api_service.dart';
import '../../services/storage_service.dart';

class AuthRepository {
  final ApiService _apiService;
  final StorageService _storageService;

  AuthRepository(this._apiService, this._storageService);

  Future<User?> login(String phone, String password) async {
    try {
      final response = await _apiService.post('/mother/login', {
        'login': phone,
        'password': password,
      });

      if (response['success'] == true && response['data'] != null) {
        final user = User.fromJson(response['data']['user']);
        final token = response['data']['token'];
        
        await _storageService.setToken(token);
        await _storageService.setUser(user);
        
        return user;
      }
      return null;
    } catch (e) {
      throw Exception('Login failed: $e');
    }
  }

  Future<User?> register(String name, String phone, String password) async {
    try {
      final response = await _apiService.post('/mother/register', {
        'name': name,
        'login': phone,
        'password': password,
      });

      if (response['success'] == true && response['data'] != null) {
        final user = User.fromJson(response['data']['user']);
        final token = response['data']['token'];
        
        await _storageService.setToken(token);
        await _storageService.setUser(user);
        
        return user;
      }
      return null;
    } catch (e) {
      throw Exception('Registration failed: $e');
    }
  }

  Future<bool> forgotPassword(String phone) async {
    try {
      final response = await _apiService.post('/mother/forgot-password', {
        'login': phone,
      });

      return response['success'] == true;
    } catch (e) {
      throw Exception('Failed to send reset link: $e');
    }
  }

  Future<void> logout() async {
    await _storageService.clearAll();
  }

  Future<bool> isLoggedIn() async {
    return await _storageService.hasToken();
  }

  Future<User?> getCurrentUser() async {
    return await _storageService.getUser();
  }

  Future<void> updateProfile(Map<String, dynamic> data) async {
    try {
      final response = await _apiService.put('/mother/profile', data);
      
      if (response['success'] == true && response['data'] != null) {
        final user = User.fromJson(response['data']);
        await _storageService.setUser(user);
      }
    } catch (e) {
      throw Exception('Failed to update profile: $e');
    }
  }

  Future<void> changePassword(String currentPassword, String newPassword) async {
    try {
      final response = await _apiService.post('/mother/change-password', {
        'current_password': currentPassword,
        'new_password': newPassword,
      });

      if (response['success'] != true) {
        throw Exception(response['message'] ?? 'Failed to change password');
      }
    } catch (e) {
      throw Exception('Failed to change password: $e');
    }
  }
}
