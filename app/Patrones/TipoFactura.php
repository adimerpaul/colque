<?php

namespace App\Patrones;

class TipoFactura
{
    const CompraVenta = 'Compra Venta';
    const ExportacionMinera = 'Exportacion Minera';
    const LibreConsignacion = 'Libre Consignacion';


    ////
    const FacturaConDerechoACreditoFiscal = 1;
    const FacturaSinDerechoACreditoFiscal = 2;
    const DocumentoDeAjuste = 3;
    const DocumentoEquivalente = 4;
}
