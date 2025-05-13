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
        Schema::create('bayi_barulahir', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bayi', 100);
            $table->string('nik_ibuk', 16);
            $table->string('no_rekamedis', 50);
            $table->string('tempat_lahir', 30);
            $table->date('tanggal_lahir');
            $table->time('jam_lahir');
            $table->unsignedBigInteger('jk_id');
            $table->timestamps();

            $table->foreign('jk_id')->references('id')->on('jenis_kelamin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bayi_barulahir');
    }
};