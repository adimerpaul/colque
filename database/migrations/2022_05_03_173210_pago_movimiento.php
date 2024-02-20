<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PagoMovimiento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_movimiento', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10)->nullable();
            $table->decimal('monto', 12, 2)->default(0);

            $table->string('glosa', 100)->nullable();
            $table->unsignedInteger('proveedor_id')->nullable()->comment("clave foranea de proveedor");
            $table->foreign('proveedor_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('proveedor');

//relacion polimorfica con FormularioLiquidacion o Movimiento
            $table->unsignedInteger('origen_id')->comment("origin de relacion");
            $table->string('origen_type')->comment("origen de relacion del modelo");

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
        Schema::dropIfExists('pago_movimiento');
    }
}
