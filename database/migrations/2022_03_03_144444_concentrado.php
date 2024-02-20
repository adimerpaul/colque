<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Concentrado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concentrado', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->nullable();
            $table->string('nombre', 50)->nullable();
            $table->decimal('peso_neto_humedo', 10, 2)->default(0);
            $table->decimal('humedad', 10, 2)->default(0);
            $table->decimal('valor_tonelada', 10, 2)->default(0);
            $table->decimal('valor_neto_venta', 10, 2)->default(0);
            $table->decimal('regalia_minera', 10, 2)->default(0);
            $table->decimal('retenciones', 10, 2)->default(0);
            $table->decimal('ley_sn', 10, 2)->default(0);
            $table->decimal('cotizacion_sn', 10, 2)->default(0);
            $table->decimal('ley_zn', 10, 2)->default(0);
            $table->decimal('cotizacion_zn', 10, 2)->default(0);
            $table->decimal('ley_ag', 10, 2)->default(0);
            $table->decimal('cotizacion_ag', 10, 2)->default(0);
            $table->decimal('ley_pb', 10, 2)->default(0);
            $table->decimal('cotizacion_pb', 10, 2)->default(0);

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
        Schema::dropIfExists('concentrado');
    }
}
