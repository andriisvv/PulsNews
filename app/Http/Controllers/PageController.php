<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\News;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Спільні дані для хедера інформаційних сторінок (категорії навігації).
     */
    private function categories()
    {
        return News::where('is_published', true)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    /**
     * Сторінка «Про нас»
     */
    public function about()
    {
        return view('pages.about', [
            'categories' => $this->categories(),
        ]);
    }

    /**
     * Сторінка «Реклама»
     */
    public function advertising()
    {
        return view('pages.advertising', [
            'categories' => $this->categories(),
        ]);
    }

    /**
     * Сторінка «Контакти» з формою зворотного звʼязку
     */
    public function contacts()
    {
        return view('pages.contacts', [
            'categories' => $this->categories(),
        ]);
    }

    /**
     * Обробка надісланого повідомлення з форми зворотного звʼязку
     */
    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'required|email|max:200',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        $validated['ip_address'] = $request->ip();
        $validated['is_read'] = false;

        ContactMessage::create($validated);

        return redirect()
            ->route('contacts')
            ->with('success', 'Дякуємо! Ваше повідомлення надіслано. Ми звʼяжемося з вами найближчим часом.');
    }
}
