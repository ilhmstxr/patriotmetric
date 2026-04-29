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
        // Add fields to pengumpulans table
        Schema::table('pengumpulans', function (Blueprint $table) {
            $table->string('email_pic')->nullable()->after('no_hp_pic');
            $table->string('surat_pernyataan')->nullable()->after('email_pic');
            $table->string('sk_pendirian')->nullable()->after('surat_pernyataan');
            $table->string('sk_akreditasi')->nullable()->after('sk_pendirian');
            $table->string('profil_pt')->nullable()->after('sk_akreditasi');
            $table->string('struktur_organisasi')->nullable()->after('profil_pt');
            $table->string('sk_tim')->nullable()->after('struktur_organisasi');
        });

        // Add fields to identitas table
        Schema::table('identitas', function (Blueprint $table) {
            $table->foreignId('institution_id')->nullable()->after('pengumpulan_id');
            $table->integer('jml_ormawa')->default(0)->after('jml_ukm');
            $table->integer('agama_islam')->default(0)->nullable();
            $table->integer('agama_kristen')->default(0)->nullable();
            $table->integer('agama_katolik')->default(0)->nullable();
            $table->integer('agama_hindu')->default(0)->nullable();
            $table->integer('agama_buddha')->default(0)->nullable();
            $table->integer('agama_konghucu')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengumpulans', function (Blueprint $table) {
            $table->dropColumn([
                'email_pic',
                'surat_pernyataan',
                'sk_pendirian',
                'sk_akreditasi',
                'profil_pt',
                'struktur_organisasi',
                'sk_tim',
            ]);
        });

        Schema::table('identitas', function (Blueprint $table) {
            $table->dropColumn([
                'institution_id',
                'jml_ormawa',
                'agama_islam',
                'agama_kristen',
                'agama_katolik',
                'agama_hindu',
                'agama_buddha',
                'agama_konghucu',
            ]);
        });
    }
};
