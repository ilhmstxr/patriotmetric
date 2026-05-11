<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('Assessments', function (Blueprint $table) {
            // Menyimpan ringkasan skor total dan per-kategori dalam format JSON
            // Struktur: { "total_persen": 78.50, "per_kategori": { "A.": { "persen": 85.0, "skor": 17, "max": 20, "bobot": 20 }, ... } }
            $table->json('skor_rekap_json')->nullable()->after('total_skor_akhir');
        });
    }

    public function down(): void
    {
        Schema::table('Assessments', function (Blueprint $table) {
            $table->dropColumn('skor_rekap_json');
        });
    }
};
