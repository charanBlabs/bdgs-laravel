<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeededUserLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\AdminUserSeeder::class,
        ]);
    }

    public function test_seeded_admin_password_hash_is_valid(): void
    {
        $admin = User::query()->where('email', 'admin@bdgrowthsuite.com')->first();

        $this->assertNotNull($admin);
        $this->assertTrue($admin->isAdmin());
        $this->assertTrue(Hash::check('ChangeMe123!', $admin->password));
    }

    public function test_seeded_demo_user_password_hash_is_valid(): void
    {
        $demo = User::query()->where('email', 'demo@bdgrowthsuite.com')->first();

        $this->assertNotNull($demo);
        $this->assertTrue($demo->hasRole('user'));
        $this->assertFalse($demo->isAdmin());
        $this->assertTrue(Hash::check('DemoUser123!', $demo->password));
    }

    public function test_seeded_admin_can_log_in_and_reach_site_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@bdgrowthsuite.com',
            'password' => 'ChangeMe123!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $this->get('/dashboard')->assertOk();
        $this->get('/dashboard/admin')->assertOk();
        $this->get('/admin')->assertOk();
    }

    public function test_seeded_demo_user_can_log_in_and_reach_user_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'demo@bdgrowthsuite.com',
            'password' => 'DemoUser123!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $this->get('/dashboard')->assertOk();
    }
}
