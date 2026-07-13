<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/signup');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/signup', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('register.thank-you', absolute: false));
    }

    public function test_registration_normalizes_mixed_case_email(): void
    {
        $this->post('/signup', [
            'first_name' => 'Mixed',
            'last_name' => 'Case',
            'email' => 'Mixed.Case@Example.COM',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ])->assertRedirect(route('register.thank-you', absolute: false));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'mixed.case@example.com',
            'is_active' => true,
        ]);
    }

    public function test_registration_thank_you_shows_next_actions(): void
    {
        $response = $this->post('/signup', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'thanks@example.com',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ]);

        $response->assertRedirect(route('register.thank-you', absolute: false));

        $this->get(route('register.thank-you'))
            ->assertOk()
            ->assertSee('Thank you for signing up')
            ->assertSee('Complete profile')
            ->assertSee('Go to dashboard');
    }

    public function test_registered_user_can_reach_dashboard_and_login_again(): void
    {
        $this->post('/signup', [
            'first_name' => 'Loop',
            'last_name' => 'User',
            'email' => 'loop@example.com',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ])->assertRedirect(route('register.thank-you', absolute: false));

        $this->get(route('dashboard'))->assertOk();

        $this->post('/logout')->assertRedirect('/');

        $this->post('/login', [
            'email' => 'Loop@Example.com',
            'password' => 'password1',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
        $this->get(route('dashboard'))->assertOk();
    }
}
