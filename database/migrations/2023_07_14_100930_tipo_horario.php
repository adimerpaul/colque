<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TipoHorario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh.tipo_horario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion', 50);
            $table->time('inicio_semana');
            $table->time('fin_semana');
            $table->time('inicio_sabado')->nullable();
            $table->time('fin_sabado')->nullable();

            
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
        Schema::dropIfExists('rrhh.tipo_horario');
    }
}
