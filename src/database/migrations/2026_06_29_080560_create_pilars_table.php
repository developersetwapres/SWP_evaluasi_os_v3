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
        Schema::create('pilars', function (Blueprint $table) {
            $table->id();

            $table->string('title', 100)->unique();
            $table->uuid('uuid')->unique();
            $table->foreignId('bobot_skor_id')->constrained('bobot_skors')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilars');
    }
};
