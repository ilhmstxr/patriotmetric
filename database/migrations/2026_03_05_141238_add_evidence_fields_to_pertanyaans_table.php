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
        Schema::table('pertanyaans', function (Blueprint $table) {
            $table->string('kode_pertanyaan')->nullable()->after('id');
            $table->text('deskripsi')->nullable()->after('teks_pertanyaan');
            $table->text('kebutuhan_bukti')->nullable()->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pertanyaans', function (Blueprint $table) {
            $table->dropColumn(['kode_pertanyaan', 'deskripsi', 'kebutuhan_bukti']);
        });
    }
};
