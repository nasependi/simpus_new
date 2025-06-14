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
        Schema::create('terapi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->references('id')->on('obat_resep')->onDelete('cascade');

            $table->string('nama_tindakan');
            $table->string('petugas');
            $table->date('tanggal_pelaksanaan_tindakan');
            $table->time('jam_mulai_tindakan');
            $table->time('jam_selesai_tindakan');
            $table->text('alat_medis');
            $table->text('bmhp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terapi');
    }
};
