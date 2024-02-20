<?php

use Illuminate\Database\Migrations\Migration;

class FnPesoSecoProducto extends Migration
{
    public function up()
    {
        DB::statement("CREATE OR REPLACE FUNCTION fn_peso_seco_producto(fecha VARCHAR, letraVar VARCHAR) RETURNS FLOAT AS $$
                        DECLARE sumaTotal numeric(10,2);
                        BEGIN
                            SELECT sum(formulario_liquidacion.peso_seco) into sumaTotal
                            FROM formulario_liquidacion
                            WHERE formulario_liquidacion.estado IN ('Liquidado', 'Vendido', 'Composito' )
                                AND to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')=fecha
                                AND formulario_liquidacion.letra=letraVar;

                            RETURN sumaTotal;
                        END; $$
                        LANGUAGE PLPGSQL;
        ");
    }

    public function down()
    {
        Schema::dropIfExists('fn_peso_seco_producto');
    }
}
