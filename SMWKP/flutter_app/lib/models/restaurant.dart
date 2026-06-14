class Restaurant {
  final int id;
  final String name;
  final String? imageUrl;
  final double rating;
  final String? description;

  Restaurant({
    required this.id,
    required this.name,
    this.imageUrl,
    this.rating = 0.0,
    this.description,
  });

  factory Restaurant.fromJson(Map<String, dynamic> j) {
    return Restaurant(
      id: j['id'] is int ? j['id'] : int.parse('${j['id']}'),
      name: j['name'] ?? '',
      imageUrl: j['image_url'] ?? j['image'] ?? null,
      rating: (j['rating'] != null) ? double.tryParse('${j['rating']}') ?? 0.0 : 0.0,
      description: j['description'] ?? null,
    );
  }
}
