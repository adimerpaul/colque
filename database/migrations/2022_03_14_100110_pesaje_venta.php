<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PesajeVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesaje_venta', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_pesaje');
            $table->decimal('peso_bruto_humedo', 10, 2)->default(0);
            $table->decimal('peso_neto_humedo', 10, 2)->default(0);

            $table->unsignedBigInteger('venta_id')->comment("clave foranea de venta");
            $table->foreign('venta_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('venta');

            $table->unsignedBigInteger('chofer_id')->nullable()->comment("clave foranea de chofer");
            $table->foreign('chofer_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('chofer');

            $table->unsignedBigInteger('vehiculo_id')->nullable()->comment("clave foranea de vehiculo");
            $table->foreign('vehiculo_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('vehiculo');

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
        Schema::dropIfExists('pesaje_venta');
    }
}
