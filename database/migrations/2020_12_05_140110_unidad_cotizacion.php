<?php

use App\Patrones\UnidadLey;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UnidadCotizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidad_cotizacion', function (Blueprint $table) {
            $table->increments('id');

            $table->enum('diaria', [UnidadLey::Porcentaje, UnidadLey::GramosPorTonelada, UnidadLey::Decimarco, UnidadLey::PartesPorMillon])->default(UnidadLey::Porcentaje)->comment("unidad de cotizacion diaria");
            $table->enum('oficial', [UnidadLey::Porcentaje, UnidadLey::GramosPorTonelada, UnidadLey::Decimarco, UnidadLey::PartesPorMillon])->default(UnidadLey::Porcentaje)->comment("unidad de cotizacion oficial");

            $table->unsignedInteger('empresa_mineral_id')->comment("clave foranea de empresa_mineral");
            $table->foreign('empresa_mineral_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('empresa_mineral');

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
        Schema::dropIfExists('unidad_cotizacion');
    }
}
