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
        Schema::table('penugasans', function (Blueprint $table) {
            $table->text('area_pengembangan')->nullable()->after('catatan');
            $table->text('kekuatan_teramati')->nullable()->after('area_pengembangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penugasans', function (Blueprint $table) {
            $table->dropColumn(['area_pengembangan', 'kekuatan_teramati']);
        });
    }
};
