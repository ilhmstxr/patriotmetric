<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds the 'is_peserta_profile_edit_enabled' key to pengaturan_cms.
     * This flag controls whether participants can edit their profile (PIC data).
     * Separate from 'is_peserta_edit_enabled' which controls rubrik form editing.
     */
    public function up(): void
    {
        DB::table('pengaturan_cms')->insertOrIgnore([
            'key'        => 'is_peserta_profile_edit_enabled',
            'value'      => 'true',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pengaturan_cms')
            ->where('key', 'is_peserta_profile_edit_enabled')
            ->delete();
    }
};
