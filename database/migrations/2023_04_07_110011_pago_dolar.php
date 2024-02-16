<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PagoDolar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_dolar', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10)->nullable();
            $table->decimal('monto', 12, 2)->default(0);
            $table->integer('anio' );
            $table->string('glosa', 100)->nullable();
            $table->string('factura', 50)->nullable();
            $table->unsignedInteger('proveedor_id')->nullable()->comment("clave foranea de proveedor");
            $table->foreign('proveedor_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('proveedor');


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
        Schema::dropIfExists('pago_dolar');
    }
}
