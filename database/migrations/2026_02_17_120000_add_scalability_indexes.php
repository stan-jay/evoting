<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->index(['election_id', 'position_id', 'candidate_id'], 'votes_election_position_candidate_idx');
            $table->index(['election_id', 'created_at'], 'votes_election_created_idx');
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->index(['position_id', 'status'], 'candidates_position_status_idx');
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropIndex('votes_election_position_candidate_idx');
            $table->dropIndex('votes_election_created_idx');
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropIndex('candidates_position_status_idx');
        });
    }
};
