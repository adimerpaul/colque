<?php

use Illuminate\Database\Migrations\Migration;

class FnComprasMes extends Migration
{
    public function up()
    {
        DB::statement("CREATE OR REPLACE FUNCTION fn_compras_por_mes(mes VARCHAR, cooperativaId int4) RETURNS INTEGER AS $$
                        DECLARE sumaTotal bigint;
                        BEGIN
                            SELECT count (*) into sumaTotal
                            FROM cliente
	                            INNER JOIN formulario_liquidacion	ON cliente.id = formulario_liquidacion.cliente_id
                            WHERE formulario_liquidacion.estado IN ('Liquidado', 'Vendido', 'Composito' )
                                AND to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM')=mes
                                AND cliente.cooperativa_id=cooperativaId;

                            RETURN sumaTotal;
                        END; $$
                        LANGUAGE PLPGSQL;
        ");
    }

    public function down()
    {

    }
}
