<?php

namespace App\Http\Controllers\DataXML;

use App\Patrones\ActividadEconomica;
use App\Patrones\Env;

class DataXMLCompraVenta
{
    use DataXML;

    private $codigoDocumentoSector;
    private $codigoPuntoVenta;

    public function __construct($codigoDocumentoSector, $codigoPuntoVenta)
    {
        $this->codigoDocumentoSector = $codigoDocumentoSector;
        $this->codigoPuntoVenta = $codigoPuntoVenta;
    }

    function makeHead($xml, $cuf, $cufd, $numeroFactura, $fechaActual)
    {
        return [
            "nitEmisor" => Env::nit,
            "razonSocialEmisor" => Env::razonSocial,
            "municipio" => Env::municipio,
            "telefono" => Env::telefono,
            "numeroFactura" => $numeroFactura,
            "cuf" => $cuf,
            "cufd" => $cufd,
            "codigoSucursal" => Env::codigoSucursal,
            "direccion" => Env::direccion,
            "codigoPuntoVenta" => $this->codigoPuntoVenta,
            "fechaEmision" => $fechaActual,
            "nombreRazonSocial" => $xml->nombre_razon_social,
            "codigoTipoDocumentoIdentidad" => strlen(trim($xml->numero_documento)) > 8 ? 5 : 1,
            "numeroDocumento" => $xml->numero_documento,
            "complemento" => null,
            "codigoCliente" => $xml->numero_documento,
            "codigoMetodoPago" => Env::codigoMetodoPago,
            "numeroTarjeta" => null,
            "montoTotal" => $xml->monto_total,
            "montoTotalSujetoIva" => $xml->monto_total,
            "codigoMoneda" => Env::codigoMoneda,
            "tipoCambio" => Env::codigoMoneda,
            "montoTotalMoneda" => $xml->monto_total,
            "montoGiftCard" => null,
            "descuentoAdicional" => null,
            "codigoExcepcion" => Env::codigoExcepcion,
            "cafc" => null,
            "leyenda" => "Ley N° 453: Puedes acceder a la reclamación cuando tus derechos han sido vulnerados.",
            "usuario" => "Kevin",//todo cambiar por usuario logueado
            "codigoDocumentoSector" => 1
        ];
    }

    function makeDetails($xml)
    {
        foreach ($xml as $detalle) {
            $detalle = (object)$detalle;
            $detalles[] = [
                "actividadEconomica" => ActividadEconomica::LibreConsignacion,
                "codigoProductoSin" => Env::codigoProductoSin,
                "codigoProducto" => $detalle->codigo_producto,
                "descripcion" => $detalle->descripcion,
                "cantidad" => $detalle->cantidad,
                "unidadMedida" => Env::unidadMedidaOtro,
                "precioUnitario" => $detalle->precio_unitario,
                "montoDescuento" => null,
                "subTotal" => $detalle->subtotal,
                "numeroSerie" => null,
                "numeroImei" => null
            ];
        }
        return $detalles;
    }
}
