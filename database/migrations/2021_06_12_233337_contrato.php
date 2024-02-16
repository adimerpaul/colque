<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Contrato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato', function (Blueprint $table) {
            $table->increments('id');

            $table->decimal('porcentaje_arsenico', 6, 2)->nullable();
            $table->decimal('porcentaje_antimonio', 6, 2)->nullable();
            $table->decimal('porcentaje_bismuto', 6, 2)->nullable();
            $table->decimal('porcentaje_estanio', 6, 2)->nullable();
            $table->decimal('porcentaje_hierro', 6, 2)->nullable();
            $table->decimal('porcentaje_silico', 6, 2)->nullable();
            $table->decimal('porcentaje_zinc', 6, 2)->nullable();

            $table->decimal('deduccion_elemento', 10, 3)->nullable();
            $table->decimal('deduccion_plata', 10, 3)->nullable();
            $table->decimal('porcentaje_pagable_elemento', 6, 2)->nullable();
            $table->decimal('porcentaje_pagable_plata', 6, 2)->nullable();
            $table->decimal('maquila', 10, 3)->nullable();
            $table->decimal('base', 10, 3)->nullable();
            $table->decimal('escalador', 10, 3)->nullable();

            $table->decimal('deduccion_refinacion_onza', 10, 3)->nullable();
            $table->decimal('refinacion_libra_elemento', 10, 3)->nullable();

            $table->decimal('laboratorio', 10, 3)->nullable();
            $table->decimal('molienda', 10, 3)->nullable();
            $table->decimal('manipuleo', 10, 3)->nullable();
            $table->decimal('margen_administrativo', 10, 3)->nullable();
            $table->decimal('transporte', 10, 3)->nullable();
            $table->decimal('roll_back', 10, 3)->nullable();

            $table->unsignedBigInteger('producto_id')->comment("clave foranea de producto");
            $table->foreign('producto_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('producto');

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
        Schema::dropIfExists('contrato');
    }
}
