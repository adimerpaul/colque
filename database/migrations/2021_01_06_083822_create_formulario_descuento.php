<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormularioDescuento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulario_descuento', function (Blueprint $table) {
//            $table->id();

            $table->unsignedBigInteger('formulario_liquidacion_id')->comment("clave foranea de formulario_liquidacion");
            $table->foreign('formulario_liquidacion_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('formulario_liquidacion');

            $table->unsignedInteger('descuento_bonificacion_id')->comment("clave foranea de descuento_bonificacion");
            $table->foreign('descuento_bonificacion_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('descuento_bonificacion');

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
        Schema::dropIfExists('formulario_descuento');
    }
}
