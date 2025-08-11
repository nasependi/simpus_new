<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan_obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualan_obat')->onDelete('cascade');
            $table->foreignId('obat_id')->constrained('obat')->onDelete('cascade');
            $table->integer('kuantitas');
            $table->float('harga_beli', 15, 2);
            $table->float('jumlah', 15, 2);
            $table->date('kadaluarsa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan_obat');
    }
};
