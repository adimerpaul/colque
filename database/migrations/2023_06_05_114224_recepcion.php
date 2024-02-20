<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Recepcion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.recepcion', function (Blueprint $table) {
            $table->id();
            $table->integer('numero');
            $table->integer('mes' );
            $table->integer('anio' );
            $table->integer('codigo' );
            $table->string('caracteristicas', 50)->default('Sobre Cerrado');
            $table->boolean('es_cancelado')->default(false);
            $table->boolean('alta')->default(true);
            $table->unsignedInteger('cliente_id')->comment("clave foranea de cliente");
            $table->foreign('cliente_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('laboratorio.cliente');
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
        Schema::dropIfExists('laboratorio.recepcion');
    }
}
