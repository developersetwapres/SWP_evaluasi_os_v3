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
        Schema::create('indikators', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pilar_id')->constrained('pilars')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('jabatan_id')->constrained('jabatans')->onDelete('cascade')->onUpdate('cascade');
            $table->string('title');
            $table->text('defenisi');
            $table->uuid('uuid')->unique();
            $table->json('example');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikators');
    }
};
