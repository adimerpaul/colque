<?php
namespace App\Http\Controllers\DataXML;

use App\Http\Controllers\LeyendaController;
use App\Patrones\ActividadEconomica;
use App\Patrones\Env;

class DataXMLNotaConciliacion
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
            "numeroNotaConciliacion"=>$item->numero,
            "cuf" => $cuf,
            "cufd" => $cufd,
            "codigoSucursal" => Env::codigoSucursal,
            "direccion" => Env::direccion,
            "codigoPuntoVenta" => $this->codigoPuntoVenta,
            "fechaEmision" => date("Y-m-d\TH:i:s.v", time()),
            "nombreRazonSocial" => trim($item->facturaOriginal->numero_documento) == '' || trim($item->facturaOriginal->numero_documento) == "0" ? "Control Tributario" : $item->facturaOriginal->razon_social,
            "codigoTipoDocumentoIdentidad" => strlen(trim($item->facturaOriginal->numero_documento)) > 8 ? 5 : 1,
            "numeroDocumento" => trim($item->facturaOriginal->numero_documento) == '' || trim($item->facturaOriginal->numero_documento) == "0" ? "99002" : str_replace("&","&amp;",trim($item->facturaOriginal->numero_documento)),
            "complemento" => null,
            "codigoCliente" => trim($item->facturaOriginal->codigo_cliente) === '' ? trim($item->facturaOriginal->numero_documento) : $item->facturaOriginal->codigo_cliente,
            "numeroFactura" => $item->facturaOriginal->numero_factura,
            "numeroAutorizacionCuf" => $item->facturaOriginal->cuf,
            "codigoControl" => null,
            "fechaEmisionFactura" => date("Y-m-d\TH:i:s.v", strtotime($item->facturaOriginal->fecha_emision)),
//            "fechaEmisionFactura" => date("Y-m-d\TH:i:s.v", time()),
            "montoTotalOriginal" =>  $item->facturaOriginal->monto_total,
            "montoTotalConciliado" => abs($item->total),
            "creditoFiscalIva" => $item->total > 0 ? $item->credito : 0,
            "debitoFiscalIva" => $item->total < 0 ? $item->credito : 0,
            "codigoExcepcion" => Env::codigoExcepcion,
            "leyenda" => LeyendaController::getLeyenda(ActividadEconomica::DistribucionAgua,),
            "usuario" => "PBaptista",//todo cambiar por usuario logueado
            "codigoDocumentoSector" => $this->codigoDocumentoSector,
        ];
    }

    function makeDetails($item)
    {
        $detalles = [];
        foreach ($item->detalleDocumentoAjustes as $detalle) {
            $detalles[] = [
                "actividadEconomica" => ActividadEconomica::DistribucionAgua,
                "codigoProductoSin" => Env::codigoProductoSin,
                "codigoProducto" => $detalle->codigo,
                "descripcion" => $detalle->descripcion,
                "cantidad" => $detalle->cantidad,
                "unidadMedida" => Env::unidadMedida,
                "precioUnitario" => $detalle->precio_unitario,
                "montoDescuento" => null,
                "subTotal" => $detalle->precio_unitario,
            ];
        }
        return $detalles;
    }

    function makeDetailsConciliacion($item)
    {
        $detalles = [];
        foreach ($item->detalleDocumentoAjustes as $detalle) {
            if ($detalle->es_seleccionado == true) {
                $detalles[] = [
                    "actividadEconomica" => ActividadEconomica::SujetoAiva,
                    "codigoProductoSin" => Env::codigoProductoSin,
                    "codigoProducto" => $detalle->codigo,
                    "descripcion" => $detalle->descripcion,
                    "montoOriginal" => $detalle->monto_original,
                    "montoFinal" => $detalle->monto_final,
                    "montoConciliado" => $detalle->monto_conciliado,
                ];
            }
        }
        return $detalles;
    }
}
