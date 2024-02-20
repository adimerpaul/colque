<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PuntosCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('punto_cliente', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor',8, 2)->default(0);
            $table->string("descripcion", 100);
            $table->unsignedBigInteger('cliente_id')->comment("clave foranea de cliente");
            $table->foreign('cliente_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('cliente');


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
        Schema::dropIfExists('punto_cliente');
    }
}
