<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CuentaCobrar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuenta_cobrar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('motivo', 100)->nullable();
            $table->decimal('monto', 8, 2);
            $table->boolean('es_cancelado')->default(false);
            $table->string('tipo', 10)->default('Ingreso');

//relacion polimorfica con FormularioLiquidacion o Cliente
            $table->unsignedInteger('origen_id')->comment("origen de relacion");
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
        //
    }
}
