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
        Schema::create('pengumpulan_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('pengumpulans')->onDelete('cascade');
            $table->foreignId('pertanyaan_id')->constrained('pertanyaans')->onDelete('cascade');
            $table->foreignId('jawaban_id')->nullable()->constrained('opsi_jawaban')->onDelete('cascade');
            $table->text('jawaban_teks')->nullable();
            $table->string('tautan_bukti_drive')->nullable();
            $table->decimal('skor_sistem', 8, 2)->default(0);
            $table->decimal('skor_validasi_reviewer', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_jawabans');
    }
};
