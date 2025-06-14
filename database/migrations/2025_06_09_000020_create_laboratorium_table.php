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
        Schema::create('laboratorium', function (Blueprint $table) {
            $table->id();
            
            $table->text('nama_pemeriksaan');
            $table->text('nomor_pemeriksaan');
            $table->date('tanggal_permintaan');
            $table->time('jam_permintaan');
            $table->text('dokter_pengirim');
            $table->text('nomor_telepon_dokter');
            $table->text('nama_fasilitas_pelayanan');
            $table->text('unit_pengirim');
            $table->boolean('prioritas_pemeriksaan');
            $table->text('diagnosis_masalah');
            $table->text('catatan_permintaan');
            $table->string('metode_pengiriman');
            $table->string('asal_sumber_spesimen');
            $table->text('lokasi_pengambilan_spesimen');
            $table->text('jumlah_spesimen');
            $table->text('volume_spesimen');
            $table->text('metode_pengambilan_spesimen');
            $table->date('tanggal_pengambilan_spesimen');
            $table->time('jam_pengambilan_spesimen');
            $table->text('kondisi_spesimen');
            $table->date('tanggal_fiksasi_spesimen');
            $table->time('jam_fiksasi_spesimen');
            $table->string('cairan_fiksasi');
            $table->string('volume_cairan_fiksasi');

            // Variabel 22–26
            $table->text('petugas_mengambil_spesimen');
            $table->text('petugas_mengantarkan_spesimen');
            $table->text('petugas_menerima_spesimen');
            $table->text('petugas_menganalisis_spesimen');
            $table->date('tanggal_pemeriksaan_spesimen');
            $table->time('jam_pemeriksaan_spesimen');

            // Variabel 27–32
            $table->text('nilai_hasil_pemeriksaan');
            $table->string('nilai_moral');
            $table->text('nilai_rujukan');
            $table->text('nilai_kritis');
            $table->text('interpretasi_hasil');
            $table->text('dokter_validasi');
            $table->text('dokter_interpretasi');
            $table->date('tanggalpemeriksaan_keluar');
            $table->time('jam_pemeriksaan_keluar');
            $table->date('tanggal_pemeriksaan_diterima');
            $table->time('jam_pemeriksaan_diterima');
            $table->text('fasilitas_kesehatan_pemeriksaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorium');
    }
};
