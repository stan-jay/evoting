<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('status')->default('active');
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->after('id')->constrained('organizations')->cascadeOnDelete();
            $table->index(['organization_id', 'role'], 'users_org_role_idx');
        });

        Schema::table('elections', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->after('id')->constrained('organizations')->cascadeOnDelete();
            $table->index(['organization_id', 'status'], 'elections_org_status_idx');
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->after('id')->constrained('organizations')->cascadeOnDelete();
            $table->index('organization_id', 'positions_org_idx');
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->after('id')->constrained('organizations')->cascadeOnDelete();
            $table->index('organization_id', 'candidates_org_idx');
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->after('id')->constrained('organizations')->cascadeOnDelete();
            $table->index(['organization_id', 'election_id'], 'votes_org_election_idx');
        });

        Schema::table('vote_audits', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->after('id')->constrained('organizations')->cascadeOnDelete();
            $table->index('organization_id', 'vote_audits_org_idx');
        });

        Schema::create('organization_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->string('email');
            $table->string('name')->nullable();
            $table->string('role')->default('voter');
            $table->string('token')->unique();
            $table->foreignId('invited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'email', 'accepted_at'], 'org_invites_org_email_status_idx');
        });

        $defaultOrganizationId = DB::table('organizations')->insertGetId([
            'name' => 'Default Organization',
            'slug' => 'default-' . Str::lower(Str::random(6)),
            'status' => 'active',
            'settings' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->whereNull('organization_id')->update(['organization_id' => $defaultOrganizationId]);
        DB::table('elections')->whereNull('organization_id')->update(['organization_id' => $defaultOrganizationId]);
        DB::table('positions')->whereNull('organization_id')->update(['organization_id' => $defaultOrganizationId]);
        DB::table('candidates')->whereNull('organization_id')->update(['organization_id' => $defaultOrganizationId]);
        DB::table('votes')->whereNull('organization_id')->update(['organization_id' => $defaultOrganizationId]);
        DB::table('vote_audits')->whereNull('organization_id')->update(['organization_id' => $defaultOrganizationId]);
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_invites');

        Schema::table('vote_audits', function (Blueprint $table) {
            $table->dropIndex('vote_audits_org_idx');
            $table->dropConstrainedForeignId('organization_id');
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->dropIndex('votes_org_election_idx');
            $table->dropConstrainedForeignId('organization_id');
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropIndex('candidates_org_idx');
            $table->dropConstrainedForeignId('organization_id');
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->dropIndex('positions_org_idx');
            $table->dropConstrainedForeignId('organization_id');
        });

        Schema::table('elections', function (Blueprint $table) {
            $table->dropIndex('elections_org_status_idx');
            $table->dropConstrainedForeignId('organization_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_org_role_idx');
            $table->dropConstrainedForeignId('organization_id');
        });

        Schema::dropIfExists('organizations');
    }
};
