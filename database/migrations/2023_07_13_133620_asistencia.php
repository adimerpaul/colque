<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Asistencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh.asistencia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('hora_marcada');
            $table->unsignedInteger('user_edicion')->nullable();
            $table->unsignedInteger('user_registro')->nullable();
            $table->string('tipo_asistencia',100);
            $table->string('observacion',200)->nullable();
            $table->unsignedInteger('personal_id')->nullable()->comment("clave foranea de personal");
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
        Schema::dropIfExists('rrhh.asistencia');
    }
}
