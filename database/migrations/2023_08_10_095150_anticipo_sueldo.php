<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AnticipoSueldo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh.anticipo_sueldo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('monto');
            $table->string('tipo',10)->default('Egreso');
            $table->boolean('es_cancelado')->default(false);
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
        Schema::dropIfExists('rrhh.anticipo_sueldo');
    }
}
