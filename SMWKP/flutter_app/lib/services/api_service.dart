import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/restaurant.dart';
import 'auth_service.dart';

class ApiService {
  // TODO: set to your Laravel base URL (HTTPS recommended)
  static const String baseUrl = 'https://your-laravel-site.test';

  static Future<List<Restaurant>> fetchRestaurants() async {
    final uri = Uri.parse('\$baseUrl/api/restaurants');
    final headers = await _defaultHeaders();
    final res = await http.get(uri, headers: headers);
    if (res.statusCode == 200) {
      final body = json.decode(res.body);
      // Expecting { data: [...] } or an array
      final list = (body is Map && body['data'] != null) ? body['data'] : body;
      if (list is List) {
        return list.map((e) => Restaurant.fromJson(e)).toList();
      }
    }
    throw Exception('Failed to load restaurants: \\${res.statusCode}');
  }

  static Future<Map<String, String>> _defaultHeaders() async {
    final token = await AuthService.getToken();
    final headers = {'Content-Type': 'application/json'};
    if (token != null) headers['Authorization'] = 'Bearer $token';
    return headers;
  }

  static Future<Restaurant> fetchRestaurantDetail(int id) async {
    final uri = Uri.parse('\$baseUrl/api/restaurants/\$id');
    final res = await http.get(uri);
    if (res.statusCode == 200) {
      final body = json.decode(res.body);
      final data = (body is Map && body['data'] != null) ? body['data'] : body;
      if (data is Map) return Restaurant.fromJson(data);
    }
    throw Exception('Failed to load restaurant detail: \\${res.statusCode}');
  }

  static Future<Map<String, dynamic>> createBooking(Map<String, dynamic> payload) async {
    final uri = Uri.parse('\$baseUrl/api/bookings');
    final headers = await _defaultHeaders();
    final res = await http.post(uri, body: json.encode(payload), headers: headers);
    final body = json.decode(res.body);
    if (res.statusCode == 201 || res.statusCode == 200) return body;
    throw Exception('Booking failed: \\${res.statusCode} - \\${body}');
  }

  static Future<List<Map<String, dynamic>>> fetchUserBookings() async {
    final uri = Uri.parse('\$baseUrl/api/user/bookings');
    final headers = await _defaultHeaders();
    final res = await http.get(uri, headers: headers);
    if (res.statusCode == 200) {
      final body = json.decode(res.body);
      final list = (body is Map && body['data'] != null) ? body['data'] : body;
      if (list is List) return List<Map<String, dynamic>>.from(list);
    }
    throw Exception('Failed to load user bookings: \\${res.statusCode}');
  }

  static Future<Map<String, dynamic>> fetchUserProfile() async {
    final uri = Uri.parse('\$baseUrl/api/user/profile');
    final headers = await _defaultHeaders();
    final res = await http.get(uri, headers: headers);
    if (res.statusCode == 200) {
      final body = json.decode(res.body);
      final data = (body is Map && body['data'] != null) ? body['data'] : body;
      if (data is Map) return Map<String, dynamic>.from(data);
    }
    throw Exception('Failed to fetch profile: \\${res.statusCode}');
  }

  static Future<Map<String, dynamic>> updateUserProfile(Map<String, dynamic> payload) async {
    final uri = Uri.parse('\$baseUrl/api/user/profile');
    final headers = await _defaultHeaders();
    final res = await http.put(uri, headers: headers, body: json.encode(payload));
    final body = json.decode(res.body);
    if (res.statusCode == 200) return body;
    throw Exception('Failed to update profile: \\${res.statusCode} - \\${body}');
  }

  static Future<Map<String, dynamic>> uploadAvatar(String filePath) async {
    final uri = Uri.parse('\$baseUrl/api/user/avatar');
    final headers = await _defaultHeaders();
    final request = http.MultipartRequest('POST', uri);
    request.headers.addAll(headers);
    request.files.add(await http.MultipartFile.fromPath('avatar', filePath));
    final streamed = await request.send();
    final res = await http.Response.fromStream(streamed);
    final body = json.decode(res.body);
    if (res.statusCode == 200 || res.statusCode == 201) return body;
    throw Exception('Avatar upload failed: \\${res.statusCode} - \\${body}');
  }

  // Owner menu CRUD
  static Future<List<Map<String, dynamic>>> fetchOwnerMenus() async {
    final uri = Uri.parse('\$baseUrl/api/owner/menus');
    final headers = await _defaultHeaders();
    final res = await http.get(uri, headers: headers);
    if (res.statusCode == 200) {
      final body = json.decode(res.body);
      final list = (body is Map && body['data'] != null) ? body['data'] : body;
      if (list is List) return List<Map<String, dynamic>>.from(list);
    }
    throw Exception('Failed to load owner menus: \\${res.statusCode}');
  }

