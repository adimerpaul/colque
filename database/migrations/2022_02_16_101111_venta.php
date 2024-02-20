<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Venta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->id();
            $table->string('sigla', 5)->default('CMV');
            $table->integer('numero_lote');
            $table->string('letra', 2)->nullable();
            $table->integer('anio' );
            $table->string('url_documento', 50)->nullable();
            $table->decimal('transporte', 10, 2)->nullable();
            $table->decimal('otros_costos', 10, 2)->nullable();
            $table->string('producto', 50);

            $table->string('lote_comprador', 20)->nullable();
            //$table->date('fecha_entrega');
            $table->string('tipo_transporte', 50)->nullable();
            $table->string('trayecto', 50)->nullable();
            $table->string('tranca', 50)->nullable();
            $table->string('municipio', 200)->nullable();
            $table->date('fecha_venta')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->date('fecha_cobro')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('empaque', \App\Patrones\Fachada::getEmpaques())->default(\App\Patrones\Empaque::AGranel);
            $table->enum('estado', \App\Patrones\Fachada::getEstadosVentas())->default(\App\Patrones\EstadoVenta::EnProceso);

            $table->unsignedInteger('comprador_id')->comment("clave foranea de comprador");
            $table->foreign('comprador_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('comprador');
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
        Schema::dropIfExists('venta');
    }
}
