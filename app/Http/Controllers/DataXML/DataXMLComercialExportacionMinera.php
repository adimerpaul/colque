<?php

namespace App\Http\Controllers\DataXML;

use App\Patrones\ActividadEconomica;
use App\Patrones\Env;

class DataXMLComercialExportacionMinera
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
            "nombreRazonSocial" => str_replace("&","&amp;",trim($xml->nombre_razon_social)),
            "direccionComprador" => $xml->direccion_comprador,
            "codigoTipoDocumentoIdentidad" =>  5,
            "numeroDocumento" => $xml->numero_documento,
            "complemento" => null,
            "ruex" => $xml->ruex,
            "nim" => $xml->nim,
            "concentradoGranel" => $xml->concentrado_granel,
            "origen" => $xml->origen,
            "puertoTransito" => $xml->puerto_transito,
            "puertoDestino" => $xml->puerto_destino,
            "paisDestino" => $xml->pais_destino,
            "incoterm" => $xml->incoterm,
            "codigoCliente" => $xml->numero_documento,
            "montoTotalSujetoIva" => 0,
            "codigoMoneda" => Env::codigoMonedaDolar,
            "tipoCambio" => $xml->tipo_cambio,
            "tipoCambioANB" => $xml->tipo_cambio,
            "numeroLote" => $xml->numero_lote,
            "kilosNetosHumedos" => $xml->kilos_netos_humedos,
            "humedadPorcentaje" => $xml->humedad_porcentaje,
            "humedadValor" => $xml->humedad_valor,
            "mermaPorcentaje" => $xml->merma_porcentaje,
            "mermaValor" => round($xml->merma_valor, 2),
            "kilosNetosSecos" => round($xml->kilos_netos_secos),
            "codigoMetodoPago" => Env::codigoMetodoPago,
            "numeroTarjeta" => null,
            "montoTotal" => round(($xml->monto_total - round($xml->gastos_realizacion, 2)) * $xml->tipo_cambio, 2),
            "montoTotalMoneda" => round($xml->monto_total - $xml->gastos_realizacion, 2),
            "gastosRealizacion" => round($xml->gastos_realizacion, 2),
            "otrosDatos" => null,
            "descuentoAdicional" => null,
            "codigoExcepcion" => 1,
            "cafc" => null,
            "leyenda" => "Ley N° 453: Puedes acceder a la reclamación cuando tus derechos han sido vulnerados.",
            "usuario" => "Kevin",//todo cambiar por usuario logueado
            "codigoDocumentoSector" => 20
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
                "descripcionLeyes" => $detalle->descripcion_leyes,
                "cantidadExtraccion" => 10,
                "cantidad" => round($detalle->cantidad, 5),
                "unidadMedidaExtraccion" => Env::unidadMedidakILOGRAMO,
                "unidadMedida" => Env::unidadMedidakILOGRAMO,
                "precioUnitario" => round($detalle->precio_unitario, 2),
                "montoDescuento" => null,
                "subTotal" => round( round($detalle->cantidad, 5) * round($detalle->precio_unitario, 2), 5),
            ];
        }
        return $detalles;
    }
}
