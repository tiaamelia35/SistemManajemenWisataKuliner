class Booking {
  final int id;
  final int restaurantId;
  final int userId;
  final String date;
  final String status;

  Booking({
    required this.id,
    required this.restaurantId,
    required this.userId,
    required this.date,
    required this.status,
  });

  factory Booking.fromJson(Map<String, dynamic> j) {
    return Booking(
      id: j['id'] is int ? j['id'] : int.parse('${j['id']}'),
      restaurantId: j['restaurant_id'] is int ? j['restaurant_id'] : int.parse('${j['restaurant_id']}'),
      userId: j['user_id'] is int ? j['user_id'] : int.parse('${j['user_id']}'),
      date: j['date'] ?? '',
      status: j['status'] ?? 'pending',
    );
  }
}
