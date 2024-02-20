<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Costo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costo', function (Blueprint $table) {
            $table->id();

            $table->decimal('tratamiento', 10, 2)->default(0);
            $table->decimal('laboratorio', 10, 2)->default(0);
            $table->decimal('pesaje', 10, 2)->default(0);
            $table->decimal('comision', 10, 2)->default(0);
            $table->decimal('dirimicion', 10, 2)->default(0);

            $table->unsignedBigInteger('formulario_liquidacion_id')->comment("clave foranea de formulario_liquidacion");
            $table->foreign('formulario_liquidacion_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('formulario_liquidacion');

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
        Schema::dropIfExists('costo');
    }
}
