<?php

namespace App\Patrones;

class AccionHistorialCuenta
{
    const Registro = 'Registro de cuenta por cobrar en lote';
    const CambioCliente = 'Cambio de cliente en Lote';
    const CreacionSaldoNegativo = 'Creación de cuenta por saldo negativo';
    const CreacionPrestamo = 'Creación de cuenta por préstamo';
    const DivisionCuenta = 'Cuenta dividida';
    const Transferencia = 'Transferencia a otro lote';
}
