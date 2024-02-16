<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Comprador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprador', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nit', 20)->unique()->comment("nit de comprador");
            $table->string('nro_nim', 20)->comment("nim de comprador");
            $table->string('razon_social', 100)->comment("razon_social del comprador");
            $table->string('direccion', 150)->comment("direccion del comprador")->nullable();

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
        Schema::dropIfExists('comprador');
    }
}
