class Category {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final String? icon;
  final int articlesCount;
  final DateTime? createdAt;

  Category({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    this.icon,
    this.articlesCount = 0,
    this.createdAt,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      description: json['description'],
      icon: json['icon'],
      articlesCount: json['articles_count'] ?? 0,
      createdAt: json['created_at'] != null 
          ? DateTime.parse(json['created_at']) 
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'slug': slug,
      'description': description,
      'icon': icon,
      'articles_count': articlesCount,
      'created_at': createdAt?.toIso8601String(),
    };
  }
}
