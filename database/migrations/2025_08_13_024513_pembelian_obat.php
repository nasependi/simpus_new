<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian_obat', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur');
            $table->integer('jumlah_beli');
            $table->float('ppn', 10, 2)->default(0);
            $table->float('pph', 10, 2)->default(0);
            $table->float('diskon', 10, 2)->default(0);
            $table->float('harga_beli_kotor', 15, 2);
            $table->float('harga_beli_bersih', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_obat');
    }
};
