<head>
    <title>Pago Laboratorio </title>
</head>
<div class="centro" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif;">
    <div style="width: 100%;">
        <div style="width: 70%; float:left;">
            <p style="font-size: 13px">
                <strong>FECHA Y HORA:</strong> {{date( 'd/m/Y H:i', strtotime($pagos[0]->created_at))}}<br>
                <strong>MONTO:</strong> {{number_format($pagos->sum('monto'),2)}} BOB<br>
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
            @if($tipo=='laboratorio') ORDEN DE PAGO DE ANÁLISIS @else ORDEN DE PAGO DE PESAJE @endif
        </h3>
        <p style="font-size: 13px">
            <strong>PROVEEDOR:</strong>
           {{$pagos[0]->origen->proveedor->nombre}}

            <br>
            <strong>CONCEPTO:</strong> @if($tipo=='laboratorio') ANÁLISIS DE MINERAL @else GASTOS DE PESAJE DE
            MINERAL @endif<br>
            <strong>FACTURA:</strong> {{$pagos[0]->origen->factura}} <br>
            <strong>MÉTODO DE PAGO:</strong> {{$pagos[0]->metodo}} <br>

        </p>
    </div>
    <br>
    <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: -10px">
    <br>
    <h4 style="text-align: center; margin-top: -5px">
        PAGOS INCLUIDOS
    </h4>
    <table style="width: 100%">
        <thead>
        <tr style="font-size: 13px; font-weight: normal; text-align: left ">
            <th style=" text-align: left">#</th>
            <th style="text-align: left">Comprobante</th>
            <th style="text-align: left">Glosa</th>
            <th style="text-align: left">Monto</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pagos as $movimiento)
            <tr style="font-size: 13px; font-weight: normal; text-align: left ">
                <td style="width: 20px">{{$loop->iteration}}</td>
                <td style="width: 100px">{{ $movimiento->codigo}}</td>
                <td style="width: 450px">
                    {{ $movimiento->glosa}}
                </td>
                <td style="width: 100px">{{number_format($movimiento->monto,2)}} BOB</td>
            </tr>
        @endforeach
        <tr style="font-size: 13px; font-weight: normal; text-align: left ">
            <td></td>
            <td colspan="2"><strong>TOTAL</strong></td>
            <td>{{number_format($pagos->sum('monto'),2)}} BOB</td>
        </tr>
        </tbody>
    </table>
    <p style="font-size: 13px; margin-top: -2px"><strong>SON:</strong> {{$literal}}.</p>
    <br><br><br><br><br>
    <table style="width: 100%; text-align: center">
        <tr style="font-size: 13px; ">
            <td class="center"><strong>RESPONSABLE DE CAJA</strong></td>
            <td class="center">
                <strong>
                    RECIBÍ CONFORME
                </strong>
            </td>
        </tr>
        <tr style="font-size: 13px; font-weight: normal; ">
            <td class="center">VICTOR PEÑA AFINO</td>
            <td class="center">
                {!! $pagos[0]->origen->proveedor->nombre !!}
            </td>
        </tr>


    </table>
</div>
