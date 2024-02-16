<?php

use Illuminate\Database\Migrations\Migration;

class FnNetoVentaProducto extends Migration
{
    public function up()
    {
        DB::statement("CREATE OR REPLACE FUNCTION fn_neto_venta_producto(fechaVar VARCHAR, letra VARCHAR) RETURNS FLOAT AS $$
                        DECLARE sumaTotal numeric(10,2);
                        BEGIN
                            SELECT  sum(formulario_liquidacion.neto_venta) into sumaTotal
                            FROM formulario_liquidacion
                            WHERE formulario_liquidacion.estado IN ('Liquidado', 'Vendido', 'Composito' ) AND to_char(formulario_liquidacion.fecha_liquidacion, 'YYYY-MM') = fechaVar AND formulario_liquidacion.letra=letra;

                        RETURN sumaTotal;
                        END; $$
                        LANGUAGE PLPGSQL;
        ");
    }

    public function down()
    {
        Schema::dropIfExists('fn_neto_venta_producto');

    }
}
