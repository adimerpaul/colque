<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaboratorioPesoHumedad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio_peso_humedad', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('peso_humedo',10, 2)->default(0);
            $table->unsignedDecimal('peso_seco',10, 2)->default(0);
            $table->unsignedDecimal('peso_tara',10, 2)->default(0);

            $table->unsignedBigInteger('laboratorio_ensayo_id')->comment("clave foranea de laboratorio_ensayo");
            $table->foreign('laboratorio_ensayo_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('laboratorio_ensayo');


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
        Schema::dropIfExists('laboratorio_peso_humedad');
    }
}
