<head>
    <title>{{ $pago->tipo=='Egreso'? 'Pago ': 'Cobro ' }} {{$pago->codigo}}</title>
</head>
<div class="centro" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif;">
    <div style="width: 100%;">
        <div style="width: 70%; float:left;">
            <p style="font-size: 13px">
                <strong>NÚMERO:</strong> {{$pago->codigo}}<br>
                <strong>FECHA Y HORA:</strong> {{date( 'd/m/Y H:i', strtotime($pago->created_at))}}<br>
                <strong>MONTO:</strong> {{number_format($pago->monto,2)}} $us<br>
            </p>
        </div>
        <div style="width: 30%; float:right; text-align: right">
            <img src="{{ 'logos/'.Auth::user()->personal->empresa->logo}}" style="width: 150px; height: 75px;">
        </div>
    </div>
    {{--<br><br><br>--}}
    <br><br>
    <br><br><br>
    <div>
        <h3 style="text-align: center; margin-top: 10px ">
            @if($pago->tipo=='Egreso')
                ORDEN DE PAGO
            @else
                ORDEN DE INGRESO
            @endif
        </h3>
        <p style="font-size: 13px">
            @if(is_null($pago->proveedor))
                <strong>CLIENTE:</strong>
                {{$pago->venta->comprador->razon_social}}
            @else
                <strong>PROVEEDOR:</strong>
                {{$pago->proveedor->nombre}}
            @endif
            <br>
            <strong>CONCEPTO:</strong> {{$pago->glosa}}<br>
            <strong>FACTURA:</strong> {{$pago->factura}} <br>
        </p>
    </div>
    <br>
    <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: -10px">
    <br>

    <p style="font-size: 13px; margin-top: -2px"><strong>SON:</strong> {{$literal}}.</p>
    <br><br><br><br><br>
    <table style="width: 100%; text-align: center">
        <tr style="font-size: 13px; ">
            <td class="center"><strong>RESPONSABLE DE CAJA</strong></td>
            <td class="center">
                <strong>
                    @if($pago->tipo=='Egreso') RECIBÍ @else ENTREGUÉ @endif
                    CONFORME
                </strong>
            </td>
        </tr>
        <tr style="font-size: 13px; font-weight: normal; ">
            <td class="center">VICTOR PEÑA AFINO</td>
            <td class="center">
                @if(!is_null($pago->proveedor))
                    {!! $pago->proveedor->nombre !!}
                @else
                    Nombre:...................................
                    <br>
                    Carnet:...................................
                @endif

            </td>
        </tr>


    </table>
</div>
