<?php

namespace Tests\Unit;

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_automatically_generates_slug_when_creating_news()
    {
        $news = News::create([
            'title'    => 'Тестова новина про щось важливе',
            'content'  => 'Текст новини',
            'category' => 'Світ',
        ]);

        $this->assertNotEmpty($news->slug);
        $this->assertStringContainsString('testova-novina', $news->slug);
    }

    #[Test]
    public function it_automatically_sets_published_at_when_creating_news()
    {
        $news = News::create([
            'title'    => 'Новина без дати',
            'content'  => 'Текст',
            'category' => 'Tech',
        ]);

        $this->assertNotNull($news->published_at);
    }

    #[Test]
    public function published_scope_returns_only_published_news()
    {
        News::create([
            'title'        => 'Опублікована',
            'content'      => 'Текст',
            'category'     => 'Світ',
            'is_published' => true,
        ]);

        News::create([
            'title'        => 'Чернетка',
            'content'      => 'Текст',
            'category'     => 'Світ',
            'is_published' => false,
        ]);

        $published = News::published()->get();

        $this->assertCount(1, $published);
        $this->assertEquals('Опублікована', $published->first()->title);
    }

    #[Test]
    public function featured_scope_returns_only_featured_published_news()
    {
        News::create([
            'title'        => 'Featured + опублікована',
            'content'      => 'Текст',
            'category'     => 'Світ',
            'is_published' => true,
            'is_featured'  => true,
        ]);

        News::create([
            'title'        => 'Featured але чернетка',
            'content'      => 'Текст',
            'category'     => 'Світ',
            'is_published' => false,
            'is_featured'  => true,
        ]);

        News::create([
            'title'        => 'Просто опублікована',
            'content'      => 'Текст',
            'category'     => 'Світ',
            'is_published' => true,
            'is_featured'  => false,
        ]);

        $this->assertCount(1, News::featured()->get());
    }

    #[Test]
    public function by_category_scope_filters_by_category()
    {
        News::create(['title' => 'Tech новина',  'content' => '...', 'category' => 'Tech']);
        News::create(['title' => 'Світ новина',  'content' => '...', 'category' => 'Світ']);
        News::create(['title' => 'Tech новина 2','content' => '...', 'category' => 'Tech']);

        $tech = News::byCategory('Tech')->get();

        $this->assertCount(2, $tech);
    }

    #[Test]
    public function it_casts_boolean_fields_correctly()
    {
        $news = News::create([
            'title'        => 'Бул-тест',
            'content'      => '...',
            'category'     => 'Світ',
            'is_published' => 1,
            'is_featured'  => 0,
        ]);

        $this->assertIsBool($news->is_published);
        $this->assertIsBool($news->is_featured);
        $this->assertTrue($news->is_published);
        $this->assertFalse($news->is_featured);
    }
}