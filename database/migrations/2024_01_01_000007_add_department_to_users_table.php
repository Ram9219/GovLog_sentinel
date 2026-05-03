<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add department column for multi-department support
            $table->string('department')->nullable()->after('role');
        });

        // Update role enum to include 'system'
        Schema::table('users', function (Blueprint $table) {
            // For PostgreSQL, we need to drop and recreate the enum
            if (DB::getDriverName() === 'pgsql') {
                DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
                DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'operator', 'viewer', 'system'))");
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('department');
        });

        // Revert role enum to original
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'operator', 'viewer'))");
        }
    }
};
