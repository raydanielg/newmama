import '../../models/article_model.dart';
import '../../models/category_model.dart';
import '../../services/api_service.dart';

class ArticlesRepository {
  final ApiService _apiService;

  ArticlesRepository(this._apiService);

  Future<List<Article>> getArticles({int page = 1, int? categoryId}) async {
    try {
      final queryParams = {
        'page': page.toString(),
        if (categoryId != null) 'category_id': categoryId.toString(),
      };

      final response = await _apiService.get('/articles', queryParams: queryParams);

      if (response['success'] == true && response['data'] != null) {
        final List<dynamic> articlesJson = response['data']['articles'] ?? [];
        return articlesJson.map((json) => Article.fromJson(json)).toList();
      }
      return [];
    } catch (e) {
      throw Exception('Failed to load articles: $e');
    }
  }

  Future<Article?> getArticle(String slug) async {
    try {
      final response = await _apiService.get('/articles/$slug');

      if (response['success'] == true && response['data'] != null) {
        return Article.fromJson(response['data']);
      }
      return null;
    } catch (e) {
      throw Exception('Failed to load article: $e');
    }
  }

  Future<List<Category>> getCategories() async {
    try {
      final response = await _apiService.get('/categories');

      if (response['success'] == true && response['data'] != null) {
        final List<dynamic> categoriesJson = response['data'];
        return categoriesJson.map((json) => Category.fromJson(json)).toList();
      }
      return [];
    } catch (e) {
      throw Exception('Failed to load categories: $e');
    }
  }

  Future<List<Article>> searchArticles(String query) async {
    try {
      final response = await _apiService.get('/articles/search', queryParams: {
        'q': query,
      });

      if (response['success'] == true && response['data'] != null) {
        final List<dynamic> articlesJson = response['data'];
        return articlesJson.map((json) => Article.fromJson(json)).toList();
      }
      return [];
    } catch (e) {
      throw Exception('Failed to search articles: $e');
    }
  }

  Future<List<Article>> getRelatedArticles(int articleId) async {
    try {
      final response = await _apiService.get('/articles/$articleId/related');

      if (response['success'] == true && response['data'] != null) {
        final List<dynamic> articlesJson = response['data'];
        return articlesJson.map((json) => Article.fromJson(json)).toList();
      }
      return [];
    } catch (e) {
      throw Exception('Failed to load related articles: $e');
    }
  }

  Future<void> bookmarkArticle(int articleId) async {
    try {
      await _apiService.post('/articles/$articleId/bookmark', {});
    } catch (e) {
      throw Exception('Failed to bookmark article: $e');
    }
  }

  Future<void> removeBookmark(int articleId) async {
    try {
      await _apiService.delete('/articles/$articleId/bookmark');
    } catch (e) {
      throw Exception('Failed to remove bookmark: $e');
    }
  }

  Future<List<Article>> getBookmarkedArticles() async {
    try {
      final response = await _apiService.get('/articles/bookmarks');

      if (response['success'] == true && response['data'] != null) {
        final List<dynamic> articlesJson = response['data'];
        return articlesJson.map((json) => Article.fromJson(json)).toList();
      }
      return [];
    } catch (e) {
      throw Exception('Failed to load bookmarks: $e');
    }
  }
}
