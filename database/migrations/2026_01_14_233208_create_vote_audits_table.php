<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vote_audits', function (Blueprint $table) {
            $table->id();
            $table->string('user_hash');
            $table->foreignId('election_id')->constrained()->cascadeOnDelete();
            $table->foreignId('position_id')->constrained()->cascadeOnDelete();
            $table->timestamp('voted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vote_audits');
    }
};
