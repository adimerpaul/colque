<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AccesoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acceso', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha')->default(date('Y-m-d H:i:s'));
            $table->ipAddress('ip')->nullable();

            $table->unsignedBigInteger('users_id')->comment("clave foranea de users");
            $table->foreign('users_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('users');

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
        Schema::dropIfExists('acceso');
    }
}
