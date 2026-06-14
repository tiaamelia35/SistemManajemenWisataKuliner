import 'package:flutter/foundation.dart';
import '../models/user.dart';
import '../services/api_service.dart';

class ProfileProvider extends ChangeNotifier {
  UserModel? user;
  bool loading = false;
  String? error;

  Future<void> loadProfile() async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      final data = await ApiService.fetchUserProfile();
      user = UserModel.fromJson(data);
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<bool> updateProfile(Map<String, dynamic> payload) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      final res = await ApiService.updateUserProfile(payload);
      user = UserModel.fromJson(res['data'] ?? res);
      return true;
    } catch (e) {
      error = e.toString();
      return false;
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<bool> uploadAvatar(String path) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      final res = await ApiService.uploadAvatar(path);
      user = UserModel.fromJson(res['data'] ?? res);
      return true;
    } catch (e) {
      error = e.toString();
      return false;
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}
