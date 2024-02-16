<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TipoHorarioPersonal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh.tipo_horario_personal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_inicial');
            $table->date('fecha_fin');
            
            $table->unsignedInteger('tipo_horario_id')->comment("clave foranea de tipo horario");
            $table->foreign('tipo_horario_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('rrhh.tipo_horario');
            
            $table->unsignedInteger('personal_id')->comment("clave foranea de personal");
            $table->foreign('personal_id')
                    ->references('id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade')
                    ->on('personal');
            
            
           
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
        Schema::dropIfExists('rrhh.tipo_horario_personal');
    }
}
