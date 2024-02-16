<thead>
    <tr style="font-size: 12px; background-color: #ECEFF1;">
        <th colspan="3" style=" text-align: right">BONIFICACIONES BOB:</th>
        <th style=" text-align: right">{{ number_format($bonificaciones->sum('sub_total'), 2) }}</th>
    </tr>
</thead>

@foreach($bonificaciones as $bonificacion)
    <tr style="font-size: 11px; font-weight: normal; text-align: left ">
        <td style="width: 50%; padding-left: 3px">{{ $bonificacion->descuentoBonificacion->nombre }}</td>
        <td class="text-right"
            style="width: 25%">{{ number_format($bonificacion->valor, 2) }}
            {{ $bonificacion->unidad_simbolo }}</td>
        <td style="width: 30%; text-align: right">{{ number_format($bonificacion->sub_total,2) }} BOB</td>
        <td></td>
    </tr>
@endforeach

@for($i = 0; $i < (5 - $bonificaciones->count()); $i++)
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
@endfor

<tr style="font-size: 12px; background-color: #ECEFF1;">
    <th colspan="3" style=" text-align: right">LIQUIDO PAGABLE BOB:</th>
    <th style=" text-align: right">{{ number_format($formularioLiquidacion->totales['total_liquidacion'], 2) }}</th>
</tr>

@if($anticipos->count()<=3)
    @foreach($anticipos as $anticipo)
        <tr style="font-size: 11px; font-weight: normal; text-align: left">
            <td style="width: 50%"></td>
            <td style="width: 25%">ANTICIPO {{$loop->iteration}}</td>
            <td style="width: 25%; text-align: right">{{date( 'd/m/Y', strtotime($anticipo->fecha))}}</td>
            <td style="text-align: right">{{ number_format($anticipo->monto,2) }}</td>
        </tr>
    @endforeach
@else
    <tr style="font-size: 11px; font-weight: normal; text-align: left">
        <td style="width: 50%"></td>
        <td style="width: 25%">TOTAL ANTICIPOS</td>
        <td style="width: 25%; text-align: right"></td>
        <td style="text-align: right">{{ number_format($anticipos->sum('monto'),2) }}</td>
    </tr>
@endif

@if($formularioLiquidacion->total_cuentas_cobrar>0)
    <tr style="font-size: 11px; font-weight: normal; text-align: left">
        <td style="width: 50%"></td>
        <td style="width: 25%" colspan="2">{{$formularioLiquidacion->mensaje_cuentas_cobrar}}</td>
        <td style="text-align: right">{{ number_format($formularioLiquidacion->total_cuentas_cobrar,2) }}</td>
    </tr>
@endif

@if($formularioLiquidacion->total_prestamos>0)
    <tr style="font-size: 11px; font-weight: normal; text-align: left">
        <td style="width: 50%"></td>
        <td style="width: 25%" colspan="2">{{$formularioLiquidacion->mensaje_prestamo}}</td>
        <td style="text-align: right">{{ number_format($formularioLiquidacion->total_prestamos,2) }}</td>
    </tr>
@endif

@if($bonos->count()>0)
    <tr style="font-size: 11px; font-weight: normal; text-align: left">
        <td style="width: 50%"></td>
        <td style="width: 40%">SALDO POR RETIRO</td>
        <td style="width: 15%"></td>
        <td style="text-align: right">{{ number_format($bonos->sum('monto'),2) }}</td>
    </tr>
@endif
@if($formularioLiquidacion->aporte_fundacion!=0)
    <tr style="font-size: 11px; font-weight: normal; text-align: left">
        <td style="width: 50%">&nbsp;</td>
        <td style="width: 40%; text-align: left"> APORTE FUNDACIÓN
            COLQUECHACA
        </td>
        <td style="width: 15%"></td>
        <td style="text-align: right">{{ $formularioLiquidacion->aporte_fundacion }} </td>
    </tr>
@endif
<tr style="font-size: 15px;">
    <td style="width: 50%">&nbsp;</td>
    <td STYLE="text-align: left;width: 35%"><strong>
            SALDO A FAVOR BOB:</strong></td>
    <td style="width: 15%"></td>
    <td style="white-space: nowrap; overflow: hidden; text-overflow:ellipsis;">
        <strong style="float: right; margin-right: -2px">
            {{ number_format($formularioLiquidacion->totales['total_saldo_favor'], 2)}}
        </strong>
    </td>
</tr>
