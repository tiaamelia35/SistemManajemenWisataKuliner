import 'package:flutter/foundation.dart';
import '../models/restaurant.dart';
import '../services/api_service.dart';

class RestaurantProvider extends ChangeNotifier {
  List<Restaurant> restaurants = [];
  bool loading = false;
  String? error;

  Future<void> fetchRestaurants() async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      restaurants = await ApiService.fetchRestaurants();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}
