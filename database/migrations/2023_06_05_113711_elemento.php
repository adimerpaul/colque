<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Elemento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.elemento', function (Blueprint $table) {
            $table->id();
            $table->string('simbolo', 10)->unique()->comment("simbolo del elemento");
            $table->string('nombre', 20)->comment("nombre del elemento");
            $table->string('unidad', 10)->nullable()->comment("unidad por defecto del elemento para el laboratorio");
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
        Schema::dropIfExists('elemento');
    }
}
