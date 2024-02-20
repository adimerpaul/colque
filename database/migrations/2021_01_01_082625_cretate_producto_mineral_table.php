<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CretateProductoMineralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_mineral', function (Blueprint $table) {
            $table->id();
            $table->boolean('es_penalizacion')->default(false);

            $table->unsignedBigInteger('producto_id')->comment("clave foranea de producto");
            $table->foreign('producto_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('producto');

            $table->unsignedBigInteger('mineral_id')->comment("clave foranea de mineral");
            $table->foreign('mineral_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('mineral');

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
        Schema::dropIfExists('producto_mineral');
    }
}
