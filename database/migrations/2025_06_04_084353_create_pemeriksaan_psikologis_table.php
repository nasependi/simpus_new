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
        Schema::create('pemeriksaan_psikologis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kunjungan_id')->references('id')->on('kunjungan')->onDelete('cascade');

            $table->string('status_psikologis');
            $table->string('sosial_ekonomi');
            $table->string('spiritual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_psikologis');
    }
};
