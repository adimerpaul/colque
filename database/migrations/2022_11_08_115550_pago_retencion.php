<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PagoRetencion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_retencion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('motivo', 100)->nullable();
            $table->decimal('monto', 8, 2);
            $table->boolean('es_cancelado')->default(false);
            $table->string('tipo', 10)->default('Egreso');

            $table->string('retenciones_id',  255);
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
