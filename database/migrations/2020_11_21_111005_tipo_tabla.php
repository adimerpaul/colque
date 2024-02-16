<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TipoTabla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_tabla', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('valor', 10, 2)->comment("valor");
            $table->enum('tabla', [
                \App\Patrones\Tabla::Merma
            ])->default(\App\Patrones\Tabla::Merma)->comment("tipo de tabla");
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
        Schema::dropIfExists('tipo_tabla');
    }
}
