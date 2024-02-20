<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Prestamo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestamo', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('monto', 8, 2);
            $table->boolean('es_cancelado')->default(false);
            $table->string('tipo', 10)->default('Egreso');

            $table->unsignedInteger('cliente_id')->nullable()->comment("clave foranea de cliente");
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
        //
    }
}
