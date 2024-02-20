<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RangoMedicion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.rango_medicion', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 20);
            $table->unsignedDecimal('minimo',8, 2)->default(0);
            $table->unsignedDecimal('maximo',8, 2)->default(0);

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
        Schema::dropIfExists('laboratorio.rango_medicion');
    }
}
