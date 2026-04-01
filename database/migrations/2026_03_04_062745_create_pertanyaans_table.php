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
            $table->string('kode_pertanyaan'); // dummy
            $table->foreignId('category_id')->constrained('kategoris')->onDelete('cascade');
            $table->string('teks_pertanyaan'); 
            $table->string('deskripsi'); // dummy
            $table->string('kebutuhan_bukti'); // dummy
            $table->string('tipe'); 
            $table->integer('skor_maksimal'); // dummy (cek rubrik)
            $table->json('opsi_jawaban')->nullable();
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
