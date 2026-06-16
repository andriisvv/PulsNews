<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    /**
     * Дашборд — статистика
     */
    public function dashboard()
    {
        $stats = [
            'total'     => News::count(),
            'published' => News::where('is_published', true)->count(),
            'draft'     => News::where('is_published', false)->count(),
            'featured'  => News::where('is_featured', true)->count(),
            'manual'    => News::where('source', 'manual')->count(),
            'api'       => News::where('source', '!=', 'manual')->count(),
            'messages'  => ContactMessage::where('is_read', false)->count(),
        ];

        $latest = News::orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'latest'));
    }

    /**
     * Список усіх новин
     */
    public function index(Request $request)
    {
        $query = News::query();

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(15);

        $categories = News::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.news.index', compact('news', 'categories'));
    }

    /**
     * Форма створення новини
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Зберегти нову новину
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:500',
            'excerpt'      => 'nullable|string|max:1000',
            'content'      => 'required|string',
            'image_url'    => 'nullable|url|max:1000',
            'image_file'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'category'     => 'required|string|max:100',
            'author'       => 'nullable|string|max:200',
        ]);

        // Якщо завантажено файл — використовуємо його замість URL
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('news', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        }

        unset($validated['image_file']);

        $validated['source']       = 'manual';
        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['is_featured']  = $request->boolean('is_featured', false);

        News::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'Новину успішно створено!');
    }

    /**
     * Форма редагування
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Оновити новину
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:500',
            'excerpt'      => 'nullable|string|max:1000',
            'content'      => 'required|string',
            'image_url'    => 'nullable|url|max:1000',
            'image_file'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'category'     => 'required|string|max:100',
            'author'       => 'nullable|string|max:200',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('news', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        }

        unset($validated['image_file']);

        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured']  = $request->boolean('is_featured');

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'Новину оновлено!');
    }

    /**
     * Видалити новину
     */
    public function destroy(News $news)
    {
        $news->delete();
        return back()->with('success', 'Новину видалено.');
    }

    /**
     * Список повідомлень з форми зворотного звʼязку
     */
    public function messages()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Позначити повідомлення як прочитане
     */
    public function markMessageRead(ContactMessage $message)
    {
        $message->update(['is_read' => true]);

        return back()->with('success', 'Повідомлення позначено як прочитане.');
    }

    /**
     * Видалити повідомлення
     */
    public function destroyMessage(ContactMessage $message)
    {
        $message->delete();

        return back()->with('success', 'Повідомлення видалено.');
    }

    /**
     * Ручний запуск імпорту новин з RSS-джерел.
     */
    public function fetchNews()
    {
        Artisan::call('news:fetch');
        $output = trim(Artisan::output());

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Імпорт новин з RSS-джерел виконано. ' . $output);
    }
}
