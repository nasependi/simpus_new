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
        Schema::table('pasien_umum', function (Blueprint $table) {
            // Make domicile fields nullable
            $table->text('alamat_domisili')->nullable()->change();
            $table->string('domisili_rt')->nullable()->change();
            $table->string('domisili_rw')->nullable()->change();
            $table->string('domisili_kel')->nullable()->change();
            $table->string('domisili_kec')->nullable()->change();
            $table->string('domisili_kab')->nullable()->change();
            $table->string('domisili_kodepos')->nullable()->change();
            $table->string('domisili_prov')->nullable()->change();
            $table->string('domisili_negara')->nullable()->change();
            
            // Make kodepos_id nullable
            $table->string('kodepos_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasien_umum', function (Blueprint $table) {
            // Revert back to NOT NULL
            $table->text('alamat_domisili')->nullable(false)->change();
            $table->string('domisili_rt')->nullable(false)->change();
            $table->string('domisili_rw')->nullable(false)->change();
            $table->string('domisili_kel')->nullable(false)->change();
            $table->string('domisili_kec')->nullable(false)->change();
            $table->string('domisili_kab')->nullable(false)->change();
            $table->string('domisili_kodepos')->nullable(false)->change();
            $table->string('domisili_prov')->nullable(false)->change();
            $table->string('domisili_negara')->nullable(false)->change();
            
            $table->string('kodepos_id')->nullable(false)->change();
        });
    }
};
