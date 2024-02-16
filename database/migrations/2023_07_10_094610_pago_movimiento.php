<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagoMovimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.pago_movimiento', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10);
            $table->decimal('monto', 12, 2)->default(0);
            $table->string('glosa', 300)->nullable();
            $table->boolean('es_cancelado')->default(false);
            $table->enum('tipo', ['Ingreso', 'Egreso'])->default('Ingreso')->comment("tipo de movimiento");
            $table->enum('metodo', [\App\Patrones\TipoPago::CuentaBancaria, \App\Patrones\TipoPago::Efectivo])->nullable()->comment("metodo");
            $table->boolean('alta')->default(true);
            $table->string('factura', 50)->nullable();
            $table->unsignedInteger('anio');
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
        Schema::dropIfExists('laboratorio.pago_movimiento');
    }
}
