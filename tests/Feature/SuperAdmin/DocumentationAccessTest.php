<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentationAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_docs_page(): void
    {
        $organization = Organization::create([
            'name' => 'Docs Org',
            'slug' => 'docs-org',
            'status' => 'active',
        ]);

        $superAdmin = User::factory()->create([
            'organization_id' => $organization->id,
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($superAdmin)->get(route('super_admin.docs.show'));

        $response->assertOk();
        $response->assertSee('Super Admin Documentation');
    }

    public function test_admin_cannot_access_docs_page(): void
    {
        $organization = Organization::create([
            'name' => 'Docs Org Two',
            'slug' => 'docs-org-two',
            'status' => 'active',
        ]);

        $admin = User::factory()->create([
            'organization_id' => $organization->id,
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->get(route('super_admin.docs.show'));

        $response->assertForbidden();
    }
}
