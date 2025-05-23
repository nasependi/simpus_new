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
        Schema::create('cara_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Contoh: Mandiri, JKN, Asuransi XYZ
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(true); // Aktif/Nonaktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cara_pembayaran');
    }
};
