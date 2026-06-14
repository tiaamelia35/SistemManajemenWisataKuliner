import 'package:flutter/foundation.dart';
import '../models/booking.dart';
import '../services/api_service.dart';

class BookingProvider extends ChangeNotifier {
  List<Booking> bookings = [];
  bool loading = false;
  String? error;

  Future<void> createBooking(Map<String, dynamic> payload) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      final res = await ApiService.createBooking(payload);
      // optionally process res to Booking model
      // after creating booking, refresh user's bookings
      await fetchUserBookings();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<void> fetchUserBookings() async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      final list = await ApiService.fetchUserBookings();
      bookings = list.map((j) => Booking.fromJson(j)).toList();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}
