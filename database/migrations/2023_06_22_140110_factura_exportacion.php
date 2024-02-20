<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FacturaExportacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_exportacion', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_emision');
            $table->string('puerto_transito', 50)->nullable();
            $table->string('puerto_destino', 50)->nullable();
            $table->string('pais_destino', 50)->nullable();
            $table->string('incoterm', 20)->nullable();
            $table->decimal('kilos_netos_humedos', 12, 2)->default(0);
            $table->decimal('humedad_porcentaje', 12, 2)->default(0);
            $table->decimal('merma_porcentaje', 12, 2)->default(0);
            $table->decimal('gastos_realizacion', 12, 2)->default(0);

            $table->unsignedInteger('venta_id')->nullable()->comment("clave foranea de venta");
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
        Schema::dropIfExists('factura_exportacion');
    }
}
