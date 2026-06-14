class MenuItem {
  final int id;
  final String name;
  final String? imageUrl;
  final double? price;
  final String? description;

  MenuItem({required this.id, required this.name, this.imageUrl, this.price, this.description});

  factory MenuItem.fromJson(Map<String, dynamic> j) {
    return MenuItem(
      id: j['id'] is int ? j['id'] : int.parse('${j['id']}'),
      name: j['name'] ?? '',
      imageUrl: j['image_url'] ?? j['image'] ?? null,
      price: j['price'] != null ? double.tryParse('${j['price']}') : null,
      description: j['description'] ?? null,
    );
  }
}
