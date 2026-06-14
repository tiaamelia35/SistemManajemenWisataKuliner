import 'package:flutter/foundation.dart';
import '../services/auth_service.dart';

class AuthProvider extends ChangeNotifier {
  String? token;
  bool loading = false;
  String? error;

  AuthProvider() {
    _loadToken();
  }

  Future<void> _loadToken() async {
    token = await AuthService.getToken();
    notifyListeners();
  }

  bool get isAuthenticated => token != null;

  Future<bool> login(String email, String password) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      final t = await AuthService.login(email, password);
      token = t;
      loading = false;
      notifyListeners();
      return true;
    } catch (e) {
      error = e.toString();
      loading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    await AuthService.deleteToken();
    token = null;
    notifyListeners();
  }
}
