<head>
    <title>Anticipo {{$anticipo->codigo_caja}}</title>
</head>
<div class="centro" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif;">
    <div style="width: 100%;">
        <div style="width: 70%; float:left;">
            <p style="font-size: 13px">
                @if($anticipo->codigo_caja!='')
                    <strong>COMPROBANTE:</strong> {{$anticipo->codigo_caja}}
                @else
                    <strong>NÚMERO:</strong> {{$anticipo->id}}<br>
                @endif
                <br>
                <strong>FECHA Y HORA:</strong> {{date( 'd/m/Y H:i', strtotime($fecha))}}<br>
                <strong>MONTO:</strong> {{number_format($anticipo->monto,2)}} BOB<br>
            </p>
        </div>
        <div style="width: 30%; float:right; text-align: right">
            <img src="{{ 'logos/'.Auth::user()->personal->empresa->logo}}" style="width: 150px; height: 75px;">
        </div>
    </div>
    {{--<br><br><br>--}}
    <div>
        <h3 style="text-align: center; margin-top: 10px ">ORDEN DE ANTICIPO</h3>
        <p style="font-size: 13px">
            <strong>CLIENTE:</strong> {{$anticipo->formularioLiquidacion->cliente->nombre}}<br>
            <strong>PRODUCTOR:</strong> {{$anticipo->formularioLiquidacion->cliente->cooperativa->razon_social}}<br>
            @if($anticipo->motivo) <strong>CONCEPTO:</strong> {{$anticipo->motivo}}<br>@endif
            <strong>LOTE:</strong> {{$anticipo->formularioLiquidacion->lote}} <br>
        </p>
    </div>
    <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: -10px">
    <h4 style="text-align: center; margin-top: -5px">HISTORIAL DE ANTICIPOS</h4>
    <table style="width: 100%">
        <thead>
        <tr style="font-size: 13px; font-weight: normal; text-align: left ">
            <th style=" text-align: left">#</th>
            <th style="text-align: left">Fecha</th>
            <th style="text-align: left">Monto</th>
        </tr>
        </thead>
        <tbody>
        @foreach($historial as $anticipo)
            <tr style="font-size: 13px; font-weight: normal; text-align: left ">
                <td style="width: 20px">{{$loop->iteration}}</td>
                <td style="width: 100px">{{ date( 'd/m/Y', strtotime($anticipo->fecha))}}</td>
                <td style="width: 100px">{{number_format($anticipo->monto,2)}} BOB</td>
            </tr>
        @endforeach
        <tr style="font-size: 13px; font-weight: normal; text-align: left ">
            <td></td>
            <td><strong>TOTAL</strong></td>
            <td>{{number_format($historial->sum('monto'),2)}} BOB</td>
        </tr>
        </tbody>
    </table>
    <p style="font-size: 13px; margin-top: -2px"><strong>SON:</strong> {{$literal}}.</p>
    <br><br>
    <table style="width: 100%; text-align: center">
        <tr style="font-size: 13px; ">
            <td></td>
            <td class="center" style="width: 45%">
                <div style=" float:right; color: transparent; height:106px; width: 100%; margin-top: -20px">
                        <img
                            src="{{ 'firmas/'.$firmaCaja}}"
                            alt="."
                            style="height: 100%; width: 90%">

                </div>
                <br>
                <strong>RESPONSABLE DE CAJA:</strong></td>
            <td class="center" style="width: 45%">
                <div style=" float:right; color: transparent; height:106px; width: 100%; margin-top: -20px">
                    <img src="{{ 'firmas/'.$anticipo->formularioLiquidacion->cliente->firma}}" alt="."
                         style="height: 100%; width: 80%">
                </div>
                <br>
                <strong>RECIBÍ CONFORME:</strong></td>
            <td></td>

        </tr>
        <tr style="font-size: 13px; font-weight: normal; ">
            <td></td>

            <td class="center">{{$nombreCaja}}</td>
            <td class="center">{{$cliente->nombre}}</td>
            <td></td>

        </tr>

    </table>
</div>
