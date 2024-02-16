<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TerminosPlomoPlata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminos_plomo_plata', function (Blueprint $table) {
            $table->id();
            $table->decimal('ley_minima', 8, 2);
            $table->decimal('ley_maxima', 8, 2);
            $table->integer('maquila');
            $table->decimal('costo_refinacion', 8, 2);
            $table->decimal('transporte', 8, 2);
            $table->decimal('rollback', 8, 2);

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
        Schema::dropIfExists('terminos_plomo_plata');
    }
}
