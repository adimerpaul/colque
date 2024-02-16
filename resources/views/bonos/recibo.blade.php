<head>
    <title>Devolucion {{$devolucion->codigo_caja}}</title>
</head>
<div class="centro" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif;">
    <div style="width: 100%;">
        <div style="width: 70%; float:left;">
            <p style="font-size: 13px">
                @if($devolucion->codigo_caja!='')
                    <strong>COMPROBANTE:</strong> {{$devolucion->codigo_caja}} <br>
                @endif
                <strong>FECHA:</strong> {{date( 'd/m/Y H:i', strtotime($fecha))}}<br>
                    <strong>MONTO:</strong> {{$devolucion->monto}} BOB<br>
            </p>
        </div>
        <div style="width: 30%; float:right; text-align: right">
            <img src="{{ 'logos/logo.png'}}" style="width: 150px; height: 75px;">
        </div>
    </div>
    {{--<br><br><br>--}}
    <div>
        <br><br><br><br>

        <h3 style="text-align: center; margin-top: 10px ">ORDEN DE DEVOLUCIÓN</h3>
        <p style="font-size: 13px">
            <strong>CLIENTE:</strong> {{$cliente->nombre}}<br>
            <strong>LOTE:</strong> {{$devolucion->formularioLiquidacion->lote}} <br>
        </p>
    </div>
    <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: -10px">
    <h4 style="text-align: center; margin-top: -5px">HISTORIAL DE DEVOLUCIONES</h4>
    <table style="width: 100%">
        <thead>
        <tr style="font-size: 13px; font-weight: normal; text-align: left ">
            <th style=" text-align: left">#</th>
            <th style="text-align: left">Fecha</th>
            <th style="text-align: left">Motivo</th>
            <th style="text-align: left">Monto</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bonos as $bono)
            <tr style="font-size: 13px; font-weight: normal; text-align: left ">
                <td style="width: 20px">{{$loop->iteration}}</td>
                <td style="width: 100px">{{ date( 'd/m/Y', strtotime($bono->created_at))}}</td>
                <td style="width: 100px">{{ $bono->motivo}}</td>
                <td  style="width: 100px">{{number_format($bono->monto,2)}} BOB</td>
            </tr>
        @endforeach
        <tr style="font-size: 13px; font-weight: normal; text-align: left ">
            <td colspan="2"></td>
            <td><strong>TOTAL</strong></td>
            <td>{{number_format($bonos->sum('monto'),2)}} BOB</td>
        </tr>
        </tbody>
    </table>
    <p style="font-size: 13px; margin-top: -2px"><strong>SON:</strong> {{$literal}}.</p>
    <br><br><br><br>
    <table  style="width: 100%; text-align: center">
        <tr style="font-size: 13px; ">
            <td class="center" style="width:33%"><strong>RESPONSABLE DE CAJA:</strong></td>
            <td class="center" style="width:33%"><strong>ENTREGUÉ CONFORME:</strong></td>
            <td class="center" style="width:34%"><strong>ENTREGUÉ CONFORME:</strong></td>
        </tr>
        <tr style="font-size: 13px; font-weight: normal; ">
            <td class="center" >VICTOR PEÑA AFINO</td>
            <td class="center">{{$cliente->nombre}}</td>
            <td class="center">Nombre:...................................</td>
        </tr>
        <tr style="font-size: 13px; font-weight: normal; ">
            <td colspan="2"></td>
            <td class="center"> Carnet:.....................................</td>
        </tr>

    </table>
</div>

