<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_invites', function (Blueprint $table) {
            $table->timestamp('invite_sent_at')->nullable()->after('accepted_at');
            $table->text('send_error')->nullable()->after('invite_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('organization_invites', function (Blueprint $table) {
            $table->dropColumn(['invite_sent_at', 'send_error']);
        });
    }
};
