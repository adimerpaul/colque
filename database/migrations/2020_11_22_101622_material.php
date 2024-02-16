<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Material extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mineral', function (Blueprint $table) {
            $table->id();
            $table->string('simbolo', 10)->unique()->comment("simbolo del mineral");
            $table->string('nombre', 20)->comment("nombre del mineral");
            $table->string('unidad_laboratorio', 10)->nullable()->comment("unidad por defecto del mineral para el laboratorio del mineral");
            $table->integer('margen_error')->default(3)->nullable()->comment("margen de error para los laboratorios del mineral para el laboratorio del mineral");
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
        Schema::dropIfExists('mineral');
    }
}
