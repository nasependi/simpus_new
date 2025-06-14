<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('general_consent', function (Blueprint $table) {
            $table->id();

            // Relasi ke pasien_umum
            $table->foreignId('kunjungan_id')->references('id')->on('kunjungan')->onDelete('cascade');

            // Tanggal dan Jam
            $table->date('tanggal');
            $table->time('jam');

            // Persetujuan Pasien
            $table->boolean('persetujuan_pasien');
            $table->boolean('informasi_ketentuan_pembayaran');
            $table->boolean('informasi_hak_kewajiban');
            $table->boolean('informasi_tata_tertib_rs');
            $table->boolean('kebutuhan_penerjemah_bahasa');
            $table->boolean('kebutuhan_rohaniawan');

            // Pelepasan Informasi
            $table->boolean('kerahasiaan_informasi');
            $table->boolean('pemeriksaan_ke_pihak_penjamin');
            $table->boolean('pemeriksaan_diakses_peserta_didik');
            $table->string('anggota_keluarga_dapat_akses')->nullable(); // Bisa berupa "1. Istri; 2. Anak"
            $table->boolean('akses_fasyankes_rujukan');

            // Yang Membuat Pernyataan
            $table->string('penanggung_jawab');
            $table->string('petugas_pemberi_penjelasan');

            $table->text('td_petugas');
            $table->text('td_penanggung_jawab');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_consent');
    }
};
