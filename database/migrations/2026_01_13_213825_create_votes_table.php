<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->foreignId('election_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->foreignId('position_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->foreignId('candidate_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->timestamp('voted_at')->useCurrent();

    // HARD LOCK: one vote per user per position per election
    $table->unique(['user_id', 'election_id', 'position_id']);
});
 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
