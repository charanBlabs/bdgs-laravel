<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/settings/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/dashboard/settings/profile', [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertSame('Updated', $user->first_name);
        $this->assertSame('updated@example.com', $user->email);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/dashboard/settings/profile', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ]);

        $response->assertRedirect();
        $this->assertTrue($user->refresh()->hasVerifiedEmail());
    }
}
