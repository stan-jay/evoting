<?php

namespace Tests\Feature\Admin;

use App\Jobs\SendOrganizationInviteJob;
use App\Models\Organization;
use App\Models\OrganizationInvite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class InviteManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_bulk_invites_and_jobs_are_queued(): void
    {
        Queue::fake();

        $organization = Organization::create([
            'name' => 'Invite Org',
            'slug' => 'invite-org',
            'status' => 'active',
        ]);

        $admin = User::factory()->create([
            'organization_id' => $organization->id,
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.invites.store'), [
            'emails' => "user1@example.com\nuser2@example.com",
            'role' => 'voter',
            'expires_in_days' => 7,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('organization_invites', 2);
        Queue::assertPushed(SendOrganizationInviteJob::class, 2);
    }

    public function test_duplicate_pending_email_is_not_reinvited(): void
    {
        Queue::fake();

        $organization = Organization::create([
            'name' => 'Invite Org Dup',
            'slug' => 'invite-org-dup',
            'status' => 'active',
        ]);

        $admin = User::factory()->create([
            'organization_id' => $organization->id,
            'role' => 'admin',
            'status' => 'active',
        ]);

        OrganizationInvite::withoutGlobalScope('organization')->create([
            'organization_id' => $organization->id,
            'email' => 'existing@example.com',
            'role' => 'voter',
            'expires_at' => now()->addDay(),
        ]);

        $this->actingAs($admin)->post(route('admin.invites.store'), [
            'emails' => 'existing@example.com',
            'role' => 'voter',
        ])->assertRedirect();

        $this->assertDatabaseCount('organization_invites', 1);
        Queue::assertNothingPushed();
    }

    public function test_admin_can_resend_pending_invite(): void
    {
        Queue::fake();

        $organization = Organization::create([
            'name' => 'Invite Org Resend',
            'slug' => 'invite-org-resend',
            'status' => 'active',
        ]);

        $admin = User::factory()->create([
            'organization_id' => $organization->id,
            'role' => 'admin',
            'status' => 'active',
        ]);

        $invite = OrganizationInvite::withoutGlobalScope('organization')->create([
            'organization_id' => $organization->id,
            'email' => 'pending@example.com',
            'role' => 'voter',
            'expires_at' => now()->addDay(),
            'send_error' => 'temporary smtp error',
        ]);

        $this->actingAs($admin)->post(route('admin.invites.resend', $invite))
            ->assertRedirect();

        $invite->refresh();
        $this->assertNull($invite->send_error);
        Queue::assertPushed(SendOrganizationInviteJob::class, 1);
    }
}
