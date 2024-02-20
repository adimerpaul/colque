<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AnticipoVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anticipo_venta', function (Blueprint $table) {
            $table->increments('id');
            $table->string('motivo', 100)->nullable();
            $table->decimal('monto', 12, 2);
            $table->string('tipo', 20)->default('Ingreso');
            $table->boolean('es_cancelado')->default(false);

            $table->unsignedBigInteger('venta_id')->comment("clave foranea de venta");
            $table->foreign('venta_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('venta');

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
        //
    }
}
