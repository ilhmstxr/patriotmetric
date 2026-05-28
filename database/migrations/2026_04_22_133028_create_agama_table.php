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
        Schema::create('agama', function (Blueprint $table) {
            $table->id();
            $table->foreignId('identitas_id')->constrained('identitas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('agama')->nullable();
            $table->integer('jumlah')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agama');
    }
};
