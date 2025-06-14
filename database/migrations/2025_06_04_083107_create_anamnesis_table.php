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
        Schema::create('anamnesis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kunjungan_id')->references('id')->on('kunjungan')->onDelete('cascade');
            
            $table->string('keluhan_utama');
            $table->string('riwayat_penyakit');
            $table->string('riwayat_alergi');
            $table->string('riwayat_pengobatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anamnesis');
    }
};
