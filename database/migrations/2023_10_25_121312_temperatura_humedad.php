<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TemperaturaHumedad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.temperatura_humedad', function (Blueprint $table) {
            $table->id();
            $table->string('ambiente', 20);
            $table->string('tipo', 20);
            $table->unsignedDecimal('valor',8, 2)->default(0);
            $table->unsignedInteger('rango_medicion_id')->comment("clave foranea de rango_medicion");
            $table->foreign('rango_medicion_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('laboratorio.rango_medicion');
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
        Schema::dropIfExists('laboratorio.temperatura_humedad');
    }
}
