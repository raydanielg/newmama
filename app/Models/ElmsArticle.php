<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElmsArticle extends Model
{
    protected $table = 'elms_articles';

    protected $fillable = [
        'category_id',
        'course_id',
        'author_id',
        'title',
        'slug',
        'level',
        'thumbnail',
        'excerpt',
        'content',
        'published_at',
        'is_featured',
        'is_published',
        'views_count',
    ];

    protected $casts = [
        'published_at' => 'date',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'views_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ElmsArticleCategory::class, 'category_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(ElmsCourse::class, 'course_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
