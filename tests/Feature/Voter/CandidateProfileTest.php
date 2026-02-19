<?php

namespace Tests\Feature\Voter;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_voter_can_view_candidate_manifesto_from_ballot(): void
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

        $position = Position::create([
            'name' => 'President',
            'election_id' => $election->id,
        ]);

        $candidate = Candidate::create([
            'name' => 'Candidate A',
            'position_id' => $position->id,
            'status' => 'approved',
            'manifesto' => 'My manifesto',
        ]);

        $response = $this->actingAs($voter)->get(route('voter.vote.candidate.show', [$election, $candidate]));

        $response->assertOk();
        $response->assertSee('My manifesto');
    }
}
