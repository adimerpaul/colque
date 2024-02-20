<?php

namespace App\Http\Controllers\PDF;

use App\Models\FacturasImpuestos;
use App\Models\TipoMoneda;
use App\Patrones\TipoPdf;
use Dompdf\Dompdf;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Patrones\Env;

class GeneratePDF
{

    public static function generate($pathXmlFile, $type)
    {
        if (!file_exists($pathXmlFile)) return false;

        $nameFile = substr($pathXmlFile, 0, strlen($pathXmlFile) - 4);
        $content = file_get_contents($pathXmlFile);
        $xml = simplexml_load_string($content);
        $cuf = '';
        for ($i = 0; $i < strlen($xml->cabecera->cuf); $i++) {
            if (($i + 1) % 20 == 0) {
                $cuf .= substr($xml->cabecera->cuf, $i, 1) . "<br>";
            } else {
                $cuf .= substr($xml->cabecera->cuf, $i, 1);
            }
        }
        $date=date_create($xml->cabecera->fechaEmision);
        $xml->cabecera->fechaEmision= date_format($date,"d/m/Y h:i A");

        if ($xml->cabecera->complemento!='') {
            $complemento = "-".$xml->cabecera->complemento;
        }else{
            $complemento= "";
        }

        $tipoMoneda = TipoMoneda::where('codigo', $xml->codigoMoneda)->first();

        $factura = FacturasImpuestos::where('cuf', $xml->cabecera->cuf)->first();
        $enLinea = $factura->es_enviado;

        if ($type == TipoPdf::pdfOtrosIngresos) {
            $html = GenerateOtroIngresoPDF::generateHTML($xml, $cuf,$complemento);
        } else if ($type == TipoPdf::pdfConciliacion) {
            $html = GenerateConciliacionPDF::generateHTML($xml, $cuf,$complemento);
        } else if ($type == TipoPdf::pdfCreditoDebito) {
            $html = GenerateCreditoDebitoPDF::generateHTML($xml, $cuf,$complemento);
        } else if ($type == TipoPdf::pdfServicioBasico) {
            $html = GenerateServicioBasicoPDF::generateHTML($xml, $cuf,$complemento);
        } else if ($type == TipoPdf::pdfLibreConsignacion) {
            $html = GenerateLibreConsignacionPDF::generateHTML($xml, $cuf,$complemento, $tipoMoneda);
        } else if ($type == TipoPdf::pdfExportacionMineral) {
            $html = GenerateExportacionMineralPDF::generateHTML($xml, $cuf,$complemento, $tipoMoneda);
        }



        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter');
        $dompdf->render();
        file_put_contents($nameFile . ".pdf", $dompdf->output());
        return $nameFile . ".pdf";
    }
}
