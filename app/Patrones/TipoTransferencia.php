<?php

namespace App\Patrones;

class TipoTransferencia
{
    const CuentaBnbACaja = 'Cuenta BNB a Caja';
    const CuentaEconomicoACaja = 'Cuenta Economico a Caja';
    const CajaACuentaBnb = 'Caja a Cuenta BNB';
    const CajaACuentaEconomico = 'Caja a Cuenta Economico';
    const CuentaEconomicoACuentaBnb = 'Cuenta Economico a Cuenta BNB';
    const CuentaBnbACuentaEconomico = 'Cuenta BNB a Cuenta Economico';
}
