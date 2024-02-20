<?php
namespace App\Http\Controllers\DataXML;

use App\Patrones\ActividadEconomica;
use App\Patrones\Env;
use App\Patrones\Fachada;
use Illuminate\Support\Facades\Auth;

class DataXMLLey
{
    use DataXML;

    private $codigoDocumentoSector;
    private $codigoPuntoVenta;

    public function __construct($codigoDocumentoSector, $codigoPuntoVenta)
    {
        $this->codigoDocumentoSector = $codigoDocumentoSector;
        $this->codigoPuntoVenta = $codigoPuntoVenta;
    }

    function makeHead($xml, $cuf, $cufd, $leyenda, $nroFactura)
    {

        return [
            "nitEmisor" => Env::nit,
            "razonSocialEmisor" => Env::razonSocial,
            "municipio" => Env::municipio,
            "telefono" => Env::telefono,
            "numeroFactura" => $nroFactura,
            "cuf" => $cuf,
            "cufd" => $cufd,
            "codigoSucursal" => Env::codigoSucursal,
            "direccion" => Env::direccion,
            "codigoPuntoVenta" => $this->codigoPuntoVenta,
            "mes" => $xml->mes,
            "gestion" => $xml->gestion,
            "ciudad" => Env::ciudad,
            "zona" => Env::zona,
            "numeroMedidor" => "0",
            "fechaEmision" => date("Y-m-d\TH:i:s.v", strtotime("$xml->fechaEmision")),
            "nombreRazonSocial" => $xml->nombreRazonSocial,
            "domicilioCliente"=> $xml->domicilioCliente,
            "codigoTipoDocumentoIdentidad" => strlen($xml->numeroDocumento) > 8 ? 5 : 1,
            "numeroDocumento" => $xml->numeroDocumento,
            "complemento" => null,
            "codigoCliente" => $xml->numeroDocumento,
            "codigoMetodoPago" => Env::codigoMetodoPago,
            "numeroTarjeta" => null,
            //"montoTotal" => $xml->pla_nmes >= 3 ? $xml->pla_totmes - $xml->pla_corte : $xml->pla_totmes,
            "montoTotal" => $xml->montoTotal,
            "montoTotalSujetoIva" => $xml->montoTotal,
            "consumoPeriodo" => null, //FALTA
            "beneficiarioLey1886" => null, //FALTA
            "montoDescuentoLey1886" => null, //FALTA
            "montoDescuentoTarifaDignidad" => null, //FALTA
            "tasaAseo" => null,
            "tasaAlumbrado" => null,
            "ajusteNoSujetoIva" => null,
            "detalleAjusteNoSujetoIva" => null,
            "ajusteSujetoIva" => null,
            "detalleAjusteSujetoIva" => null,
            "otrosPagosNoSujetoIva" => null,
            "detalleOtrosPagosNoSujetoIva" => null,
            "otrasTasas" => null,
            "codigoMoneda" => Env::codigoMoneda,
            "tipoCambio" => Env::codigoMoneda,
            "montoTotalMoneda" => $xml->montoTotal,
            "descuentoAdicional" => null,
            "codigoExcepcion" => Env::codigoExcepcion,
            "cafc" =>  null,
            "leyenda" => $xml->leyenda,
            "usuario" => Auth::user()->nombre_completo,//todo cambiar por usuario logueado
            "codigoDocumentoSector" => $this->codigoDocumentoSector
        ];
    }

    function makeDetails($xml)
    {
        $detalles = [];

            $detalles[] = [
                "actividadEconomica" => ActividadEconomica::DistribucionAgua,
                "codigoProductoSin" => Env::codigoProductoSin,
                "codigoProducto" => $xml->codigoDetalle,
                "descripcion" => $xml->descripcionDetalle,
                "cantidad" => Env::cantidad,
                "unidadMedida" => Env::unidadMedida,
                "precioUnitario" => $xml->montoTotal,
                "montoDescuento" => null,
                "subTotal" => $xml->montoTotal
            ];
        return $detalles;
    }
}
