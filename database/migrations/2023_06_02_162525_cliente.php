<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.cliente', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nit', 20)->unique()->comment("nit de cliente");
            $table->string('nombre', 100)->comment("nombre del cliente");
            $table->string('celular', 15)->comment("celular del cliente");
            $table->string('direccion', 150)->comment("direccion del cliente");

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
        Schema::dropIfExists('laboratorio.cliente');
    }
}
