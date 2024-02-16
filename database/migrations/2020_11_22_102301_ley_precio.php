<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Patrones\UnidadLey;
class LeyPrecio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ley_precio', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('valor', 3, 2)->comment("valor de ley");
            $table->enum('diaria', [UnidadLey::Porcentaje, UnidadLey::GramosPorTonelada, UnidadLey::Decimarco, UnidadLey::PartesPorMillon])->default(UnidadLey::Porcentaje)->comment("tipo de unidad de la ley");

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
        Schema::dropIfExists('ley');
    }
}
