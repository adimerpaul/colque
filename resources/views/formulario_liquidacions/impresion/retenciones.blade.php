<table class="table table-bordered"
       style="border:1px solid; border: #ECEFF1; border-collapse: collapse; width: 100%; margin-top: -12px">
    <thead>
    <tr style="font-size: 12px; background-color: #ECEFF1">
        <th colspan="6" style=" text-align: center"> 5. VALORIZACIÓN</th>
    </tr>
    <tr style="font-size: 12px; background-color: #ECEFF1">
        <th colspan="5" style=" text-align: right">TOTAL VALOR BRUTO VENTA BOB:</th>
        <th style="text-align: right">{{number_format($sumaBrutoVenta, 2)}}</th>
    </tr>
    </thead>
    <tr style="font-size: 11px; text-align: center">
        <td>ELEMENTO</td>
        <td>LEYES
        <td>PESO FINO KG</td>
        <td>COT. OFICIAL</td>
        <td>VALOR BRUTO VENTA</td>
        <td></td>
    </tr>
    @foreach($formularioLiquidacion->minerales_regalia as $mineral)
        @php
            $mineral = (object)$mineral;
                            $totalRegalias=$totalRegalias+$mineral->sub_total;
        @endphp
        <tbody id="tabla">
        <tr style="font-size: 11px; font-weight: normal; text-align: center">
            <td style="padding-left: 6px">{{ $mineral->simbolo }}</td>
            <td style="padding-left: 6px">{{ number_format($mineral->ley,3) }}</td>
            <td style="padding-left: 6px">{{ number_format($mineral->peso_fino, 2) }}</td>
            <td style="padding-left: 6px">{{ number_format($mineral->cotizacion_oficial, 2) }}</td>
            <td style="padding-left: 6px" id="valorBruto"
                class="valorBruto">{{ number_format($mineral->valor_bruto_venta, 2) }} BOB
            </td>
            <td></td>
        </tr>

        </tbody>
    @endforeach
    @for($i = 0; $i < (3 - $cotizacionesDiarias->count()); $i++)
        <tr>
            <td colspan="6">&nbsp;</td>
        </tr>
    @endfor
</table>

<br>
<table class="table table-bordered"
       style="border:1px solid; font-size: 11px;border: #ECEFF1; border-collapse: collapse; width: 100%; margin-top: -22px">
    <thead>
    <tr style="font-size: 12px; font-weight: bold; text-align: left; background-color: #ECEFF1">
        <td colspan="3" style=" text-align: right">VALOR POR TONELADA USD:</td>
        <td style=" text-align: right">{{number_format($formularioLiquidacion->valor_por_tonelada,2)}}</td>

    </tr>
    <tr style="font-size: 12px; background-color: #ECEFF1;">
        <th colspan="3" style=" text-align: right">TOTAL VALOR NETO VENTA BOB:</th>
        <th style=" text-align: right">{{number_format($formularioLiquidacion->valor_neto_venta, 2)}}</th>
    </tr>
    <tr style="font-size: 12px; background-color: #ECEFF1;">
        <th colspan="3" style=" text-align: right">RETENCIONES DE LEY Y DEDUCCIONES INSTITUCIONALES BOB:</th>
        <th style=" text-align: right">{{ number_format(($descuentos->sum('sub_total') + $retenciones->sum('sub_total') + $totalRegalias), 2) }}</th>
    </tr>
    </thead>
    <tr>
        <td colspan="4" style="padding-left: 3px"><b>RETENCIONES DE LEY</b></td>
    </tr>
    <tr style="font-size: 11px; font-weight: normal; text-align: left ">
        <td style="width: 55%; padding-left: 3px">REGALÍA MINERA</td>
        <td></td>
        <td class="text-right" style="width: 27%; text-align: right">{{ number_format($totalRegalias,2) }} BOB</td>
        <td></td>
    </tr>
    @foreach($retenciones as $retencion)
        <tr style="font-size: 11px; font-weight: normal; text-align: left ">
            <td style="width: 55%; padding-left: 3px">{{ $retencion->descuentoBonificacion->nombre }}</td>
            <td class="text-right"
                style="width: 15%">{{ number_format($retencion->valor, 2) }} {{ $retencion->unidad === 'Porcentaje' ? '%' : '' }}</td>
            <td class="text-right" style="width: 27%; text-align: right">{{ number_format($retencion->sub_total,2) }}
                BOB
            </td>
            <td></td>
        </tr>
    @endforeach
    @for($i = 0; $i < (2 - $retenciones->count()); $i++)
        <tr><td>&nbsp;</td></tr>
    @endfor
    <tr>
        <td colspan="4" style="padding-left: 3px"><b>DEDUCCIONES INSTITUCIONALES</b></td>
    </tr>
    @foreach($descuentos as $descuento)
        <tr style="font-size: 11px; font-weight: normal; text-align: left ">
            <td style="width: 50%; padding-left: 3px">{{ $descuento->descuentoBonificacion->nombre }}</td>
            <td class="text-right"
                style="width: 25%">{{ number_format($descuento->valor, 2) }} {{ $descuento->unidad === 'Porcentaje' ? '%' : '' }}</td>
            <td style="width: 25%; text-align: right">{{ number_format($descuento->sub_total,2) }} BOB</td>
            <td></td>
        </tr>
    @endforeach
    @for($i = 0; $i < (9 - $descuentos->count()); $i++)
        <tr><td colspan="4">&nbsp;</td></tr>
    @endfor
    @include('formulario_liquidacions.impresion.bonificaciones')

</table>
