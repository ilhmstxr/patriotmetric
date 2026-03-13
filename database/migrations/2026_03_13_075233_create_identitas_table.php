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
            $table->foreignId('assessment_id')->references('id')->on('assessments')->onUpdate('cascade')->onDelete('cascade');

            // Data Baseline (Penyebut Rumus)
            $table->integer('jml_mahasiswa')->default(0);
            $table->integer('jml_dosen')->default(0);
            $table->integer('jml_tendik')->default(0);
            $table->integer('jml_prodi')->default(0);
            $table->integer('jml_ukm')->default(0);

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
