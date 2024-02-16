<?php

use Illuminate\Database\Migrations\Migration;

class FnValorNeto extends Migration
{
    public function up()
    {
        DB::statement("CREATE OR REPLACE FUNCTION fn_valor_neto_pagable(fechaVar VARCHAR) RETURNS FLOAT AS $$
                        DECLARE sumaTotal numeric(10,2);
                        BEGIN
                            SELECT  sum(formulario_liquidacion.valor_neto_pagable) into sumaTotal
                            FROM formulario_liquidacion
                            WHERE formulario_liquidacion.estado IN ('Liquidado', 'Vendido', 'Composito' ) AND to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM') = fechaVar
                            GROUP BY DATE_TRUNC('month',formulario_liquidacion.fecha_liquidacion);
                        RETURN sumaTotal;
                        END; $$
                        LANGUAGE PLPGSQL;
        ");
    }

    public function down()
    {
        Schema::dropIfExists('fn_valor_neto_pagable');

    }
}
