class Article {
  final int id;
  final String title;
  final String slug;
  final String? excerpt;
  final String? content;
  final String? imageUrl;
  final String category;
  final int? categoryId;
  final String? ageGroup;
  final DateTime? publishedAt;
  final int readTime;

  Article({
    required this.id,
    required this.title,
    required this.slug,
    this.excerpt,
    this.content,
    this.imageUrl,
    required this.category,
    this.categoryId,
    this.ageGroup,
    this.publishedAt,
    this.readTime = 5,
  });

  factory Article.fromJson(Map<String, dynamic> json) {
    return Article(
      id: json['id'],
      title: json['title'],
      slug: json['slug'],
      excerpt: json['excerpt'],
      content: json['content'],
      imageUrl: json['image_url'] ?? json['image'],
      category: json['category'] ?? 'General',
      categoryId: json['category_id'],
      ageGroup: json['age_group'],
      publishedAt: json['published_at'] != null 
          ? DateTime.parse(json['published_at']) 
          : null,
      readTime: json['read_time'] ?? 5,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'slug': slug,
      'excerpt': excerpt,
      'content': content,
      'image_url': imageUrl,
      'category': category,
      'category_id': categoryId,
      'age_group': ageGroup,
      'published_at': publishedAt?.toIso8601String(),
      'read_time': readTime,
    };
  }
}
