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
        Schema::create('radiologi', function (Blueprint $table) {
            $table->id();

            $table->string('nama_pemeriksaan');
            $table->string('jenis_pemeriksaan');
            $table->string('nomor_pemeriksaan');
            $table->date('tanggal_permintaan');
            $table->time('jam_permintaan');
            $table->string('dokter_pengirim');
            $table->text('nomor_telepon_dokter');
            $table->text('nama_fasilitas_radiologi');
            $table->text('unit_pengirim_radiologi');
            $table->string('prioritas_pemeriksaan_radiologi');
            $table->text('diagnosis_kerja');
            $table->text('catatan_permintaan');
            $table->string('metode_penyampaian_pemeriksaan');
            $table->boolean('status_alergi');
            $table->string('status_kehamilan');
            $table->date('tanggal_pemeriksaan');
            $table->time('jam_pemeriksaan');
            $table->text('jenis_bahan_kontras');
            $table->string('foto_hasil');
            $table->string('nama_dokter_pemeriksaan');
            $table->text('interpretasi_radiologi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiologi');
    }
};
