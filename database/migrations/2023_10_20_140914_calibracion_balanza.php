<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CalibracionBalanza extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.calibracion_balanza', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 20);
            $table->unsignedDecimal('valor',8, 2)->default(0);
            $table->unsignedInteger('constante_medida_id')->comment("clave foranea de constante_medida");
            $table->foreign('constante_medida_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('laboratorio.constante_medida');
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
        Schema::dropIfExists('laboratorio.calibracion_balanza');
    }
}
