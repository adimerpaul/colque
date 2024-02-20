<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DescuentoBonificacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descuento_bonificacion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100)->comment("nombre del pago");
            $table->decimal('valor', 5, 2)->comment("valor de pagos");
            $table->enum('en_funcion', \App\Patrones\Fachada::enFunciones())->nullable()->comment("En funcion de");
            $table->enum('unidad', [\App\Patrones\UnidadDescuentoBonificacion::Constante, \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, \App\Patrones\UnidadDescuentoBonificacion::DolarPorTonelada])->default(\App\Patrones\UnidadDescuentoBonificacion::Constante)->comment("tipo de unidad de pago");
            $table->enum('tipo', ["Descuento", "Bonificacion", "Retencion"])->default("Bonificacion")->comment("tipo de pago");
            $table->boolean('alta')->default(true);
            $table->unsignedInteger('cooperativa_id')->comment("clave foranea de cooperativa");
            $table->foreign('cooperativa_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('cooperativa');

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
        Schema::dropIfExists('descuento_bonificacion');
    }
}
