<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institusis', function (Blueprint $table) {
            if (!Schema::hasColumn('institusis', 'domain_email')) {
                $table->string('domain_email', 100)->nullable()->after('jenis_institusi')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('institusis', function (Blueprint $table) {
            if (Schema::hasColumn('institusis', 'domain_email')) {
                $table->dropColumn('domain_email');
            }
        });
    }
};
