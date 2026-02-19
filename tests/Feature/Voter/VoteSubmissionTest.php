<?php

namespace Tests\Feature\Voter;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_voter_cannot_submit_candidate_from_another_position(): void
    {
        $voter = User::factory()->create([
            'role' => 'voter',
            'status' => 'active',
        ]);

        $election = Election::create([
            'title' => 'Campus Election',
            'start_time' => now()->subHour(),
            'end_time' => now()->addHour(),
            'status' => 'active',
        ]);

        $positionA = Position::create(['name' => 'President', 'election_id' => $election->id]);
        $positionB = Position::create(['name' => 'Secretary', 'election_id' => $election->id]);

        $candidateForB = Candidate::create([
            'name' => 'Candidate B',
            'position_id' => $positionB->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($voter)->post(route('voter.vote.submit'), [
            'election_id' => $election->id,
            'votes' => [
                $positionA->id => $candidateForB->id,
            ],
        ]);

        $response->assertSessionHasErrors(["votes.{$positionA->id}"]);
        $this->assertDatabaseCount('votes', 0);
    }

    public function test_duplicate_vote_is_rejected_without_server_error(): void
    {
        $voter = User::factory()->create([
            'role' => 'voter',
            'status' => 'active',
        ]);

        $election = Election::create([
            'title' => 'Campus Election',
            'start_time' => now()->subHour(),
            'end_time' => now()->addHour(),
            'status' => 'active',
        ]);

        $position = Position::create(['name' => 'President', 'election_id' => $election->id]);
        $candidate = Candidate::create([
            'name' => 'Candidate A',
            'position_id' => $position->id,
            'status' => 'approved',
        ]);

        $payload = [
            'election_id' => $election->id,
            'votes' => [
                $position->id => $candidate->id,
            ],
        ];

        $this->actingAs($voter)->post(route('voter.vote.submit'), $payload)
            ->assertRedirect(route('voter.dashboard'));

        $duplicate = $this->actingAs($voter)->from(route('voter.vote.create', $election))->post(route('voter.vote.submit'), $payload);

        $duplicate->assertRedirect(route('voter.vote.create', $election));
        $duplicate->assertSessionHasErrors(['votes']);
        $this->assertEquals(1, Vote::count());
    }

    public function test_suspended_user_is_blocked_from_protected_routes(): void
    {
        $suspendedVoter = User::factory()->create([
            'role' => 'voter',
            'status' => 'suspended',
        ]);

        $response = $this->actingAs($suspendedVoter)->get(route('voter.dashboard'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
    }
}
