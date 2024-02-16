<?php

namespace App\Http\Controllers\DataXML;

use App\Patrones\ActividadEconomica;
use App\Patrones\Env;

class DataXMLLibreConsignacion
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
            "nombreRazonSocial" => "SIN RAZON SOCIAL",
            "codigoTipoDocumentoIdentidad" => 5,
            "numeroDocumento" => 0,
            "codigoCliente" => $xml->nit,
            "codigoPais" => 38,
            "puertoDestino" => $xml->puerto_destino,
            "codigoMetodoPago" => Env::codigoMetodoPago,
            "numeroTarjeta" => null,
            "montoTotal" => $xml->monto_total * $xml->tipo_cambio,
            "montoTotalSujetoIva" => 0,
            "codigoMoneda" => Env::codigoMonedaDolar,
            "tipoCambio" => $xml->tipo_cambio,
            "montoTotalMoneda" => $xml->monto_total,
            "codigoExcepcion" => Env::codigoExcepcion,
            "cafc" => null,
            "leyenda" => "Ley N° 453: Puedes acceder a la reclamación cuando tus derechos han sido vulnerados.",
            "usuario" => "Kevin",//todo cambiar por usuario logueado
            "codigoDocumentoSector" => 4
        ];
    }

    function makeDetails($xml)
    {
        foreach ($xml as $detalle) {
            $detalle = (object)$detalle;
            $detalles[] = [
                "actividadEconomica" => ActividadEconomica::LibreConsignacion,
                "codigoProductoSin" => Env::codigoProductoSinLibre,
                "codigoProducto" => $detalle->codigo_producto,
                "codigoNandina" => $detalle->nandina,
                "descripcion" => $detalle->descripcion,
                "unidadMedida" => Env::unidadMedidakILOGRAMO,
                "cantidad" => $detalle->cantidad,
                "precioUnitario" => $detalle->precio_unitario,
                "montoDescuento" => 0,
                "subTotal" => $detalle->subtotal
            ];
        }
        return $detalles;
    }
}
