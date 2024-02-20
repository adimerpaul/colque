<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VentaMineral extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta_mineral', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venta_id')->comment("clave foranea de venta");
            $table->foreign('venta_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('venta');

            $table->unsignedBigInteger('mineral_id')->comment("clave foranea de mineral");
            $table->foreign('mineral_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('mineral');

            $table->unsignedDecimal('peso_fino',12, 2)->default(0);
            $table->unsignedDecimal('descripcion_leyes',8, 2)->default(0);
            $table->unsignedDecimal('cantidad_extraccion',12, 2)->default(0);
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
        Schema::dropIfExists('venta_mineral');
    }
}
