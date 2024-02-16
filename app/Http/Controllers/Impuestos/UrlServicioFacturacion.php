<?php

namespace App\Http\Controllers\Impuestos;

use App\Patrones\DocumentoSector;
use App\Patrones\Env;

class UrlServicioFacturacion
{
    public static function getUrlServicio($codigoDocumentoSector)
    {
        $wsdl = $carpetaBase = "";

        switch ($codigoDocumentoSector) {
            case DocumentoSector::CompraVenta:
                $wsdl = Env::url . "ServicioFacturacionCompraVenta?WSDL";
                $carpetaBase = "facturasOtrosIngresos";
                break;
            case DocumentoSector::ServiciosBasicos:
                $wsdl = Env::url . "ServicioFacturacionServicioBasico?WSDL";
                $carpetaBase = "facturasConsumos";
                break;
            case DocumentoSector::NotaCreditoDebito:
                $wsdl = Env::url . "ServicioFacturacionDocumentoAjuste?WSDL";
                $carpetaBase = "facturasCreditoDebito";
                break;
            case DocumentoSector::NotaConciliacion:
                $wsdl = Env::url . "ServicioFacturacionDocumentoAjuste?WSDL";
                $carpetaBase = "facturasConciliacion";
                break;
            case DocumentoSector::LibreConsignacion:
                $wsdl = Env::url . "ServicioFacturacionElectronica?WSDL";
                $carpetaBase = "facturasConciliacion";
                break;
            case DocumentoSector::ExportacionMineral:
                $wsdl = Env::url . "ServicioFacturacionElectronica?WSDL";
                $carpetaBase = "facturasConciliacion";
                break;

        }
        return [$wsdl, $carpetaBase];
    }
}
