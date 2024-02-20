<head>
    <title>Venta {{$pago->codigo}}</title>
</head>
<div class="centro" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif;">
    <div style="width: 100%;">
        <div style="width: 70%; float:left;">
            <p style="font-size: 13px">
                    <strong>COMPROBANTE:</strong> {{$pago->codigo}}
                <br>
                <strong>FECHA Y HORA:</strong> {{date( 'd/m/Y H:i', strtotime($pago->created_at))}}<br>
                <strong>MONTO:</strong> {{number_format($pago->monto,2)}} BOB<br>
            </p>
        </div>
        <div style="width: 30%; float:right; text-align: right">
            <img src="{{ 'logos/'.Auth::user()->personal->empresa->logo}}" style="width: 150px; height: 75px;">
        </div>
    </div>
    <br><br><br>
    <br><br>
    <div>
        <h3 style="text-align: center; margin-top: -10px ">ORDEN DE COBRO POR VENTA</h3>
        <p style="font-size: 13px; margin-top: -8px">
            <strong>COMPRADOR:</strong> {{$pago->origen->comprador->razon_social}}<br>
            <strong>CONCEPTO:</strong> {{$pago->glosa}}<br>
            <strong>LOTE:</strong> {{$pago->origen->lote}} <br>
            @if($pago->metodo==\App\Patrones\TipoPago::CuentaBancaria)<strong>BANCO:</strong> {{$pago->banco}} <br>@endif
        </p>
    </div>
    <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: -10px">
    <h4 style="text-align: center; margin-top: -5px">HISTORIAL DE ANTICIPOS</h4>
    <table style="width: 100%; margin-top: -15px">
        <thead>
        <tr style="font-size: 13px; font-weight: normal; text-align: left ">
            <th style=" text-align: left">#</th>
            <th style="text-align: left">Fecha</th>
            <th style="text-align: left">Monto</th>
        </tr>
        </thead>
        <tbody>
        @foreach($historial as $pago)
            <tr style="font-size: 13px; font-weight: normal; text-align: left ">
                <td style="width: 20px">{{$loop->iteration}}</td>
                <td style="width: 100px">{{ date( 'd/m/Y', strtotime($pago->updated_at))}}</td>
                <td style="width: 100px">{{number_format($pago->monto,2)}} BOB</td>
            </tr>
        @endforeach
        <tr style="font-size: 13px; font-weight: normal; text-align: left ">
            <td></td>
            <td><strong>TOTAL</strong></td>
            <td>{{number_format($historial->sum('monto'),2)}} BOB</td>
        </tr>
        </tbody>
    </table>

    <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: 10px">

    <p style="font-size: 13px; margin-top: -2px"><strong>SON:</strong> {{$literal}}.</p>
    <br><br><br><br>
    <table style="width: 100%; text-align: center">
        <tr style="font-size: 13px; ">
            <td class="center"><strong>RESPONSABLE DE CAJA:</strong></td>
            <td class="center"><strong>ENTREGUÉ CONFORME:</strong></td>
        </tr>
        <tr style="font-size: 13px; font-weight: normal; ">
            <td class="center">VICTOR PEÑA AFINO</td>
            <td class="center">Nombre:...................................</td>
        </tr>
        <tr style="font-size: 13px; font-weight: normal; ">
            <td></td>
            <td class="center"> Carnet:.....................................</td>
        </tr>

    </table>
</div>
