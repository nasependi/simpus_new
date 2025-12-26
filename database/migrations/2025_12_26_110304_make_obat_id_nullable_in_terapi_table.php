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
        Schema::table('terapi', function (Blueprint $table) {
            // Ubah kolom obat_id menjadi nullable karena terapi tidak selalu terkait dengan resep obat
            $table->foreignId('obat_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('terapi', function (Blueprint $table) {
            // Kembalikan ke not nullable jika rollback
            $table->foreignId('obat_id')->nullable(false)->change();
        });
    }
};
