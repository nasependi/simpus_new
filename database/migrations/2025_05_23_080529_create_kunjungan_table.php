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
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasien_id');
            $table->unsignedBigInteger('poli_id');
            $table->unsignedBigInteger('carapembayaran_id');
            $table->date('tanggal_kunjungan');
            $table->integer('umur_tahun');
            $table->integer('umur_bulan');
            $table->integer('umur_hari');
            $table->timestamps();


            // Foreign Keys
            $table->foreign('pasien_id')->references('id')->on('pasien_umum');
            $table->foreign('poli_id')->references('id')->on('poli');
            $table->foreign('carapembayaran_id')->references('id')->on('cara_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};
