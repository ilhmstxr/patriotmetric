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
        Schema::create('compro_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page', 50)->index();
            $table->string('section', 100)->index();
            $table->string('key', 150);
            $table->enum('type', ['text', 'richtext', 'image', 'url', 'repeater']);
            $table->longText('value')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['page', 'section', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compro_contents');
    }
};
