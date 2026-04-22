<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('identitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengumpulan_id')->references('id')->on('pengumpulans')->onUpdate('cascade')->onDelete('cascade');

            // Data Baseline (Penyebut Rumus)
            $table->integer('jml_mahasiswa')->default(0);
            $table->integer('jml_dosen')->default(0);
            $table->integer('jml_tendik')->default(0);
            $table->integer('jml_prodi')->default(0);
            $table->integer('jml_ukm')->default(0);
            $table->integer('jml_fakultas')->default(0);
            
            // visi misi
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();

            // Link Dokumen Legal (JSON untuk fleksibilitas)
            $table->json('legal_documents')->nullable(); // {'sk_rektor': 'url', 'aipt': 'url'}

            $table->boolean('is_verified')->default(false);
            $table->text('admin_note')->nullable(); // Alasan jika ditolak (Revision Loop)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identitas');
    }
};
