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
        Schema::create('pasien_umum', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->string('no_rekamedis', 50);
            $table->string('nik', 16);
            $table->string('paspor', 50)->nullable();
            $table->string('ibu_kandung', 50);
            $table->string('tempat_lahir', 30);
            $table->date('tanggal_lahir');

            $table->unsignedBigInteger('jk_id');
            $table->unsignedBigInteger('agama_id');
            $table->text('suku')->nullable();
            $table->text('bahasa_dikuasai')->nullable();
            $table->text('alamat_lengkap');

            $table->string('rt');
            $table->string('rw');
            $table->string('kel_id');
            $table->string('kec_id');
            $table->string('kab_id');
            $table->string('kodepos_id');
            $table->string('prov_id');

            $table->text('alamat_domisili');
            $table->string('domisili_rt');
            $table->string('domisili_rw');
            $table->string('domisili_kel');
            $table->string('domisili_kec');
            $table->string('domisili_kab');
            $table->string('domisili_kodepos');
            $table->string('domisili_prov');
            $table->string('domisili_negara');

            $table->string('no_rumah', 20)->nullable();
            $table->string('no_hp', 20);

            $table->unsignedBigInteger('pendidikan_id');
            $table->unsignedBigInteger('pekerjaan_id');
            $table->unsignedBigInteger('statusnikah_id');

            $table->timestamps();

            // Foreign Keys
            $table->foreign('jk_id')->references('id')->on('jenis_kelamin');
            $table->foreign('agama_id')->references('id')->on('agama');
            $table->foreign('pendidikan_id')->references('id')->on('pendidikan');
            $table->foreign('pekerjaan_id')->references('id')->on('pekerjaan');
            $table->foreign('statusnikah_id')->references('id')->on('status_pernikahan');

            // foreign dari indoregion
            $table->foreign('prov_id')->references('id')->on('provinces');
            $table->foreign('kab_id')->references('id')->on('regencies');
            $table->foreign('kec_id')->references('id')->on('districts');
            $table->foreign('kel_id')->references('id')->on('villages');

            $table->foreign('domisili_prov')->references('id')->on('provinces');
            $table->foreign('domisili_kab')->references('id')->on('regencies');
            $table->foreign('domisili_kec')->references('id')->on('districts');
            $table->foreign('domisili_kel')->references('id')->on('villages');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien_umum');
    }
};
