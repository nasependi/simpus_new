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
        Schema::create('pemeriksaan_penunjang', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kunjungan_id')->references('id')->on('kunjungan')->onDelete('cascade');
            $table->foreignId('laboratorium_id')->references('id')->on('laboratorium')->onDelete('cascade');

            $table->time('jam');
            $table->date('tanggal');
            $table->boolean('status_pasien');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_penunjang');
    }
};
