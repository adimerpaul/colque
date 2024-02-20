<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CotizacionOficial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizacion_oficial', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha')->comment("fecha de cotizacion");
            $table->decimal('monto', 7, 2)->comment("cotizacion oficial de material");
            $table->string('unidad', 10)->nullable()->comment("unidad oficial de material");
            $table->double('alicuota_exportacion', 10, 2)->nullable()->comment("alicuota regalias mineras exportaciones");
            $table->double('alicuota_interna', 10, 2)->nullable()->comment("alicuota regalias mineras ventas internas");

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
        Schema::dropIfExists('cotizacion_oficial');
    }
}
