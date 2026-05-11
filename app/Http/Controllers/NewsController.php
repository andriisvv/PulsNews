<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Головна сторінка — featured + сітка новин
     */
    public function index(Request $request)
    {
        $category = $request->get('category');
        $search   = $request->get('search');

        // Запит для featured (головна історія)
        $featured = News::featured()->first();

        // Запит для основної сітки
        $query = News::published();

        // Виключаємо featured зі списку (щоб не дублювалось)
        if ($featured) {
            $query->where('id', '!=', $featured->id);
        }

        if ($category && $category !== 'all') {
            $query->byCategory($category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $news = $query->paginate(6);

        // Унікальні категорії для навігації
       $categories = News::where('is_published', true)
    ->select('category')
    ->distinct()
    ->orderBy('category')
    ->pluck('category');
        return view('news.index', compact('news', 'featured', 'categories', 'category', 'search'));
    }

    /**
     * Сторінка окремої новини
     */
    public function show(string $slug)
    {
        $article = News::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $related = News::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->limit(3)
            ->get();

        return view('news.show', compact('article', 'related'));
    }
}