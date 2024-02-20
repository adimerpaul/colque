<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HistorialVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_venta', function (Blueprint $table) {
            $table->id();
            $table->string('accion', 100)->nullable()->comment("estado de la accion");
            $table->string('observacion')->nullable()->comment("observacion");
            $table->unsignedBigInteger('venta_id')->comment("clave foranea de venta");
            $table->foreign('venta_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('venta');

            //relacion logica con usuario
            $table->unsignedBigInteger('users_id')->nullable()->comment("clave foranea de usuario");

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
        Schema::dropIfExists('historial_venta');
    }
}
