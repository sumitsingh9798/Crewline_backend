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
        // Add phone only if it does not already exist
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('email');
            });
        }

        // Add company only if it does not already exist
        if (!Schema::hasColumn('users', 'company')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('company')->nullable()->after('phone');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove company if it exists
        if (Schema::hasColumn('users', 'company')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('company');
            });
        }

        // Remove phone if it exists
        if (Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
        }
    }
};