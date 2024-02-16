<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrecioElemento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.precio_elemento', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('monto',6, 2)->default(0);

            $table->unsignedBigInteger('elemento_id')->comment("clave foranea de elemento");
            $table->foreign('elemento_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('laboratorio.elemento');

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
        Schema::dropIfExists('laboratorio.precio_elemento');
    }
}
