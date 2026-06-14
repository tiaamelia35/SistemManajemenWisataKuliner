import 'dart:convert';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:http/http.dart' as http;

class AuthService {
  static const _storage = FlutterSecureStorage();
  static const _tokenKey = 'auth_token';
  static const String baseUrl = 'https://your-laravel-site.test';

  static Future<void> setToken(String token) async {
    await _storage.write(key: _tokenKey, value: token);
  }

  static Future<String?> getToken() async {
    return await _storage.read(key: _tokenKey);
  }

  static Future<void> deleteToken() async {
    await _storage.delete(key: _tokenKey);
  }

  /// Attempt login; expects JSON response containing a `token` field.
  static Future<String> login(String email, String password) async {
    final uri = Uri.parse('\$baseUrl/api/login');
    final res = await http.post(uri,
        headers: {'Content-Type': 'application/json'},
        body: json.encode({'email': email, 'password': password}));
    final body = json.decode(res.body);
    if (res.statusCode == 200 || res.statusCode == 201) {
      // try common response shapes
      String? token;
      if (body is Map) {
        token = body['token'] ?? body['access_token'] ?? body['data']?['token'];
      }
      if (token != null) {
        await setToken(token);
        return token;
      }
      throw Exception('Login succeeded but token missing in response');
    }
    throw Exception('Login failed: \\${res.statusCode} - \\${body}');
  }
}
