<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DetalleActivo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activo.detalle_activo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('factura',50)->nullable();
            $table->unsignedInteger('cantidad');
            $table->string('descripcion', 300);
            $table->unsignedDecimal('valor_unitario', 10, 2);
            $table->unsignedInteger('activo_fijo_id')->comment("clave foranea de activo fijo");
            $table->foreign('activo_fijo_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('activo.activo_fijo');

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
        Schema::dropIfExists('activo.detalle_activo');
    }
}
