<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TipoPermisoPersonal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh.tipo_permiso_personal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('personal_id')->comment("clave foranea de personal");
            $table->foreign('personal_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('personal');
            $table->unsignedInteger('tipo_permiso_id')->comment("clave foranea tipo_permiso");
            $table->foreign('tipo_permiso_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('rrhh.tipo_permiso');
            $table->boolean('es_habilitado')->comment("Es un parámetro para saber si pertenece el permiso");
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
