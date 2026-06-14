class UserModel {
  final int id;
  final String name;
  final String email;
  final String? avatarUrl;

  UserModel({required this.id, required this.name, required this.email, this.avatarUrl});

  factory UserModel.fromJson(Map<String, dynamic> j) {
    return UserModel(
      id: j['id'] is int ? j['id'] : int.parse('${j['id']}'),
      name: j['name'] ?? '',
      email: j['email'] ?? '',
      avatarUrl: j['avatar_url'] ?? j['avatar'] ?? null,
    );
  }
}
