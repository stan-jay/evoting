<?php

namespace Tests\Feature\Auth;

use App\Models\Organization;
use App\Models\OrganizationInvite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $organization = Organization::query()->firstOrCreate(
            ['slug' => 'reg-test-org'],
            ['name' => 'Registration Test Org', 'status' => 'active']
        );

        $invite = OrganizationInvite::withoutGlobalScope('organization')->create([
            'organization_id' => $organization->id,
            'email' => 'invitee@example.com',
            'role' => 'voter',
            'expires_at' => now()->addDay(),
        ]);

        $response = $this->get('/register/' . $invite->token);

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $organization = Organization::query()->firstOrCreate(
            ['slug' => 'reg-test-org-two'],
            ['name' => 'Registration Test Org 2', 'status' => 'active']
        );

        $invite = OrganizationInvite::withoutGlobalScope('organization')->create([
            'organization_id' => $organization->id,
            'email' => 'test@example.com',
            'role' => 'voter',
            'expires_at' => now()->addDay(),
        ]);

        $response = $this->post('/register/' . $invite->token, [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
