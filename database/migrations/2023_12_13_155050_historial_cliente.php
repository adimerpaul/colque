<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HistorialCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_cliente', function (Blueprint $table) {
            $table->id();
            $table->string('valores_antiguos', 1000)->nullable();
            $table->string('valores_nuevos', 1000);
            $table->string('tipo', 20);
            $table->unsignedBigInteger('cliente_id')->comment("clave foranea de cliente");
            $table->foreign('cliente_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('cliente');

            //relacion logica con usuario
            $table->unsignedBigInteger('registrado_id')->nullable()->comment("clave foranea de personal");

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
        Schema::dropIfExists('historial_cliente');
    }
}
