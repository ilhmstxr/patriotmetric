<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE assessments MODIFY COLUMN status ENUM('UNVERIFIED', 'ACTIVE', 'IN_PROGRESS', 'SUBMITTED', 'GRADED', 'PUBLISHED') DEFAULT 'ACTIVE'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE assessments MODIFY COLUMN status ENUM('ACTIVE', 'IN_PROGRESS', 'SUBMITTED', 'GRADED', 'PUBLISHED') DEFAULT 'ACTIVE'");
    }
};
