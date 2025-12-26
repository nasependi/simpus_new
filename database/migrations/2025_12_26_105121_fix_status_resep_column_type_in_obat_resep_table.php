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
        Schema::table('obat_resep', function (Blueprint $table) {
            // Ubah kolom status_resep dari integer ke text
            $table->text('status_resep')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obat_resep', function (Blueprint $table) {
            // Kembalikan ke integer jika rollback
            $table->integer('status_resep')->change();
        });
    }
};
