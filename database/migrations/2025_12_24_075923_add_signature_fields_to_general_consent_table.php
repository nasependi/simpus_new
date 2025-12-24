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
        Schema::table('general_consent', function (Blueprint $table) {
            $table->text('ttd_penanggung_jawab')->nullable()->after('penanggung_jawab');
            $table->text('ttd_petugas')->nullable()->after('petugas_pemberi_penjelasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_consent', function (Blueprint $table) {
            $table->dropColumn(['ttd_penanggung_jawab', 'ttd_petugas']);
        });
    }
};
