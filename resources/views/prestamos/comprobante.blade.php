<head>
    <title>Prestamo {{$prestamo->codigo}}</title>
</head>
<div class="centro" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif;">
    <div style="width: 100%;">
        <div style="width: 70%; float:left;">
            <p style="font-size: 13px">
                <strong>NÚMERO:</strong> {{$prestamo->codigo}}<br>
                <strong>FECHA Y HORA:</strong> {{date( 'd/m/Y H:i', strtotime($prestamo->created_at))}}<br>
                <strong>MONTO:</strong> {{number_format($prestamo->monto,2)}} BOB<br>
            </p>
        </div>
        <div style="width: 30%; float:right; text-align: right">
            <img src="{{ 'logos/logo.png'}}" style="width: 150px; height: 75px;">
        </div>
    </div>
    {{--<br><br><br>--}}
    <div>
        <h3 style="text-align: center; margin-top: 10px ">ORDEN DE PAGO POR PRÉSTAMO</h3>
        <p style="font-size: 13px">
            <strong>CLIENTE:</strong>
            {{$prestamo->origen->cliente->nombre}}
            <br>
            <strong>PRODUCTOR:</strong> {{$prestamo->origen->cliente->cooperativa->razon_social}}<br>
            <strong>MÉTODO DE PAGO:</strong> {{$prestamo->metodo}}<br>
            <strong>REGISTRADO POR:</strong> {{$prest->registrado->personal->nombre_completo}}<br>
            @if($prest->aprobadoPor) <strong>AUTORIZADO POR:</strong> {{$prest->aprobadoPor->personal->nombre_completo}}<br> @endif
            <strong>MOTIVO:</strong> {{$prest->motivo}}<br>
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
                    RECIBÍ CONFORME
                </strong>
            </td>
        </tr>
        <tr style="font-size: 13px; font-weight: normal; ">
            <td class="center">VICTOR PEÑA AFINO</td>
            <td class="center">{{$prestamo->origen->cliente->nombre}}</td>
        </tr>
    </table>
</div>
