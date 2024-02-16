<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HistorialCuentaCobrar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_cuenta_cobrar', function (Blueprint $table) {
            $table->id();
            $table->string('accion', 100)->nullable()->comment("estado de la accion");
            $table->string('observacion')->nullable()->comment("observacion");
            $table->unsignedInteger('origen_id')->comment("origen de relacion");
            $table->string('origen_type')->comment("origen de relacion del modelo");

            $table->unsignedBigInteger('users_id')->nullable()->comment("clave foranea de usuario");
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
        Schema::dropIfExists('historial_cuenta_cobrar');
    }
}
