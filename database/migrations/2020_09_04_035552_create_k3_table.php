<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateK3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('k3', function (Blueprint $table) {
            $table->id();
            $table->string("lat", 255);
            $table->string("long", 255);
            $table->text("keterangan", 255);
            $table->string("foto", 255);
            $table->string("id_pelapor", 255);
            $table->string("id_admin", 255)->nullable();
            $table->string("sudah_diterima", 255)->default("0");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('k3');
    }
}
