<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BasePlomoPlata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_plomo_plata', function (Blueprint $table) {
            $table->id();
            $table->integer('lme_pb_minimo');
            $table->integer('lme_pb_maximo');

            $table->integer('base');

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
        Schema::dropIfExists('base_plomo_plata');
    }
}
