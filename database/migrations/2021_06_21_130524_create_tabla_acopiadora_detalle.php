<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablaAcopiadoraDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabla_acopiadora_detalle', function (Blueprint $table) {
            $table->id();
            $table->double('cotizacion');
            $table->double('l_0')->nullable();
            $table->double('l_5')->nullable();
            $table->double('l_10')->nullable();
            $table->double('l_15')->nullable();
            $table->double('l_20')->nullable();
            $table->double('l_25')->nullable();
            $table->double('l_30')->nullable();
            $table->double('l_35')->nullable();
            $table->double('l_40')->nullable();
            $table->double('l_45')->nullable();
            $table->double('l_50')->nullable();
            $table->double('l_55')->nullable();
            $table->double('l_60')->nullable();
            $table->double('l_65')->nullable();
            $table->double('l_70')->nullable();

            $table->unsignedBigInteger('tabla_acopiadora_id')->comment("clave foranea de tabla_acopiadora");
            $table->foreign('tabla_acopiadora_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('tabla_acopiadora');
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
        Schema::dropIfExists('tabla_acopiadora_detalle');
    }
}
