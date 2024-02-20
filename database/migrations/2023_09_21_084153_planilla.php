<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Planilla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh.planilla', function (Blueprint $table) {
            $table->bigIncrements('id');
            //datos Adicionales
            // $table->date('fecha_planilla');
            $table->string('fecha_planilla', 10);
            //$table->string('tipo_Contrato',15);
            $table->unsignedDecimal('haber_basico',10,2);
            $table->unsignedDecimal('bono_antiguedad',10,2);
            //horas Extra
            $table->unsignedDecimal('numero_horas_extra',8,2);
            $table->unsignedDecimal('hora_extra_monto_pagado',8,2);
            //Bonos
            $table->unsignedDecimal('bono_prod',8,2);
            $table->unsignedDecimal('dominical',8,2);
            $table->unsignedDecimal('otros_bonos',8,2);
            //Descuentos
            $table->unsignedDecimal('afp',8,2);
            $table->unsignedDecimal('aporte_solidario',8,2);
            $table->unsignedDecimal('rc_iva',8,2);
            $table->unsignedDecimal('anticipos_otros_descuentos',8,2);

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
        Schema::dropIfExists('rrhh.planilla');
    }
}
