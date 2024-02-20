<?php

namespace App\Http\Controllers\DataXML;

use App\Http\Controllers\LeyendaController;
use App\Patrones\ActividadEconomica;
use App\Patrones\Env;

class DataXMLNotaCreditoDebito
{
    use DataXML;

    private $codigoDocumentoSector;
    private $codigoPuntoVenta;

    public function __construct($codigoDocumentoSector, $codigoPuntoVenta)
    {
        $this->codigoDocumentoSector = $codigoDocumentoSector;
        $this->codigoPuntoVenta = $codigoPuntoVenta;
    }

    function makeHead($item, $cuf, $cufd)
    {
        return [
            "nitEmisor" => Env::nit,
            "razonSocialEmisor" => Env::razonSocial,
            "municipio" => Env::municipio,
            "telefono" => Env::telefono,
            "numeroNotaCreditoDebito" => $item->numero,
            "cuf" => $cuf,
            "cufd" => $cufd,
            "codigoSucursal" => Env::codigoSucursal,
            "direccion" => Env::direccion,
            "codigoPuntoVenta" => $this->codigoPuntoVenta,
            "fechaEmision" => date("Y-m-d\TH:i:s.v", time()),
            "nombreRazonSocial" => trim($item->facturaOriginal->numero_documento) == '' || trim($item->facturaOriginal->numero_documento) == "0" ? "Control Tributario" : str_replace("&","&amp;",trim($item->facturaOriginal->razon_social)),
            "codigoTipoDocumentoIdentidad" => strlen(trim($item->numero_documento)) > 8 ? 5 : 1,
            "numeroDocumento" => trim($item->facturaOriginal->numero_documento) == '' || trim($item->facturaOriginal->numero_documento) == "0" ? "99002" : trim($item->facturaOriginal->numero_documento),
            "complemento" => null,
            "codigoCliente" => trim($item->facturaOriginal->codigo_cliente) === '' ? trim($item->facturaOriginal->numero_documento) : $item->facturaOriginal->codigo_cliente,
            "numeroFactura" => $item->facturaOriginal->numero_factura,
            "numeroAutorizacionCuf" => $item->facturaOriginal->cuf,
            "fechaEmisionFactura" => date("Y-m-d\TH:i:s.v", strtotime($item->facturaOriginal->fecha_emision)),
            "montoTotalOriginal" => $item->facturaOriginal->monto_total,
            "montoTotalDevuelto" => $item->total,
            "montoDescuentoCreditoDebito" => null,
            "montoEfectivoCreditoDebito" => round(($item->total * 0.13), 2),
            "codigoExcepcion" => Env::codigoExcepcion,
            "leyenda" => LeyendaController::getLeyenda(ActividadEconomica::SujetoAiva),
            "usuario" => "PBaptista",//todo cambiar por usuario logueado
            "codigoDocumentoSector" => $this->codigoDocumentoSector,
        ];
    }

    function makeDetails($item)
    {
        $detalles = [];
        foreach ($item->detalleDocumentoAjustes as $detalle) {
            $detalles[] = [
                "actividadEconomica" => ActividadEconomica::SujetoAiva,
                "codigoProductoSin" => Env::codigoProductoSin,
                "codigoProducto" => $detalle->codigo,
                "descripcion" => $detalle->descripcion,
                "cantidad" => $detalle->cantidad,
                "unidadMedida" => Env::unidadMedidaOtro,
                "precioUnitario" => $detalle->precio_unitario,
                "montoDescuento" => null,
                "subTotal" => $detalle->precio_unitario,
                "codigoDetalleTransaccion" => 1
            ];
        }
        foreach ($item->detalleDocumentoAjustes as $detalle) {
            if ($detalle->es_seleccionado == true) {
                $detalles[] = [
                    "actividadEconomica" => ActividadEconomica::SujetoAiva,
                    "codigoProductoSin" => Env::codigoProductoSin,
                    "codigoProducto" => $detalle->codigo,
                    "descripcion" => $detalle->descripcion,
                    "cantidad" => $detalle->cantidad,
                    "unidadMedida" => Env::unidadMedidaOtro,
                    //"precioUnitario" => $detalle->monto_final > 0 ? $detalle->monto_final : $detalle->precio_unitario,
                    "precioUnitario" => $detalle->monto_final,
                    "montoDescuento" => null,
                    //"subTotal" => $detalle->monto_final > 0 ? $detalle->monto_final : $detalle->precio_unitario,
                    "subTotal" => $detalle->monto_final,
                    "codigoDetalleTransaccion" => 2
                ];
            }
        }
        return $detalles;
    }
}
