import 'package:flutter/foundation.dart';
import '../services/api_service.dart';

class AdminProvider extends ChangeNotifier {
  Map<String, dynamic>? summary;
  List<Map<String, dynamic>> users = [];
  Map<String, dynamic>? reports;
  bool loading = false;
  String? error;

  Future<void> loadSummary() async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      summary = await ApiService.fetchAdminSummary();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<void> loadUsers() async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      users = await ApiService.fetchAdminUsers();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<bool> updateUser(int id, Map<String, dynamic> payload) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await ApiService.updateAdminUser(id, payload);
      await loadUsers();
      return true;
    } catch (e) {
      error = e.toString();
      return false;
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<bool> deleteUser(int id) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await ApiService.deleteAdminUser(id);
      await loadUsers();
      return true;
    } catch (e) {
      error = e.toString();
      return false;
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<void> loadReports() async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      reports = await ApiService.fetchAdminReports();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}
