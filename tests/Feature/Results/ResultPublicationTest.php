<?php

namespace Tests\Feature\Results;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultPublicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_voter_cannot_view_unpublished_closed_results(): void
    {
        $voter = User::factory()->create([
            'role' => 'voter',
            'status' => 'active',
        ]);

        $election = Election::create([
            'title' => 'Unpublished Election',
            'start_time' => now()->subHours(2),
            'end_time' => now()->subHour(),
            'status' => 'closed',
        ]);

        $response = $this->actingAs($voter)->get(route('voter.results.show', $election));

        $response->assertForbidden();
    }

    public function test_admin_can_publish_closed_results(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $election = Election::create([
            'title' => 'Closed Election',
            'start_time' => now()->subHours(2),
            'end_time' => now()->subHour(),
            'status' => 'closed',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.results.publish', $election));

        $response->assertRedirect();
        $this->assertDatabaseHas('elections', [
            'id' => $election->id,
            'status' => 'declared',
        ]);
    }

    public function test_admin_can_export_closed_results_as_csv(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $voter = User::factory()->create([
            'role' => 'voter',
            'status' => 'active',
        ]);

        $election = Election::create([
            'title' => 'Export Election',
            'start_time' => now()->subHours(2),
            'end_time' => now()->subHour(),
            'status' => 'closed',
        ]);

        $position = Position::create([
            'name' => 'President',
            'election_id' => $election->id,
        ]);

        $candidate = Candidate::create([
            'name' => 'Alice',
            'position_id' => $position->id,
            'status' => 'approved',
        ]);

        Vote::create([
            'user_id' => $voter->id,
            'election_id' => $election->id,
            'position_id' => $position->id,
            'candidate_id' => $candidate->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.results.excel', $election));

        $response->assertOk();
        $this->assertStringStartsWith('text/csv', (string) $response->headers->get('content-type'));
    }

    public function test_admin_live_endpoint_returns_analytics_payload(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $election = Election::create([
            'title' => 'Live Analytics Election',
            'start_time' => now()->subHour(),
            'end_time' => now()->addHour(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.results.live', $election));

        $response->assertOk();
        $response->assertJsonStructure([
            'summary' => ['total_votes', 'unique_voters', 'eligible_voters', 'turnout_percentage', 'is_published'],
            'trend' => ['labels', 'data'],
            'results_by_position',
            'status',
        ]);
    }

    public function test_officer_live_endpoint_returns_analytics_payload(): void
    {
        $officer = User::factory()->create([
            'role' => 'officer',
            'status' => 'active',
        ]);

        $election = Election::create([
            'title' => 'Officer Live Election',
            'start_time' => now()->subHour(),
            'end_time' => now()->addHour(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($officer)->get(route('officer.results.live', $election));

        $response->assertOk();
        $response->assertJsonStructure([
            'summary' => ['total_votes', 'unique_voters', 'is_published'],
            'trend' => ['labels', 'data'],
        ]);
    }
}
