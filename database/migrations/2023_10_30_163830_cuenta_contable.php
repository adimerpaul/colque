<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CuentaContable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.cuenta_contable', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 100);
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
        Schema::dropIfExists('laboratorio.cuenta_contable');
    }
}
