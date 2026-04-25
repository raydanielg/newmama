<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElmsArticle;
use App\Models\ElmsArticleCategory;
use App\Models\ElmsCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ElmsArticlesController extends Controller
{
    public function index(Request $request)
    {
        $filterPublished = $request->query('published', 'published');
        $filterPublished = in_array($filterPublished, ['all', 'published', 'draft'], true) ? $filterPublished : 'published';

        $query = ElmsArticle::query()->with(['category', 'course', 'author']);

        if ($filterPublished === 'published') {
            $query->where('is_published', true);
        }

        if ($filterPublished === 'draft') {
            $query->where('is_published', false);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($courseId = $request->query('course_id')) {
            $query->where('course_id', $courseId);
        }

        $articles = $query->orderByDesc('published_at')->orderByDesc('id')->paginate(20)->withQueryString();

        $categories = ElmsArticleCategory::query()->where('is_active', true)->orderBy('name')->get();
        $courses = ElmsCourse::query()->where('is_active', true)->orderBy('title')->get();

        return view('admin.elms.articles.index', [
            'title' => 'ELMS Articles',
            'articles' => $articles,
            'filterPublished' => $filterPublished,
            'categories' => $categories,
            'courses' => $courses,
        ]);
    }

    public function create()
    {
        return view('admin.elms.articles.form', [
            'title' => 'Add ELMS Article',
            'article' => null,
            'categories' => ElmsArticleCategory::query()->where('is_active', true)->orderBy('name')->get(),
            'courses' => ElmsCourse::query()->where('is_active', true)->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, null);
        $data['author_id'] = $request->user()?->id;
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?? $data['title'], null);

        $article = ElmsArticle::create($data);

        return redirect()->route('admin.elms.articles.edit', $article)->with('status', 'Article created');
    }

    public function edit(ElmsArticle $article)
    {
        return view('admin.elms.articles.form', [
            'title' => 'Edit ELMS Article',
            'article' => $article,
            'categories' => ElmsArticleCategory::query()->where('is_active', true)->orderBy('name')->get(),
            'courses' => ElmsCourse::query()->where('is_active', true)->orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, ElmsArticle $article)
    {
        $data = $this->validated($request, $article);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?? $data['title'], $article);

        $article->update($data);

        return back()->with('status', 'Article updated');
    }

    public function togglePublish(ElmsArticle $article)
    {
        $article->is_published = !$article->is_published;
        if ($article->is_published && !$article->published_at) {
            $article->published_at = now()->toDateString();
        }
        $article->save();

        return back()->with('status', $article->is_published ? 'Article published' : 'Article set to draft');
    }

    private function validated(Request $request, ?ElmsArticle $article): array
    {
        $unique = 'unique:elms_articles,slug';
        if ($article) {
            $unique .= ',' . $article->id;
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', $unique],
            'category_id' => ['nullable', 'integer', 'exists:elms_article_categories,id'],
            'course_id' => ['nullable', 'integer', 'exists:elms_courses,id'],
            'level' => ['nullable', 'string', 'max:50'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['is_published'] = (bool) ($data['is_published'] ?? false);

        return $data;
    }

    private function makeUniqueSlug(string $value, ?ElmsArticle $article): string
    {
        $base = Str::slug(trim($value));
        if ($base === '') {
            $base = 'article';
        }

        $slug = $base;
        $i = 2;

        while (true) {
            $exists = ElmsArticle::query()
                ->where('slug', $slug)
                ->when($article, fn ($q) => $q->where('id', '!=', $article->id))
                ->exists();

            if (!$exists) {
                return $slug;
            }

            $slug = $base . '-' . $i;
            $i++;
        }
    }
}
