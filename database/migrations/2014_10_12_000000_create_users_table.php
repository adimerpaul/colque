<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('rol', [\App\Patrones\Rol::SuperAdmin, \App\Patrones\Rol::Administrador, \App\Patrones\Rol::Pesaje, \App\Patrones\Rol::Comercial, \App\Patrones\Rol::Contabilidad]);
            $table->boolean('alta')->default(true);

            $table->date('ultimo_cambio_password')->nullable();

            $table->unsignedInteger('personal_id')->comment("clave foranea de personal");
            $table->foreign('personal_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('personal');

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
