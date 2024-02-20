<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('cufd', 100)->comment("codigo unico de factura digital");
            $table->dateTime('fecha_inicio')->comment("fecha de inicio del evento");
            $table->dateTime('fecha_fin')->comment("fecha de fin del evento");
            $table->string('codigo', 100)->comment("codigo de evento");
            $table->string('codigo_recepcion', 100)->comment("codigo de evento")->nullable();
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
        Schema::dropIfExists('eventos');
    }
}
