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
        Schema::create('pemeriksaan_fisik', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kunjungan_id')->references('id')->on('kunjungan')->onDelete('cascade');
            $table->foreignId('tingkatkesadaran_id')->references('id')->on('tingkat_kesadaran')->onDelete('cascade');

            $table->text('gambar_anatomitubuh');
            $table->string('denyut_jantung');
            $table->string('pernapasan');
            $table->integer('sistole');
            $table->integer('diastole');
            $table->integer('suhu_tubuh');
            $table->text('kepala');
            $table->text('mata');
            $table->text('telinga');
            $table->text('hidung');
            $table->text('rambut');
            $table->text('bibir');
            $table->text('gigi_geligi');
            $table->text('lidah');
            $table->text('langit_langit');
            $table->text('leher');
            $table->text('tenggorokan');
            $table->text('tonsil');
            $table->text('dada');
            $table->text('payudara');
            $table->text('punggung');
            $table->text('perut');
            $table->text('genital');
            $table->text('anus');
            $table->text('lengan_atas');
            $table->text('lengan_bawah');
            $table->text('kuku_tangan');
            $table->text('persendian_tangan');
            $table->text('tungkai_atas');
            $table->text('tungkai_bawah');
            $table->text('jari_kaki');
            $table->text('kuku_kaki');
            $table->text('persendian_kaki');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_fisik');
    }
};
