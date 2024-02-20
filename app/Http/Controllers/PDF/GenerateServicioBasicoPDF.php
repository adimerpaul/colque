<?php

namespace App\Http\Controllers\PDF;

use Dompdf\Dompdf;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Patrones\Env;
class GenerateServicioBasicoPDF
{
    public static function generateHTML($xml,$cuf,$complemento): string
    {
        $numeroAutorizacionCuf = '';
        for ($i = 0; $i < strlen($xml->cabecera->numeroAutorizacionCuf); $i++) {
            if (($i + 1) % 20 == 0) {
                $numeroAutorizacionCuf .= substr($xml->cabecera->numeroAutorizacionCuf, $i, 1) . "<br>";
            } else {
                $numeroAutorizacionCuf .= substr($xml->cabecera->numeroAutorizacionCuf, $i, 1);
            }
        }
        if ($xml->cabecera->beneficiarioLey1886==""){
            $beneficiarioLey1886="";
        }else{
            $beneficiarioLey1886="
                <tr>
                    <td class='right'>
                        <div class='bold'>Beneficiario Ley 1886:</div>
                    </td>
                    <td>
                        <div>" .$xml->cabecera->beneficiarioLey1886 . "</div>
                    </td>
                </tr>";
        }
        if ($xml->cabecera->montoDescuentoLey1886==""){
            $montoDescuentoLey1886="";
        }else{
            $montoDescuentoLey1886="<br><div style='font-size: 9px'>Ley 1886 ".$xml->cabecera->montoDescuentoLey1886."</div>";
        }

        $formatter = new NumeroALetras();
        $literal = $formatter->toInvoice((float)$xml->cabecera->montoTotal, 2, 'Bolivianos');
        $detalles = "";
        foreach ($xml->detalle as $d) {
            if ($d->unidadMedida==Env::unidadMedida) {
                $d->unidadMedida = Env::unidadMedidaDescripcion;
            }else{
                $d->unidadMedida = Env::unidadMedidaOtroDescripcion;
            }
            if ($d->codigoProducto=="2216"){
                $texto=$montoDescuentoLey1886;
            }else{
                $texto="";
            }
            $detalles .= '
                <tr>
                    <td class="border">' . $d->codigoProducto . '</td>
                    <td class="border right">' . $d->cantidad . '</td>
                    <td class="border">' . $d->unidadMedida . '</td>
                    <td class="border">' . $d->descripcion . ' '.$texto.'</td>
                    <td class="border right">' . $d->precioUnitario . '</td>
                    <td class="border right">0</td>
                    <td class="border right">' . $d->subTotal . '</td>
                </tr>
            ';
        }
        $detalles.='<tr>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border">Ajustes sujetos a IVA</td>
                    <td class="border right"></td>
                    <td class="border right"></td>
                    <td class="border right">0</td>
                </tr>';
        $detalles.='<tr>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border">Tasa Aseo Urbano</td>
                    <td class="border right"></td>
                    <td class="border right"></td>
                    <td class="border right">0</td>
                </tr>';
        $detalles.='<tr>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border">Tasa Alumbrado</td>
                    <td class="border right"></td>
                    <td class="border right"></td>
                    <td class="border right">0</td>
                </tr>';
        $detalles.='<tr>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border">Otras Tasas </td>
                    <td class="border right"></td>
                    <td class="border right"></td>
                    <td class="border right">0</td>
                </tr>';
        $detalles.='<tr>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border"></td>
                    <td class="border">Otros Pagos (pago de cuotas etc)</td>
                    <td class="border right"></td>
                    <td class="border right"></td>
                    <td class="border right">0</td>
                </tr>';
        $url =Env::urlQr."QR?nit=370883022&cuf=" . $xml->cabecera->cuf . "&numero=" . $xml->cabecera->numeroFactura."&t=2";
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($url));
        return ('
        <!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        *{
            font-size: 12px;
        }
        .bold{
            font-weight: bold;
        }
        .text-h1{
            font-size: 20px;
        }
        .text-h5{
            font-size: 8px;
        }
        .text-h6{
            font-size: 7px;
        }
        .center{
            text-align: center;
        }
        .right{
            text-align: right;
        }
        .border{
            border: 1px solid black
        }
        .collapse{
            border-collapse: collapse;
        }
        .background{
            background: #edf2f7
        }
        .overflow-visible {
          white-space: initial;
        }
    </style>
</head>
<body>
<table width="100%"  class="collapse" >
    <tr>
        <td width="33%">
            <div class="bold center">' . $xml->cabecera->razonSocialEmisor . '</div>
            <div class="bold center">CASA MATRIZ</div>
            <div class="center">No. Punto de Venta ' . $xml->cabecera->codigoPuntoVenta . '</div>
        </td>
        <td></td>
        <td width="120px">
            <table>
                <tr>
                    <td valign=top width="130px">
                        <div class="bold">NIT</div>
                        <div class="bold">FACTURA N°</div>
                        <div class="bold">CÓD. AUTORIZACIÓN</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->nitEmisor . '</div>
                        <div>' . $xml->cabecera->numeroFactura . '</div>
                        <div style="width: 120px">' . $cuf . '</div>
                        <br>
                        <br>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <div class="center">
                ' . $xml->cabecera->direccion . '
            </div>
            <div class="center">Teléfono: ' . $xml->cabecera->telefono . '</div>
            <div class="center">Oruro</div>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <div class="bold center text-h1">
                FACTURA
            </div>
            <div class="center">
                (Con Derecho a Crédito Fiscal)
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%">
                <tr>
                    <td>
                        <div class="bold">Fecha: </div>
                    </td>
                    <td>
                        <div>'.$xml->cabecera->fechaEmision.'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold">Nombre/Razón Social:</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->nombreRazonSocial . '</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold">Dirección:</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->domicilioCliente . '</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold">Consumo Periodo:</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->consumoPeriodo . '</div>
                    </td>
                </tr>
            </table>
        </td>
        <td>
            <table width="100%">
                <tr>
                    <td class="right">
                        <div class="bold">NIT/CI/CEX:</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->numeroDocumento . $complemento.'</div>
                    </td>
                </tr>
                <tr>
                    <td class="right">
                        <div class="bold">Cod. Cliente:</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->codigoCliente . '</div>
                    </td>
                </tr>
                '.$beneficiarioLey1886.'
                <tr>
                    <td class="right">
                        <div class="bold">Periodo Facturado:</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->mes  .'/'.$xml->cabecera->gestion . '</div>
                    </td>
                </tr>
                 <tr>
                    <td class="right">
                        <div class="bold">Nro Medidor:</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->numeroMedidor . '</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table width="100%" class="collapse">
                <tr class="background" >
                    <th class="border" width="50px" >CÓDIGO
                        SERVICIO</th>
                    <th class="border">CANTIDAD</th>
                    <th class="border">UNIDAD DE
                        MEDIDA</th>
                    <th class="border">DESCRIPCIÓN</th>
                    <th class="border" width="60px">PRECIO
                        UNITARIO</th>
                    <th class="border">DESCUENTO</th>
                    <th class="border">SUBTOTAL</th>
                </tr>
                ' . $detalles . '
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">TOTAL Bs</td>
                    <td class="border right">' . $xml->cabecera->montoTotal . '</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">(-) DESCUENTO Bs</td>
                    <td class="border right">0</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">SUBTOTAL A PAGAR Bs</td>
                    <td class="border right">' . $xml->cabecera->montoTotal . '</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">(-) AJUSTES NO SUJETOS A IVA Bs</td>
                    <td class="border right">0</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5 bold background" colspan="2">MONTO TOTAL A PAGAR Bs</td>
                    <td class="border right">' . $xml->cabecera->montoTotal . '</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">(-) TASAS Bs</td>
                    <td class="border right">0</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">(-) OTROS PAGOS NO SUJETO IVA Bs</td>
                    <td class="border right">0</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">(+) AJUSTES NO SUJETOS A IVA Bs</td>
                    <td class="border right">0</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right  text-h5 bold background" colspan="2">IMPORTE BASE CRÉDITO FISCAL</td>
                    <td class="border right">' . $xml->cabecera->montoTotal . '</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" >
            <table width="100%">
                <tr>
                    <td class="center" valign=top>
                        <div class="left bold">Son: ' . $literal . '</div>
                        <div class="text-h5">ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY
                            </div>
                        <div class="text-h5">' . $xml->cabecera->leyenda . '</div>
                        <div class="text-h5">“Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea”</div>
                    </td>
                    <td>
                        <img width="95px" src="data:image/png;base64,' . $qrcode . '">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
');
    }
}