  static Future<Map<String, dynamic>> createOwnerMenu(Map<String, dynamic> payload, {String? imagePath}) async {
    final uri = Uri.parse('\$baseUrl/api/owner/menus');
    final headers = await _defaultHeaders();
    if (imagePath != null) {
      final request = http.MultipartRequest('POST', uri);
      request.headers.addAll(headers);
      request.fields.addAll(payload.map((k, v) => MapEntry(k, v.toString())));
      request.files.add(await http.MultipartFile.fromPath('image', imagePath));
      final streamed = await request.send();
      final res = await http.Response.fromStream(streamed);
      final body = json.decode(res.body);
      if (res.statusCode == 200 || res.statusCode == 201) return body;
      throw Exception('Create menu failed: \\${res.statusCode} - \\${body}');
    }
    final res = await http.post(uri, headers: headers, body: json.encode(payload));
    final body = json.decode(res.body);
    if (res.statusCode == 200 || res.statusCode == 201) return body;
    throw Exception('Create menu failed: \\${res.statusCode} - \\${body}');
  }

  static Future<Map<String, dynamic>> updateOwnerMenu(int id, Map<String, dynamic> payload, {String? imagePath}) async {
    final uri = Uri.parse('\$baseUrl/api/owner/menus/\$id');
    final headers = await _defaultHeaders();
    if (imagePath != null) {
      final request = http.MultipartRequest('POST', uri); // some APIs accept POST with _method=PUT
      request.headers.addAll(headers);
      request.fields.addAll({'_method': 'PUT'});
      request.fields.addAll(payload.map((k, v) => MapEntry(k, v.toString())));
      request.files.add(await http.MultipartFile.fromPath('image', imagePath));
      final streamed = await request.send();
      final res = await http.Response.fromStream(streamed);
      final body = json.decode(res.body);
      if (res.statusCode == 200) return body;
      throw Exception('Update menu failed: \\${res.statusCode} - \\${body}');
    }
    final res = await http.put(uri, headers: headers, body: json.encode(payload));
    final body = json.decode(res.body);
    if (res.statusCode == 200) return body;
    throw Exception('Update menu failed: \\${res.statusCode} - \\${body}');
  }

  static Future<void> deleteOwnerMenu(int id) async {
    final uri = Uri.parse('\$baseUrl/api/owner/menus/\$id');
    final headers = await _defaultHeaders();
    final res = await http.delete(uri, headers: headers);
    if (res.statusCode == 200 || res.statusCode == 204) return;
    throw Exception('Delete menu failed: \\${res.statusCode}');
  }

  // Admin APIs
  static Future<Map<String, dynamic>> fetchAdminSummary() async {
    final uri = Uri.parse('\$baseUrl/api/admin/summary');
    final headers = await _defaultHeaders();
    final res = await http.get(uri, headers: headers);
    if (res.statusCode == 200) {
      return json.decode(res.body) as Map<String, dynamic>;
    }
    throw Exception('Failed to load admin summary: \\${res.statusCode}');
  }

  static Future<List<Map<String, dynamic>>> fetchAdminUsers({int page = 1}) async {
    final uri = Uri.parse('\$baseUrl/api/admin/users?page=\$page');
    final headers = await _defaultHeaders();
    final res = await http.get(uri, headers: headers);
    if (res.statusCode == 200) {
      final body = json.decode(res.body);
      final list = (body is Map && body['data'] != null) ? body['data'] : body;
      if (list is List) return List<Map<String, dynamic>>.from(list);
    }
    throw Exception('Failed to load admin users: \\${res.statusCode}');
  }

  static Future<Map<String, dynamic>> updateAdminUser(int id, Map<String, dynamic> payload) async {
    final uri = Uri.parse('\$baseUrl/api/admin/users/\$id');
    final headers = await _defaultHeaders();
    final res = await http.put(uri, headers: headers, body: json.encode(payload));
    final body = json.decode(res.body);
    if (res.statusCode == 200) return body;
    throw Exception('Failed to update user: \\${res.statusCode} - \\${body}');
  }

  static Future<void> deleteAdminUser(int id) async {
    final uri = Uri.parse('\$baseUrl/api/admin/users/\$id');
    final headers = await _defaultHeaders();
    final res = await http.delete(uri, headers: headers);
    if (res.statusCode == 200 || res.statusCode == 204) return;
    throw Exception('Failed to delete user: \\${res.statusCode}');
  }

  static Future<Map<String, dynamic>> fetchAdminReports({Map<String, String>? params}) async {
    final q = params != null ? Uri(queryParameters: params).query : '';
    final uri = Uri.parse('\$baseUrl/api/admin/reports' + (q.isNotEmpty ? '?\$q' : ''));
    final headers = await _defaultHeaders();
    final res = await http.get(uri, headers: headers);
    if (res.statusCode == 200) return json.decode(res.body) as Map<String, dynamic>;
    throw Exception('Failed to load reports: \\${res.statusCode}');
  }
}
