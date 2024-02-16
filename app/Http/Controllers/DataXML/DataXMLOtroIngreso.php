<?php

namespace App\Http\Controllers\DataXML;

use App\Patrones\ActividadEconomica;
use App\Patrones\Env;

class DataXMLOtroIngreso
{
    use DataXML;

    private $codigoDocumentoSector;
    private $codigoPuntoVenta;

    public function __construct($codigoDocumentoSector, $codigoPuntoVenta)
    {
        $this->codigoDocumentoSector = $codigoDocumentoSector;
        $this->codigoPuntoVenta = $codigoPuntoVenta;
    }

    function makeHead($xml, $cuf, $cufd)
    {
        return [
            "nitEmisor" => Env::nit,
            "razonSocialEmisor" => Env::razonSocial,
            "municipio" => Env::municipio,
            "telefono" => Env::telefono,
            "numeroFactura" => (int)$xml->pag_nren,
            "cuf" => $cuf,
            "cufd" => $cufd,
            "codigoSucursal" => Env::codigoSucursal,
            "direccion" => Env::direccion,
            "codigoPuntoVenta" => $this->codigoPuntoVenta,
            "fechaEmision" => date("Y-m-d\TH:i:s.v", strtotime("$xml->pag_fpag1")),
            "nombreRazonSocial" => trim($xml->pag_rucnit) == "" ? "Control Tributario" :str_replace("&","&amp;",trim($xml->pag_apno)) ,
            "codigoTipoDocumentoIdentidad" => strlen(trim($xml->pag_rucnit)) > 8 ? 5 : 1,
            "numeroDocumento" => trim($xml->pag_rucnit) == "" ? "99002" : trim($xml->pag_rucnit),
            "complemento" => strlen(trim($xml->pag_rucnit)) > 8 ? null : "",
            "codigoCliente" => trim($xml->pag_matr) === '' ? trim($xml->pag_rucnit) : $xml->pag_matr . $xml->pag_dive,
            "codigoMetodoPago" => Env::codigoMetodoPago,
            "numeroTarjeta" => null,
            "montoTotal" => $xml->pag_valo,
            "montoTotalSujetoIva" => $xml->pag_valo,
            "codigoMoneda" => Env::codigoMoneda,
            "tipoCambio" => Env::codigoMoneda,
            "montoTotalMoneda" => $xml->pag_valo,
            "montoGiftCard" => null,
            "descuentoAdicional" => null,
            "codigoExcepcion" => Env::codigoExcepcion,
            "cafc" => null,
            "leyenda" => $xml->leyenda,
            "usuario" => "PBaptista",//todo cambiar por usuario logueado
            "codigoDocumentoSector" => $this->codigoDocumentoSector
        ];
    }

    function makeDetails($xml)
    {
        foreach ($xml as $detalle) {
            $detalle = (object)$detalle;
            $detalles[] = [
                "actividadEconomica" => ActividadEconomica::SujetoAiva,
                "codigoProductoSin" => Env::codigoProductoSin,
                "codigoProducto" => $detalle->otr_nue,
                "descripcion" => $detalle->otr_conc,
                "cantidad" => Env::cantidad,
                "unidadMedida" => Env::unidadMedidaOtro,
                "precioUnitario" => $detalle->otr_vlpg,
                "montoDescuento" => null,
                "subTotal" => $detalle->otr_vlpg,
                "numeroSerie" => null,
                "numeroImei" => null
            ];
        }
        return $detalles;
    }
}
