<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TipoPermiso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh.tipo_permiso', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->decimal('cantidad_dia', 5, 2)->nullable(); // 5 dígitos en total, 2 decimales
            $table->decimal('cantidad_hora', 5, 2)->nullable(); // 5 dígitos en total, 2 decimales
            $table->boolean('es_unico')->default(false);
            $table->string('fecha_inicio', 10);
            $table->string('fecha_final', 10);

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
        Schema::dropIfExists('rrhh.tipo_permiso');
    }
}
