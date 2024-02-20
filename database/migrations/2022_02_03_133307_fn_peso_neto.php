<?php

use Illuminate\Database\Migrations\Migration;

class FnPesoNeto extends Migration
{
    public function up()
    {
        DB::statement("CREATE OR REPLACE FUNCTION fn_peso_neto_seco(fecha VARCHAR) RETURNS FLOAT AS $$
                        DECLARE sumaTotal numeric(10,2);
                        BEGIN
                            SELECT  sum(formulario_liquidacion.peso_seco) into sumaTotal
                            FROM formulario_liquidacion
                            WHERE formulario_liquidacion.estado IN ('Liquidado', 'Vendido', 'Composito' ) AND to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')=fecha
                            GROUP BY DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion);

                            RETURN sumaTotal;
                        END; $$
                        LANGUAGE PLPGSQL;
        ");
    }

    public function down()
    {
        Schema::dropIfExists('fn_peso_neto_seco');
    }
}
