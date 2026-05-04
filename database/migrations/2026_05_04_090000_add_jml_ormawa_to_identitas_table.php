<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('identitas', function (Blueprint $table) {
            if (!Schema::hasColumn('identitas', 'jml_ormawa')) {
                $table->integer('jml_ormawa')->default(0)->after('jml_ukm');
            }
        });
    }

    public function down(): void
    {
        Schema::table('identitas', function (Blueprint $table) {
            if (Schema::hasColumn('identitas', 'jml_ormawa')) {
                $table->dropColumn('jml_ormawa');
            }
        });
    }
};
