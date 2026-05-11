<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function login_page_loads()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Pulse Admin');
    }

    #[Test]
    public function user_can_login_with_correct_credentials()
    {
        $user = User::create([
            'name'     => 'Test Admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function user_cannot_login_with_wrong_password()
    {
        User::create([
            'name'     => 'Test Admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    #[Test]
    public function admin_routes_are_protected()
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    #[Test]
    public function admin_can_access_dashboard_when_logged_in()
    {
        $user = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Дашборд');
    }

    #[Test]
    public function user_can_logout()
    {
        $user = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}