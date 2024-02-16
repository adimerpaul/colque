<head>
    <title>Resumen</title>
</head>
<div class="centro" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif;">
    <div style="width: 100%;">
        <div style="width: 70%; float:left;">
            <p style="font-size: 13px">
                <strong>RAZÓN SOCIAL:</strong> {{ $productor->razon_social }}
                <br>
                <strong>PERIODO:</strong> DEL {{ date('d/m/y', strtotime($fechaInicio)) }} AL {{ date('d/m/y', strtotime($fechaFinal)) }} <br>
                <strong>FECHA:</strong>  {{date('d/m/y')}}<br>
            </p>
        </div>
        <div style="width: 30%; float:right; text-align: right">
            <img src="{{ 'logos/logo.png'}}" style="width: 150px; height: 75px;">
        </div>
    </div>

    <div>
        <h3 style="text-align: center; margin-top: 10px ">COMPROBANTE DE EGRESO</h3>
    </div>
    <table style="width: 100%; border: 1px solid black;" id="resumen-tabla">
        <thead>
        <tr style="font-size: 16px; font-weight: normal; text-align: left ">
            <th style="text-align: left">NOMBRE DE CUENTA</th>
            <th style="text-align: left">DEBE BOB</th>
            <th style="text-align: left">HABER BOB</th>
        </tr>
        </thead>
        <tbody style="font-size: 14px; font-weight: normal; text-align: left " id="tbody">
        <tr>
            <td>VALOR NETO VENTA</td>
            <td>{{ round($totalNetoVenta,2)}}</td>
            <td></td>
        </tr>
        @if($nroBonificaciones >0)
            @foreach($bonificaciones as $bonificacion)
                <tr>
                    <td>{{$bonificacion->nombre}}</td>
                    <td>{{round(($bonificacionesTotales[$bonificacion->nombre]),2)}}</td>
                    <td></td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td>SALDO DEUDA POR COBRAR</td>
            <td>{{ round(($totalSaldoDeuda),2)}}</td>
            <td></td>
        </tr>
        @if($diferenciaDebe!=0.00)
            <tr>
                <td>DIFERENCIA CAMBIO</td>
                <td>{{ round($diferenciaDebe,2)}}</td>
                <td></td>
            </tr>
        @endif
        <tr>
            <td>REGALIA MINERA POR PAGAR</td>
            <td></td>
            <td>{{ round($totalRegalia,2)}}</td>
        </tr>
        @if($nroRetenciones >0)
            @foreach($retenciones as $retencion)
                <tr>
                    <td>{{$retencion->nombre}}</td>
                    <td></td>
                    <td>{{round(($retencionesTotales[$retencion->nombre]),2)}}</td>
                </tr>
            @endforeach
        @endif
        @if($nroDescuentos >0)
            @foreach($descuentos as $descuento)
                <tr>
                    <td>{{$descuento->nombre}}</td>
                    <td></td>
                    <td>{{round(($descuentosTotales[$descuento->nombre]),2)}}</td>
                </tr>
            @endforeach
        @endif


        <tr>
            <td>ANTICIPOS OTORGADOS</td>
            <td></td>
            <td>{{ round($totalAnticipos,2)}}</td>
        </tr>
        <tr>
            <td>SALDO POR DEUDA PRÉSTAMOS</td>
            <td></td>
            <td>{{ round($totalPrestamos,2)}}</td>
        </tr>
        <tr>
            <td>SALDO POR DEUDA SALDO NEGATIVO</td>
            <td></td>
            <td>{{ round($totalSaldoNegativo,2)}}</td>
        </tr>
        <tr>
            <td>SALDO POR DEUDA RETIRO</td>
            <td></td>
            <td>{{ round($totalRetiros,2)}}</td>
        </tr>
        <tr>
            <td>APORTES FUNDACIÓN POR PAGAR</td>
            <td></td>
            <td>{{ round($totalAporteFundacion,2)}}</td>
        </tr>

        <tr>
            <td>DEVOLUCIÓN ANTICIPO</td>
            <td></td>
            <td>{{ round($totalDevolucionAnticipo,2)}}</td>
        </tr>
        <tr>
            <td>DEVOLUCIÓN ANÁLISIS</td>
            <td></td>
            <td>{{ round($totalDevolucionLaboratorio,2)}}</td>
        </tr>

        <tr>
            <td>CAJA BNB M/N</td>
            <td></td>
            <td>{{ round($bnb,2)}}</td>
        </tr>
        <tr>
            <td>CAJA BANCO ECONOMICO M/N</td>
            <td></td>
            <td>{{ round($economico,2)}}</td>
        </tr>
        <tr>
            <td>CAJA M/N CENTRAL</td>
            <td></td>
            <td>{{ round($efectivo,2)}}</td>
        </tr>
        @if($diferenciaHaber!=0.00)
            <tr>
                <td>DIFERENCIA CAMBIO</td>
                <td></td>
                <td>{{ round($diferenciaHaber,2)}}</td>
            </tr>
        @endif

        </tbody>
        <tfoot>
        <tr>
            <td style="text-align: right; padding-right: 10px"><b>TOTALES:</b></td>
            <td id="debeTotal">{{$totalDebe}}</td>
            <td id="haberTotal">{{$totalHaber}}</td>
        </tr>
        </tfoot>
    </table>

    <br><br><br>
</div>


