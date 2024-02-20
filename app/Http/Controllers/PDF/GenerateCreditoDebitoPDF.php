<?php

namespace App\Http\Controllers\PDF;

use Dompdf\Dompdf;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Patrones\Env;

class GenerateCreditoDebitoPDF
{
    public static function generateHTML($xml, $cuf,$complemento): string
    {
        $numeroAutorizacionCuf = '';
        for ($i = 0; $i < strlen($xml->cabecera->numeroAutorizacionCuf); $i++) {
            if (($i + 1) % 20 == 0) {
                $numeroAutorizacionCuf .= substr($xml->cabecera->numeroAutorizacionCuf, $i, 1) . "<br>";
            } else {
                $numeroAutorizacionCuf .= substr($xml->cabecera->numeroAutorizacionCuf, $i, 1);
            }
        }
        $formatter = new NumeroALetras();
        $literal = $formatter->toInvoice((float)$xml->cabecera->montoTotalDevuelto, 2, 'Bolivianos');
        $detalles = "";


        $date=date_create($xml->cabecera->fechaEmisionFactura);
        $xml->cabecera->fechaEmisionFactura= date_format($date,"d/m/Y h:i A");

        $detallesConciliacion = "";
        foreach ($xml->detalle as $d) {
            if ($d->unidadMedida == Env::unidadMedida) {
                $d->unidadMedida = Env::unidadMedidaDescripcion;
            } else {
                $d->unidadMedida = Env::unidadMedidaOtroDescripcion;
            }
            if ($d->codigoDetalleTransaccion == 1) {
                $detalles .= '
                    <tr>
                        <td class="border">' . $d->codigoProducto . '</td>
                        <td class="border right">' . $d->cantidad . '</td>
                        <td class="border">' . $d->unidadMedida . '</td>
                        <td class="border">' . $d->descripcion . '</td>
                        <td class="border right">' . $d->precioUnitario . '</td>
                        <td class="border right">0</td>
                        <td class="border right">' . $d->subTotal . '</td>
                    </tr>
                ';
            } else {
                $detallesConciliacion .= '
                <tr>
                        <td class="border">' . $d->codigoProducto . '</td>
                        <td class="border right">' . $d->cantidad . '</td>
                        <td class="border">' . $d->unidadMedida . '</td>
                        <td class="border">' . $d->descripcion . '</td>
                        <td class="border right">' . $d->precioUnitario . '</td>
                        <td class="border right">0</td>
                        <td class="border right">' . $d->subTotal . '</td>
                </tr>';
            }
        }
        $url = Env::urlQr . "QR?nit=370883022&cuf=" . $xml->cabecera->cuf . "&numero=" . $xml->cabecera->numeroNotaCreditoDebito . "&t=2";
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
                        <div class="bold">Nota N°</div>
                        <div class="bold">CÓD. AUTORIZACIÓN</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->nitEmisor . '</div>
                        <div>' . $xml->cabecera->numeroNotaCreditoDebito . '</div>
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
                NOTA CRÉDITO - DÉBITO
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
                        <div>' . $xml->cabecera->fechaEmision . '</div>
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
                        <div class="bold">N° Factura:</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->numeroFactura . '</div>
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
                <tr>
                    <td class="right">
                        <div class="bold">Fecha Factura:</div>
                    </td>
                    <td>
                        <div>' .$xml->cabecera->fechaEmisionFactura . '</div>
                    </td>
                </tr>
                 <tr>
                    <td class="right">
                        <div class="bold">N° Autorización/CUF:</div>
                    </td>
                    <td>
                        <div>' . $numeroAutorizacionCuf . '</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3">
        <h5>DATOS FACTURA ORIGINAL</h5>
            <table width="100%" class="collapse">
                <tr class="background" >
                    <th class="border" width="50px" >CÓDIGO
                        PRODUCTO</th>
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
                    <td class="border right text-h5 bold background" colspan="2">MONTO TOTAL ORIGINAL Bs</td>
                    <td class="border right ">' . $xml->cabecera->montoTotalOriginal . '</td>
                </tr>
            </table>
        </td>
    </tr>
        <tr>
        <td colspan="3">
        <h5>DATOS DE LA DEVOLUCIÓN O RESCISIÓN</h5>
            <table width="100%" class="collapse">
                <tr class="background" >
                    <th class="border" width="50px" >CÓDIGO
                        PRODUCTO</th>
                    <th class="border">CANTIDAD</th>
                    <th class="border">UNIDAD DE
                        MEDIDA</th>
                    <th class="border">DESCRIPCIÓN</th>
                    <th class="border" width="60px">PRECIO
                        UNITARIO</th>
                    <th class="border">DESCUENTO</th>
                    <th class="border">SUBTOTAL</th>
                </tr>
                ' . $detallesConciliacion . '
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5 bold background" colspan="2">MONTO TOTAL DEVUELTO Bs</td>
                    <td class="border right">' . $xml->cabecera->montoTotalDevuelto . '</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right  text-h5 bold background" colspan="2">MONTO EFECTIVO DÉBITO-CRÉDITO Bs</td>
                    <td class="border right">' . $xml->cabecera->montoEfectivoCreditoDebito . '</td>
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
