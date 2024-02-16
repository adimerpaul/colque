<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Movimiento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento', function (Blueprint $table) {
            $table->id();
            $table->string('motivo', 100)->nullable();
            $table->enum('tipo', ['Ingreso', 'Egreso'])->comment("tipo de movimiento");

            $table->decimal('total', 12, 2)->default(0);
            $table->boolean('es_cancelado')->default(false);

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
        Schema::dropIfExists('movimiento');
    }
}
