<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MovimientoCatalogo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_catalogo', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 100);
            $table->boolean('es_lote')->default(false);
            $table->enum('tipo', ['Ingreso', 'Egreso'])->default('Ingreso');


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
        Schema::dropIfExists('movimiento_catalogo');
    }
}
