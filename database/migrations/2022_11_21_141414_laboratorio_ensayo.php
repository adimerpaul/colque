<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaboratorioEnsayo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio_ensayo', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo');
            $table->string('cliente', 100)->default('Colquechaca Mining')->nullable();
            $table->string('direccion', 100)->default('Av. Barzola entre Corihuayra, Celestino Gutierrez y Heroes de la Coronilla #174')->nullable();
            $table->string('caracteristicas', 100)->nullable();
            $table->boolean('es_finalizado')->default(false);
            $table->date('fecha_muestreo')->nullable();
            $table->date('fecha_finalizacion')->nullable();
            $table->unsignedDecimal('peso_humedo',10, 2)->default(0);
            $table->unsignedDecimal('peso_seco',10, 2)->default(0);
            $table->unsignedDecimal('peso_tara',10, 2)->default(0);

            $table->unsignedBigInteger('formulario_liquidacion_id')->comment("clave foranea de formulario_liquidacion");
            $table->foreign('formulario_liquidacion_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('formulario_liquidacion');


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
        Schema::dropIfExists('laboratorio_ensayo');
    }
}
