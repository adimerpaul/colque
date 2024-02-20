<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TipoCambio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_cambio', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha')->unique()->comment("fecha de tipo cambio");
            $table->decimal('dolar_compra', 4, 2)->comment("precio de compra de dolar");
            $table->decimal('dolar_venta', 4, 2)->comment("precio de venta de dolar");
            $table->boolean('api')->default(true)->comment("es registrado por API");
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
        Schema::dropIfExists('tipo_cambio');
    }
}
