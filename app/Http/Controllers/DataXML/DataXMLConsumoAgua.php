<?php
namespace App\Http\Controllers\DataXML;

use App\Models\Anulado;
use App\Patrones\ActividadEconomica;
use App\Patrones\Env;
use App\Patrones\Fachada;

class DataXMLConsumoAgua
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
        $anuladoCount = Anulado::where([['periodo', $xml->pla_tari], ['cuenta', $xml->pla_matr.$xml->pla_dive]])->orderByDesc('id')->count();
        if($anuladoCount > 0){
            $anulado = Anulado::where([['periodo', $xml->pla_tari], ['cuenta', $xml->pla_matr.$xml->pla_dive]])->orderByDesc('id')->first();
            $fechaHoraAnulado = date("Y-m-d H:i:s", strtotime($anulado->created_at));
            $fechaEmisionAnulado = date("Y-m-d\TH:i:s.v", strtotime($fechaHoraAnulado));
        }


        $montoTotal = $xml->pla_vlag + $xml->pla_vlma + $xml->pla_form + $xml->pla_leyprv + $xml->pla_abono + $xml->pla_inag + $xml->pla_cuocre + $xml->pla_redon;
        $meses = Fachada::$meses;
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
            "mes" => (int)substr($xml->pla_tari,4, 6),
            "gestion" => substr($xml->pla_tari, 0, 4),
            "ciudad" => Env::ciudad,
            "zona" => Env::zona,
            "numeroMedidor" => $xml->usr_numm == "" ? "0" : $xml->usr_numm,
            "fechaEmision" => $anuladoCount > 0? $fechaEmisionAnulado : date("Y-m-d\TH:i:s.v", strtotime("$xml->pla_femi")),
            "nombreRazonSocial" => trim($xml->pla_rucnit) == "" ||  trim($xml->pla_rucnit) === "0" ? "Control Tributario" : str_replace("&","&amp;",trim($xml->usr_apno)),
            "domicilioCliente"=> strlen($xml->usr_dirr) > 0 ? $xml->usr_dirr : null,
            "codigoTipoDocumentoIdentidad" => strlen($xml->pla_rucnit) > 8 ? 5 : 1,
            "numeroDocumento" => trim($xml->pla_rucnit) == "" ||  trim($xml->pla_rucnit) === "0"  ? "99002" : trim($xml->pla_rucnit),
            "complemento" => strlen($xml->pla_rucnit) > 8 ? null : "",
            "codigoCliente" => $xml->pla_matr . $xml->pla_dive,
            "codigoMetodoPago" => Env::codigoMetodoPago,
            "numeroTarjeta" => null,
            //"montoTotal" => $xml->pla_nmes >= 3 ? $xml->pla_totmes - $xml->pla_corte : $xml->pla_totmes,
            "montoTotal" => $montoTotal,
            "montoTotalSujetoIva" => $montoTotal,
            "consumoPeriodo" => $xml->pla_cns1, //FALTA
            "beneficiarioLey1886" => trim($xml->usr_mayor) == 'S' && strlen(trim($xml->pla_rucnit)) > 0 ? trim($xml->pla_rucnit) : null, //FALTA
            "montoDescuentoLey1886" => trim($xml->usr_mayor) == 'S' ? abs($xml->pla_leyprv) : null, //FALTA
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
            "montoTotalMoneda" => $montoTotal,
            "descuentoAdicional" => null,
            "codigoExcepcion" => Env::codigoExcepcion,
            "cafc" =>  null,
            "leyenda" => $leyenda,
            "usuario" => "PBaptista",//todo cambiar por usuario logueado
            "codigoDocumentoSector" => $this->codigoDocumentoSector
        ];
    }

    function makeDetails($xml)
    {
        $detalles = [];
        if ($xml->pla_totmes > 0 && ($xml->pla_vlma > 0 || $xml->pla_vlag > 0)) {
            $precioUnitario = $xml->pla_vlag + $xml->pla_vlma + $xml->pla_leyprv + $xml->pla_abono + $xml->pla_inag + $xml->pla_cuocre + $xml->pla_redon;

            $detalles[] = [
                "actividadEconomica" => ActividadEconomica::DistribucionAgua,
                "codigoProductoSin" => Env::codigoProductoSin,
                "codigoProducto" => "411000",
                "descripcion" => "CONSUMO AGUA POTABLE",
                "cantidad" => Env::cantidad,
                "unidadMedida" => Env::unidadMedida,
                "precioUnitario" => $precioUnitario,
                "montoDescuento" => null,
                "subTotal" => $precioUnitario,
            ];
        }
        if ($xml->pla_form > 0) {
            $detalles[] = [
                "actividadEconomica" => ActividadEconomica::DistribucionAgua,
                "codigoProductoSin" => Env::codigoProductoSin,
                "codigoProducto" => "2216",
                "descripcion" => "REPOSICION DE FACTURA",
                "cantidad" => Env::cantidad,
                "unidadMedida" => Env::unidadMedida,
                "precioUnitario" => $xml->pla_form,
                "montoDescuento" => null,
                "subTotal" => $xml->pla_form
            ];
        }
        return $detalles;
    }
}
