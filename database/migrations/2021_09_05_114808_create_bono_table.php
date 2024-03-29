<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bono', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->decimal('monto', 8, 2);
            $table->string('motivo', 100);

            $table->unsignedBigInteger('formulario_liquidacion_id')->comment("clave foranea de formulario_liquidacion");
            $table->foreign('formulario_liquidacion_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('formulario_liquidacion');

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
        Schema::dropIfExists('bono');
    }
}
