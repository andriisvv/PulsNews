<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image_url',
        'category',
        'source',
        'external_url',
        'author',
        'is_published',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Автоматично генерує slug і встановлює дату публікації
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title) . '-' . uniqid();
            }
            if (empty($model->published_at)) {
                $model->published_at = now();
            }
        });
    }

    /**
     * Тільки опубліковані новини, сортовані за датою
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->orderBy('published_at', 'desc');
    }

    /**
     * Тільки featured новини
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                     ->where('is_published', true);
    }

    /**
     * Фільтр за категорією
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}