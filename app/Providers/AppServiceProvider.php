<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.admin');
        Paginator::defaultSimpleView('vendor.pagination.admin');

        $siteContact = null;
        $testimonials = [];
        $featuredArticles = [];
        $articleCategories = [];

        try {
            if (Schema::hasTable('site_contacts')) {
                $siteContact = DB::table('site_contacts')->orderBy('id')->first();
            }

            if (Schema::hasTable('testimonials')) {
                $testimonials = DB::table('testimonials')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();
            }

            if (Schema::hasTable('article_categories')) {
                $articleCategories = DB::table('article_categories')
                    ->select('article_categories.*', DB::raw('(SELECT COUNT(*) FROM articles WHERE articles.category_id = article_categories.id) as articles_count'))
                    ->get();
            }

            if (Schema::hasTable('articles')) {
                $featuredArticles = DB::table('articles')
                    ->where('is_featured', true)
                    ->orderBy('published_at', 'desc')
                    ->limit(4)
                    ->get();
                
                $relatedArticles = DB::table('articles')
                    ->orderBy('published_at', 'desc')
                    ->limit(4)
                    ->get();
            }
        } catch (\Throwable $e) {
            $siteContact = null;
            $testimonials = [];
            $featuredArticles = [];
            $articleCategories = [];
            $relatedArticles = [];
        }

        View::share('siteContact', $siteContact);
        View::share('testimonials', $testimonials);
        View::share('featuredArticles', $featuredArticles);
        View::share('articleCategories', $articleCategories);
        View::share('relatedArticles', $relatedArticles ?? []);
    }
}
