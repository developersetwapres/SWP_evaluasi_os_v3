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
        Schema::create('penugasans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('bobot_skor_id')->constrained('bobot_skors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('siklus_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('outsourcing_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('penilai_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('tipe_penilai', ['atasan', 'penerima_layanan1', 'penerima_layanan2']);
            $table->enum('status', ['completed', 'draft', 'incomplete'])->default('incomplete');
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->unique(['siklus_id', 'outsourcing_id', 'tipe_penilai'], 'uq_penugasan');
            $table->index('outsourcing_id', 'idx_penugasan_pekerja');
            $table->index('penilai_id', 'idx_penugasan_penilai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasans');
    }
};
