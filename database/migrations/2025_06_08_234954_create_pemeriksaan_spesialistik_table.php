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
        Schema::create('pemeriksaan_spesialistik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')->references('id')->on('kunjungan')->onDelete('cascade');
            
            $table->text('nama_obat');
            $table->text('dosis');
            $table->text('waktu_penggunaan');
            $table->text('rencana_rawat');
            $table->text('intruksi_medik');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_spesialistik');
    }
};
