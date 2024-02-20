<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Retencion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retencion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('motivo', 100)->nullable();
            $table->decimal('monto', 8, 2);
            $table->boolean('es_cancelado')->default(false);

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
        //
    }
}
