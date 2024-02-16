<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('identificacion_tributaria');
            $table->string('razon_social', 150);
            $table->string('direccion')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('celular', 30)->nullable();
            $table->boolean('alta')->default(true);
            $table->string('logo')->nullable();
            $table->string('membrete')->nullable();

            $table->integer('cantidad_usuario')->default(2)->comment('cantidad de usuarios por defectto, se aumenta de acuerdo a licencia y costo por usuario extra');

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
        Schema::dropIfExists('empresa');
    }
}
