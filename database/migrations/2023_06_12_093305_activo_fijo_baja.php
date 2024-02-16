<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ActivoFijoBaja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activo.baja', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('cantidad');
            $table->string('motivo')->default('Ninguna');
            $table->unsignedInteger('detalle_activo_id')->comment("clave foranea de detalle activo");
            $table->foreign('detalle_activo_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('activo.detalle_activo');

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
        Schema::dropIfExists('activo.baja');

    }
}
