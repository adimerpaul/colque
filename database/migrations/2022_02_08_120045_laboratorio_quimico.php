<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaboratorioQuimico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio_quimico', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100)->comment("nombre de laboratorio de analisis");
            $table->string('direccion', 100)->nullable()->comment("direccion de laboratorio de analisis");

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
        Schema::dropIfExists('laboratorio_quimico');
    }
}
