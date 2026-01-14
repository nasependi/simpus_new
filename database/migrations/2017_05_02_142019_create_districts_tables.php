<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictsTables extends Migration
{
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->char('id', 7)->primary();
            $table->char('regency_id', 4);
            $table->string('name', 50);

            $table->foreign('regency_id')
                ->references('id')
                ->on('regencies')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('districts');
    }
}
