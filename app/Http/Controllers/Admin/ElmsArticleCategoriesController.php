<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElmsArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ElmsArticleCategoriesController extends Controller
{
    public function index(Request $request)
    {
        $filterActive = $request->query('active', 'active');
        $filterActive = in_array($filterActive, ['all', 'active', 'inactive'], true) ? $filterActive : 'active';

        $query = ElmsArticleCategory::query();

        if ($filterActive === 'active') {
            $query->where('is_active', true);
        }

        if ($filterActive === 'inactive') {
            $query->where('is_active', false);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.elms.categories.index', [
            'title' => 'ELMS Categories',
            'categories' => $categories,
            'filterActive' => $filterActive,
        ]);
    }

    public function create()
    {
        return view('admin.elms.categories.form', [
            'title' => 'Add Category',
            'category' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:elms_article_categories,slug'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
        ]);

        $data['slug'] = $this->makeSlug($data['slug'] ?? $data['name']);
        $data['is_active'] = true;

        ElmsArticleCategory::create($data);

        return redirect()->route('admin.elms.categories')->with('status', 'Category created');
    }

    public function edit(ElmsArticleCategory $category)
    {
        return view('admin.elms.categories.form', [
            'title' => 'Edit Category',
            'category' => $category,
        ]);
    }

    public function update(Request $request, ElmsArticleCategory $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:elms_article_categories,slug,' . $category->id],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $this->makeSlug($data['slug'] ?? $data['name']);
        $data['is_active'] = (bool) ($data['is_active'] ?? $category->is_active);

        $category->update($data);

        return redirect()->route('admin.elms.categories')->with('status', 'Category updated');
    }

    public function toggleStatus(ElmsArticleCategory $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();

        return back()->with('status', $category->is_active ? 'Category activated' : 'Category deactivated');
    }

    private function makeSlug(string $value): string
    {
        $value = trim($value);
        $slug = Str::slug($value);

        if ($slug === '') {
            $slug = 'category';
        }

        return $slug;
    }
}
