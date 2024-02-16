<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.movimiento', function (Blueprint $table) {
            $table->id();
          //  $table->string('tipo')->nullable()->comment("tipo de movimiento");

            $table->unsignedInteger('proveedor_id')->nullable()->comment("clave foranea de proveedor");
            $table->foreign('proveedor_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('proveedor');

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
        Schema::dropIfExists('laboratorio.movimiento');
    }
}
