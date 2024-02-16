<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ActivoFijo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activo.activo_fijo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo', 10)->unique();
            $table->string('descripcion', 500);
            $table->string('factura', 30);
            $table->string('area_trabajo', 50);
            $table->string('estado', 30);
            $table->unsignedInteger('cantidad');
            $table->string('unidad_medida', 10);
            $table->date('fecha_adquisicion')->nullable();
            $table->unsignedDecimal('valor_unitario', 10, 2);
            //$table->boolean('alta')->default(true);
            $table->string('observacion', 300)->nullable();

            $table->unsignedInteger('tipo_id')->comment("clave foranea de tipo");
            $table->foreign('tipo_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('activo.tipo');

            $table->unsignedInteger('personal_id')->nullable()->comment("clave foranea de personal");
            $table->foreign('personal_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('personal');

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
        Schema::dropIfExists('activo.activo_fijo');
    }
}
