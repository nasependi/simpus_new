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
        Schema::create('persetujuan_tindakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')->references('id')->on('kunjungan')->onDelete('cascade');

            $table->string('nama_dokter');
            $table->string('nama_petugas_mendampingi');
            $table->string('nama_keluarga_pasien');
            $table->text('tindakan_dilakukan');
            $table->text('konsekuensi_tindakan');
            $table->boolean('persetujuan_penolakan');
            $table->date('tanggal_tindakan');
            $table->time('jam_tindakan');
            $table->string('ttd_dokter');
            $table->string('ttd_pasien_keluarga');
            $table->string('saksi1');
            $table->string('saksi2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persetujuan_tindakan');
    }
};
