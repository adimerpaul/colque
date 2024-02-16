<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablaAcopiadoraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabla_acopiadora', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->integer('gestion')->default(date('Y'));
            $table->string('nombre')->nullable();
            $table->double('margen')->default(0)->nullable();
            $table->boolean('es_seleccionado')->default(false);

            $table->double('cotizacion_inicial')->default(0);
            $table->double('cotizacion_final')->default(0);

            $table->double('l_0_incremental')->nullable();
            $table->double('l_0_inicial')->nullable();
            $table->double('l_5_incremental')->nullable();
            $table->double('l_5_inicial')->nullable();
            $table->double('l_10_incremental')->nullable();
            $table->double('l_10_inicial')->nullable();
            $table->double('l_15_incremental')->nullable();
            $table->double('l_15_inicial')->nullable();
            $table->double('l_20_incremental')->nullable();
            $table->double('l_20_inicial')->nullable();
            $table->double('l_25_incremental')->nullable();
            $table->double('l_25_inicial')->nullable();
            $table->double('l_30_incremental')->nullable();
            $table->double('l_30_inicial')->nullable();
            $table->double('l_35_incremental')->nullable();
            $table->double('l_35_inicial')->nullable();
            $table->double('l_40_incremental')->nullable();
            $table->double('l_40_inicial')->nullable();
            $table->double('l_45_incremental')->nullable();
            $table->double('l_45_inicial')->nullable();
            $table->double('l_50_incremental')->nullable();
            $table->double('l_50_inicial')->nullable();
            $table->double('l_55_incremental')->nullable();
            $table->double('l_55_inicial')->nullable();
            $table->double('l_60_incremental')->nullable();
            $table->double('l_60_inicial')->nullable();
            $table->double('l_65_incremental')->nullable();
            $table->double('l_65_inicial')->nullable();
            $table->double('l_70_incremental')->nullable();
            $table->double('l_70_inicial')->nullable();
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
        Schema::dropIfExists('tabla_acopiadora');
    }
}
