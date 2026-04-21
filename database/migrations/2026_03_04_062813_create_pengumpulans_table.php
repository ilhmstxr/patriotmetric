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
        Schema::create('pengumpulans', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignUuid('institution_id')->constrained('institusis')->onUpdate('cascade')->onDelete('cascade');

            // Data PIC (Snapshot per tahun)
            $table->string('nama_pic');
            $table->string('jabatan_pic')->nullable();
            $table->string('no_hp_pic');

            $table->year('tahun_periode');
            $table->enum('status', ['ACTIVE', 'IN_PROGRESS', 'SUBMITTED', 'GRADED'])->default('ACTIVE');

            // Satu institusi hanya boleh punya satu asesmen per tahun
            $table->unique(['institution_id', 'tahun_periode']);
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('total_skor_sistem', 8, 2)->default(0);
            $table->decimal('total_skor_akhir', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulans');
    }
};
