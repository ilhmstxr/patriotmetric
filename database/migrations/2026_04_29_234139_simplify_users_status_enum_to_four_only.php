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
        // Safety: Update any non-standard status to ACTIVE
        \Illuminate\Support\Facades\DB::table('users')
            ->whereNotIn('status', ["ACTIVE", "IN_PROGRESS", "SUBMITTED", "GRADED"])
            ->update(['status' => 'ACTIVE']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ["ACTIVE", "IN_PROGRESS", "SUBMITTED", "GRADED"])
                  ->default("ACTIVE")
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ["ACTIVE", "IN_PROGRESS", "SUBMITTED", "GRADED", "SUSPENDED", "BANNED"])
                  ->default("ACTIVE")
                  ->change();
        });
    }
};
