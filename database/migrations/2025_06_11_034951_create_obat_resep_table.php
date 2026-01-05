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
        Schema::create('obat_resep', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')->references('id')->on('kunjungan')->onDelete('cascade');

            $table->string('tb_pasien');
            $table->string('bb_pasien');
            $table->string('id_resep');
            $table->string('nama_obat');
            $table->string('id_obat');
            $table->string('sediaan');
            $table->integer('jumlah_obat');
            $table->text('metode_pemberian');
            $table->text('dosis_diberikan');
            $table->text('unit');
            $table->text('frekuensi');
            $table->text('aturan_tambahan');
            $table->text('catatan_resep');
            $table->text('dokter_penulis_resep');
            $table->text('nomor_telepon_dokter');
            $table->date('tanggal_penulisan_resep');
            $table->time('jam_penulisan_resep');
            $table->text('ttd_dokter');
            $table->text('status_resep');
            $table->string('pengkajian_resep');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat_resep');
    }
};
