<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaboratorioPrecio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio_precio', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('monto', 7, 2)->comment("precio del analisis del producto");

            $table->unsignedBigInteger('producto_id')->comment("clave foranea de producto");
            $table->foreign('producto_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('producto');

            $table->unsignedBigInteger('laboratorio_quimico_id')->comment("clave foranea de laboratorio_quimico");
            $table->foreign('laboratorio_quimico_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('laboratorio_quimico');

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
        Schema::dropIfExists('laboratorio_precio');
    }
}
