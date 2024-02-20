<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UbicacionFormulario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubicacion_formulario', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('formulario_liquidacion_id')->comment("clave foranea de formulario_liquidacion");
            $table->foreign('formulario_liquidacion_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('formulario_liquidacion');

            $table->unsignedBigInteger('ubicacion_id')->comment("clave foranea de ubicacion");
            $table->foreign('ubicacion_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('ubicacion');

            $table->boolean('alta')->default(true);
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
        Schema::dropIfExists('ubicacion_formulario');
    }
}
