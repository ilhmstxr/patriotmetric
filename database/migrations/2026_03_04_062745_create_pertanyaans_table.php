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
        Schema::create('pertanyaans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pertanyaan')->nullable(); // dummy
            $table->foreignId('category_id')->constrained('kategoris')->onDelete('cascade');
            $table->text('teks_pertanyaan');
            $table->text('deskripsi')->nullable(); // dummy
            $table->text('kebutuhan_bukti')->nullable(); // dummy
            $table->enum('tipe', ['pilihan_ganda', 'isian_singkat','otomatis_sistem']);
            $table->integer('skor_maksimal')->default(0); // dummy (cek rubrik)
            // $table->json('opsi_jawaban')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaans');
    }
};
