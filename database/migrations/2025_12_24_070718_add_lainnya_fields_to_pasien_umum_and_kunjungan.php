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
        // Add pekerjaan_lainnya to pasien_umum table
        Schema::table('pasien_umum', function (Blueprint $table) {
            $table->string('pekerjaan_lainnya', 100)->nullable()->after('pekerjaan_id');
        });
        
        // Add carapembayaran_lainnya to kunjungan table
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->string('carapembayaran_lainnya', 100)->nullable()->after('carapembayaran_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasien_umum', function (Blueprint $table) {
            $table->dropColumn('pekerjaan_lainnya');
        });
        
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->dropColumn('carapembayaran_lainnya');
        });
    }
};
