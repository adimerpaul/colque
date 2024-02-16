<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nit', 20)->unique()->comment("nit de cliente");
            $table->string('nombre', 100)->comment("nombre del cliente");
            $table->string('celular', 15)->comment("celular del cliente");
            $table->string('firma', 100)->comment("firma del cliente");
            $table->unsignedInteger('cooperativa_id')->comment("clave foranea de cooperativa");
            $table->foreign('cooperativa_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('cooperativa');
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
        Schema::dropIfExists('cliente');
    }
}
