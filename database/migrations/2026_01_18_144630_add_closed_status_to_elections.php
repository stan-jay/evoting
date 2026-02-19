<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE elections ALTER COLUMN status TYPE VARCHAR(20)');
        } else {
            Schema::table('elections', function (Blueprint $table) {
                $table->string('status', 20)->default('pending')->change();
            });
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE elections ALTER COLUMN status TYPE VARCHAR(255)');
        } else {
            Schema::table('elections', function (Blueprint $table) {
                $table->string('status')->default('pending')->change();
            });
        }
    }

};
