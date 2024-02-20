<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CretateLiquidacionMineralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquidacion_mineral', function (Blueprint $table) {
            $table->id();

            $table->boolean('es_penalizacion')->default(false);

            $table->unsignedBigInteger('formulario_liquidacion_id')->comment("clave foranea de formulario_liquidacion");
            $table->foreign('formulario_liquidacion_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('formulario_liquidacion');

            $table->unsignedBigInteger('mineral_id')->comment("clave foranea de mineral");
            $table->foreign('mineral_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('mineral');

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
        Schema::dropIfExists('liquidacion_mineral');
    }
}
