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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->uuid('institution_id');
            $table->foreign('institution_id')->references('id')->on('institusis')->onUpdate('cascade')->onDelete('cascade');

            // Data PIC (Snapshot per tahun)
            $table->string('nama_pic');
            $table->string('jabatan_pic')->nullable();
            $table->string('no_hp_pic');

            $table->year('tahun_periode');
            $table->enum('status', ['UNVERIFIED','ACTIVE', 'IN_PROGRESS', 'SUBMITTED', 'GRADED', 'PUBLISHED'])->default('UNVERIFIED');

            // Satu institusi hanya boleh punya satu asesmen per tahun
            $table->unique(['institution_id', 'tahun_periode']);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('reviewer_id')->nullable();
            $table->foreign('reviewer_id')->references('id')->on('reviewers')->onDelete('set null');
            $table->decimal('total_skor_sistem', 8, 2)->default(0);
            $table->decimal('total_skor_akhir', 8, 2)->default(0);
            $table->json('skor_rekap_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
