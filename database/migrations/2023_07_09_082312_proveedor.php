<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio.proveedor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nit', 20)->unique()->comment("nit de proveedor");
            $table->string('nombre', 100)->comment("nombre del proveedor");
            $table->string('observacion', 300)->nullable()->comment("observacion");

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
        Schema::dropIfExists('laboratorio.proveedor');
    }
}
