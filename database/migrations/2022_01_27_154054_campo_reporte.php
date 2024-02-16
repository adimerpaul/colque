<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CampoReporte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campo_reporte', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('codigo', 100);
            $table->boolean('visible')->default(true);

            $table->unsignedBigInteger('tipo_reporte_id')->comment("clave foranea de tipo_reporte");
            $table->foreign('tipo_reporte_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('tipo_reporte');

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
        Schema::dropIfExists('campo_reporte');
    }
}
