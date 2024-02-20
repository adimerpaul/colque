<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ci', 20)->unique();
            $table->string('ci_add', 10)->nullable();
            $table->string('expedido', 30);
            $table->string('nombre_completo', 50);
            $table->string('celular', 30)->nullable();


            $table->unsignedInteger('empresa_id')->comment("clave foranea de empresa");
            $table->foreign('empresa_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('empresa');
            $table->integer('biometrico')->nullable();
            //planilla
            $table->string('nacionalidad',20);
            $table->date('fecha_nacimiento');
            $table->string('cargo',30);
            $table->date('fecha_ingreso');
            $table->unsignedDecimal('haber_basico',10,2);
            $table->string('sexo',2);
            $table->string('tipo_contrato',50);
            

              
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
        Schema::dropIfExists('personal');
    }
}
