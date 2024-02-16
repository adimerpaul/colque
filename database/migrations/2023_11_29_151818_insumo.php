<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Insumo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.insumo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique()->comment("nombre de insumo");
            $table->string('unidad', 20)->comment("unidad por defecto del insumo");
            $table->decimal('cantidad_minima', 10, 2);
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
        Schema::dropIfExists('laboratorio.insumo');
    }
}
