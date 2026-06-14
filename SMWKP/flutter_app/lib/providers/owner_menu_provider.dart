import 'package:flutter/foundation.dart';
import '../models/menu.dart';
import '../services/api_service.dart';

class OwnerMenuProvider extends ChangeNotifier {
  List<MenuItem> menus = [];
  bool loading = false;
  String? error;

  Future<void> fetchMenus() async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      final list = await ApiService.fetchOwnerMenus();
      menus = list.map((j) => MenuItem.fromJson(j)).toList();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<bool> createMenu(Map<String, dynamic> payload, {String? imagePath}) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      final res = await ApiService.createOwnerMenu(payload, imagePath: imagePath);
      await fetchMenus();
      return true;
    } catch (e) {
      error = e.toString();
      return false;
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<bool> updateMenu(int id, Map<String, dynamic> payload, {String? imagePath}) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      final res = await ApiService.updateOwnerMenu(id, payload, imagePath: imagePath);
      await fetchMenus();
      return true;
    } catch (e) {
      error = e.toString();
      return false;
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<bool> deleteMenu(int id) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await ApiService.deleteOwnerMenu(id);
      await fetchMenus();
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
