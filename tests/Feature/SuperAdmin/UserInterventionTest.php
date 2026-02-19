<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserInterventionTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_suspend_org_admin(): void
    {
        $organization = Organization::create([
            'name' => 'Intervention Org',
            'slug' => 'intervention-org',
            'status' => 'active',
        ]);

        $superAdmin = User::factory()->create([
            'organization_id' => $organization->id,
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        $orgAdmin = User::factory()->create([
            'organization_id' => $organization->id,
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($superAdmin)->put(route('super_admin.users.update', $orgAdmin), [
            'role' => 'admin',
            'status' => 'suspended',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $orgAdmin->id,
            'status' => 'suspended',
        ]);
    }
}
