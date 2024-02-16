<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AsistenciaManual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh.asistencia_manual', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('inicio');
            $table->datetime('fin');
            $table->string('motivo',500)->nullable();
            $table->boolean('es_aprobado')->default(false);

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
        Schema::dropIfExists('rrhh.asistencia_manual');
    }
}
