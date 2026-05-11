<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminNewsCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    #[Test]
    public function admin_can_view_news_list()
    {
        News::create([
            'title'    => 'Стаття A',
            'content'  => '...',
            'category' => 'Світ',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.news.index'));

        $response->assertStatus(200);
        $response->assertSee('Стаття A');
    }

    #[Test]
    public function admin_can_open_create_form()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.news.create'));

        $response->assertStatus(200);
        $response->assertSee('Нова новина');
    }

    #[Test]
    public function admin_can_create_news()
    {
        $data = [
            'title'        => 'Нова стаття через тест',
            'excerpt'      => 'Короткий опис',
            'content'      => 'Повний текст статті',
            'category'     => 'Tech',
            'author'       => 'Тестовий автор',
            'is_published' => '1',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.news.store'), $data);

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', [
            'title'    => 'Нова стаття через тест',
            'category' => 'Tech',
            'source'   => 'manual',
        ]);
    }

    #[Test]
    public function admin_cannot_create_news_without_title()
    {
        $data = [
            'content'  => 'Текст',
            'category' => 'Tech',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.news.store'), $data);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('news', 0);
    }

    #[Test]
    public function admin_can_edit_news()
    {
        $news = News::create([
            'title'    => 'Оригінальний заголовок',
            'content'  => 'Текст',
            'category' => 'Світ',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.news.edit', $news));

        $response->assertStatus(200);
        $response->assertSee('Оригінальний заголовок');
    }

    #[Test]
    public function admin_can_update_news()
    {
        $news = News::create([
            'title'    => 'Стара назва',
            'content'  => 'Текст',
            'category' => 'Світ',
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.news.update', $news), [
                'title'    => 'Нова назва',
                'content'  => 'Текст',
                'category' => 'Світ',
            ]);

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', [
            'id'    => $news->id,
            'title' => 'Нова назва',
        ]);
    }

    #[Test]
    public function admin_can_delete_news()
    {
        $news = News::create([
            'title'    => 'Видалюваний',
            'content'  => 'Текст',
            'category' => 'Світ',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.news.destroy', $news));

        $response->assertRedirect();
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

    #[Test]
    public function guest_cannot_create_news()
    {
        $response = $this->post(route('admin.news.store'), [
            'title'    => 'Спроба гостя',
            'content'  => '...',
            'category' => 'Світ',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('news', 0);
    }
}