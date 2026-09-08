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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('indikator_id')->constrained('indikators')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('penugasan_id')->constrained('penugasans')->onDelete('cascade')->onUpdate('cascade');
            $table->smallInteger('nilai');

            $table->timestamps();

            $table->unique(['penugasan_id', 'indikator_id'], 'uq_nilai');
            $table->index('indikator_id', 'fk_nilai_indikator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
