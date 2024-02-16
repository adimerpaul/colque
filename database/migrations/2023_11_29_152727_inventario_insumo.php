<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InventarioInsumo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.inventario_insumo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha');

            $table->unsignedInteger('insumo_id')->comment("clave foranea de insumo");
            $table->foreign('insumo_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('laboratorio.insumo');

            $table->decimal('cantidad', 10, 2);
            $table->enum('tipo', ['Ingreso', 'Egreso'])->default('Ingreso')->comment("tipo de movimiento");

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
        Schema::dropIfExists('laboratorio.tipo_horario_personal');
    }
}
