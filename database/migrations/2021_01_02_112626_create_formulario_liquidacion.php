<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormularioLiquidacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulario_liquidacion', function (Blueprint $table) {
            $table->id();
            $table->string('sigla', 5)->default('CM');
            $table->integer('numero_lote');
            $table->string('letra', 2)->nullable();
            $table->integer('anio' );

            $table->date('fecha_cotizacion');
            $table->date('fecha_liquidacion')->nullable();
            $table->string('producto');
            $table->date('fecha_pesaje')->nullable();
            $table->decimal('peso_bruto', 10, 2);
            $table->decimal('tara', 10, 2)->nullable();
            $table->double('merma')->default(1)->nullable();
            $table->decimal('valor_por_tonelada', 10, 2)->nullable();
            $table->decimal('peso_seco', 10, 2)->default(0);
            $table->decimal('saldo_favor', 10, 2)->default(0);

            $table->enum('estado', \App\Patrones\Fachada::getEstados())->default(\App\Patrones\Estado::EnProceso);
            $table->string('observacion')->nullable();
            $table->string('numero_tornaguia')->nullable();
            $table->string('url_documento', 2000)->nullable();
            $table->string('laboratorio')->nullable();
            $table->string('numero_analisis')->nullable();
            $table->date('fecha_analisis')->nullable();
            $table->decimal('aporte_fundacion', 8, 2)->default(0);
            $table->unsignedInteger('sacos')->nullable();
            $table->boolean('es_cancelado')->default(false);
            $table->dateTime('fecha_cancelacion')->nullable();

            $table->unsignedInteger('tipo_cambio_id')->comment("clave foranea de tipo_cambio");
            $table->foreign('tipo_cambio_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('tipo_cambio');

            $table->unsignedInteger('cliente_id')->nullable()->comment("clave foranea de cliente");
            $table->foreign('cliente_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('cliente');

            $table->unsignedBigInteger('chofer_id')->nullable()->comment("clave foranea de chofer");
            $table->foreign('chofer_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('chofer');

            $table->unsignedBigInteger('vehiculo_id')->nullable()->comment("clave foranea de vehiculo");
            $table->foreign('vehiculo_id')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->on('vehiculo');

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
        Schema::dropIfExists('formulario_liquidacion');
    }
}
