<?php
namespace App\Http\Controllers\DataXML;

use App\Http\Controllers\XmlSource\CUF;
use App\Patrones\Env;

trait DataXML
{
    public function generateCUF($fechaEmision, int $numeroFactura, $codigoControl, $tipoFactura, $tipoEmision): string
    {
        return CUF::generate([
            "nit_emisor" => Env::nit,
            "fecha_hora" => date('YmdHisv', strtotime("$fechaEmision")),
            "sucursal" => Env::codigoSucursal,
            "modalidad" => Env::codigoModalidad,
            "tipo_emision" => $tipoEmision,
            "tipo_factura_documento_ajuste" => $tipoFactura,
            "tipo_documento_sector" => $this->codigoDocumentoSector,
            "numero_factura" => $numeroFactura,
            "pos" => $this->codigoPuntoVenta
        ], $codigoControl);
    }
}
