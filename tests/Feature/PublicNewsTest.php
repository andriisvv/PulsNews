<?php

namespace Tests\Feature;

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PublicNewsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function homepage_loads_successfully()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    #[Test]
    public function homepage_shows_published_news()
    {
        News::create([
            'title'        => 'Опублікована стаття',
            'content'      => 'Текст',
            'category'     => 'Світ',
            'is_published' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Опублікована стаття');
    }

    #[Test]
    public function homepage_does_not_show_draft_news()
    {
        News::create([
            'title'        => 'Це чернетка',
            'content'      => 'Текст',
            'category'     => 'Світ',
            'is_published' => false,
        ]);

        $response = $this->get('/');

        $response->assertDontSee('Це чернетка');
    }

    #[Test]
    public function category_filter_works()
    {
        News::create([
            'title'        => 'Tech стаття',
            'content'      => '...',
            'category'     => 'Tech',
            'is_published' => true,
        ]);

        News::create([
            'title'        => 'Світова стаття',
            'content'      => '...',
            'category'     => 'Світ',
            'is_published' => true,
        ]);

        $response = $this->get('/?category=Tech');

        $response->assertSee('Tech стаття');
        $response->assertDontSee('Світова стаття');
    }

    #[Test]
    public function search_filter_works()
    {
        News::create([
            'title'        => 'Стаття про AI',
            'content'      => 'Штучний інтелект',
            'category'     => 'Tech',
            'is_published' => true,
        ]);

        News::create([
            'title'        => 'Стаття про спорт',
            'content'      => 'Футбол',
            'category'     => 'Спорт',
            'is_published' => true,
        ]);

        $response = $this->get('/?search=AI');

        $response->assertSee('Стаття про AI');
        $response->assertDontSee('Стаття про спорт');
    }

    #[Test]
    public function single_article_page_loads()
    {
        $news = News::create([
            'title'        => 'Стаття для перегляду',
            'content'      => 'Великий текст',
            'category'     => 'Світ',
            'is_published' => true,
        ]);

        $response = $this->get(route('news.show', $news->slug));

        $response->assertStatus(200);
        $response->assertSee('Стаття для перегляду');
        $response->assertSee('Великий текст');
    }

    #[Test]
    public function unpublished_article_returns_404()
    {
        $news = News::create([
            'title'        => 'Прихована',
            'content'      => '...',
            'category'     => 'Світ',
            'is_published' => false,
        ]);

        $response = $this->get(route('news.show', $news->slug));

        $response->assertStatus(404);
    }
}