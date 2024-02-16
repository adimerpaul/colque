<?php

namespace App\Http\Controllers\PDF;

use Dompdf\Dompdf;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Patrones\Env;

class GenerateExportacionMineralPDF
{
    public static function generateHTML($xml, $cuf,$complemento, $tipoMoneda): string
    {
        $formatter = new NumeroALetras();
        $montoDola = (float)$xml->cabecera->montoTotalMoneda + (float)$xml->cabecera->gastosRealizacion;
        $montoBol = $montoDola * $xml->cabecera->tipoCambio;

        $literal = $formatter->toInvoice($montoDola, 2, 'DOLAR');
        $literalBol = $formatter->toInvoice($montoBol, 2, 'BOLIVIANOS');


        $detalles = "";

        foreach ($xml->detalle as $d) {
            if ($d->unidadMedida == Env::unidadMedida) {
                $d->unidadMedida = Env::unidadMedidaDescripcion;
            } else {
                $d->unidadMedida = Env::unidadMedidaOtroDescripcion;
            }
            $descripcionProducto = $d->descripcion;
            $detalles .= '
                <tr>
                    <td class="border">' . $d->codigoProducto . '</td>
                    <td class="border right">' . $d->descripcionLeyes . '</td>
                    <td class="border">' . $d->codigoNandina . '</td>
                    <td class="border">' . $d->cantidadExtraccion . '</td>
                    <td class="border right">' . $d->cantidad . '</td>
                    <td class="border right">' . $d->subTotal . '</td>
                </tr>
            ';
        }
        $url = Env::urlQr . "QR?nit=370883022&cuf=" . $xml->cabecera->cuf . "&numero=" . $xml->cabecera->numeroFactura.'&t=2';
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
        <td colspan="4">
            <div class="bold center text-h1">
                FACTURA COMERCIAL DE EXPORTACIÓN
            </div>
            <div class="center">
                (Sin Derecho a Crédito Fiscal)
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <table width="100%">
                <tr>
                    <td>
                        <div class="bold">FECHA (Date): </div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->fechaEmision . '</div>
                    </td>
                </tr>
                 <tr>
                    <td>
                        <div class="bold">COMPRADOR: </div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->nombreRazonSocial . '</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold">DIRECCION DEL COMPRADOR: </div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->direccionComprador . '</div>
                    </td>
                </tr>
                 <tr>
                    <td>
                        <div class="bold">NIT DEL COMPRADOR: </div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->numeroDocumento . '</div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="bold">DESCRIPCION DEL PRODUCTO: </div>
                    </td>
                    <td>
                        <div>' . $descripcionProducto . '</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold">PUERTO DE TRANSITO: </div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->puertoTransito . '</div>
                    </td>
                </tr>
                 <tr>
                    <td>
                        <div class="bold">PUERTO DESTINO PARA <br> FINES ESTADISTICOS:</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->puertoDestino . '</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold">MONEDA DE TRANSACCION</div>
                    </td>
                    <td>
                        <div>' . $tipoMoneda . '</div>
                    </td>
                </tr>




                <tr>
                    <td>
                        <div class="bold">Nº LOTE:</div>
                    </td>
                    <td align="left">
                        <div>' . $xml->cabecera->numeroLote . '</div>
                    </td>
                </tr>
                 <tr>
                    <td>
                        <div class="bold">KILOS NETOS HUMEDOS:</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->kilosNetosHumedos . '</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold">HUMEDAD(' . $xml->cabecera->humedadPorcentaje . '):</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->humedadValor . '</div>
                    </td>
                </tr>
                 <tr>
                    <td>
                        <div class="bold">MERMA(' . $xml->cabecera->mermaPorcentaje . '):</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->mermaValor . '</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold">KILOS NETOS SECOS:</div>
                    </td>
                    <td>
                        <div>' . $xml->cabecera->kilosNetosSecos . '</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <table width="100%" class="collapse">
                <tr class="background" >
                    <th class="border" width="50px" >MINERAL</th>
                    <th class="border">LEYES</th>
                    <th class="border">PARTIDA<br>ARANCELARIA</th>
                    <th class="border">FINOS</th>
                    <th class="border">COTIZACIONES</th>
                    <th class="border">SUBTOTAL</th>
                </tr>
                ' . $detalles . '


            </table>
        </td>
    </tr>
    <tr>
    <td colspan="4">
    <table width="100%" class="collapse" style="border: 1px solid">
                <tr style="border: 1px solid">
                    <th align="left" colspan="3">TOTAL VALOR OFICIAL DE EXPORTACIÓN (DOLAR)</th>
                    <td align="left">' . $montoDola . '</td>
                </tr>
                <tr style="border: 1px solid">
                    <td align="left" colspan="4">'. $literal.'</td>
                </tr>
                 <tr style="border: 1px solid">
                    <th align="left" colspan="3">TOTAL VALOR OFICIAL DE EXPORTACIÓN (BOLIVIANOS)</th>
                    <td align="left">' . $montoBol . '</td>
                </tr>
                <tr style="border: 1px solid">
                    <td align="left" colspan="4">'. $literalBol.'</td>
                </tr>
                 <tr style="border: 1px solid">
                    <th align="left" colspan="3">(-) GASTOS DE REALIZACIÓN SECTOR MINERA (Hasta 45% s/g NORMATIVA D.S. 25465) (DOLAR)</th>
                    <td align="left">' . $xml->cabecera->gastosRealizacion . '</td>
                </tr>
                 <tr style="border: 1px solid">
                    <th align="left" colspan="3">(-) DESCUENTO(DOLAR)</th>
                    <td align="left">' . $xml->cabecera->descuentoAdicional . '</td>
                </tr>
                <tr style="border: 1px solid">
                    <th align="left" colspan="3">VALOR FOB FRONTERA SEGÚN NORMATIVA ADUANERA VIGENTE (DOLAR)</th>
                    <td align="left">' . $xml->cabecera->montoTotalMoneda . '</td>
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
