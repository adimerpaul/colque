<div class="centro" style="padding-left: 25px; font-family: Arial, Helvetica, sans-serif;">
    <div style="width: 100%;">
        <div style="width: 70%; float:left;">
            <p style="font-size: 13px">
                <strong>FECHA:</strong> {{date( 'd/m/Y', strtotime($fecha))}}<br>
            </p>
        </div>
        <div style="width: 30%; float:right; text-align: right">
            <img src="{{ 'logos/'.Auth::user()->personal->empresa->logo}}" style="width: 160px; height: 75px;">
        </div>
    </div>
    {{--<br><br><br>--}}
    <div>
        <h3 style="text-align: center; margin-top: 100px ">COMPROBANTE DE EGRESOS POR FECHA</h3>

    </div>
    <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: -10px">
    <table style="width: 100%">
        <thead>
        <tr style="font-size: 13px; font-weight: normal; text-align: left ">
            <th style=" text-align: left">#</th>
            <th style="text-align: left">Nro. Lote</th>
            <th style="text-align: left">Código Caja</th>
            <th style="text-align: left">Cliente</th>
            <th style="text-align: left">Producto</th>
            <th style="text-align: left">Monto</th>
        </tr>
        </thead>
        <tbody>
        @foreach($formularios as $formulario)
            <tr style="font-size: 13px; font-weight: normal; text-align: left ">
                <td style="width: 20px">{{$loop->iteration}}</td>
                <td style="width: 100px">{{ $formulario->lote}}
                <td style="width: 100px">{{ $formulario->codigo_caja}}</td>
                <td style="width: 100px">{{ $formulario->cliente->nombre}}</td>
                <td style="width: 100px">{{ $formulario->producto}}</td>
                <td  style="width: 100px">{{number_format($formulario->saldo_favor,2)}} BOB</td>
            </tr>
        @endforeach
        <tr style="font-size: 13px; font-weight: normal; text-align: left ">
            <td colspan="4"></td>
            <td><strong>TOTAL</strong></td>
            <td>{{number_format($formularios->sum('saldo_favor'),2)}} BOB</td>
        </tr>
        </tbody>
    </table>
    <br>
    <p style="font-size: 13px;"><strong>SON:</strong> {{$literal}}.</p>
    <br><br><br><br><br><br>
    <table  style="width: 100%; text-align: center">
        <tr style="font-size: 13px; ">
            <td class="center"><strong>RESPONSABLE DE CAJA</strong></td>
        </tr>
        <tr style="font-size: 13px; font-weight: normal; ">
            <td class="center">{{auth()->user()->personal->nombre_completo}}</td>
        </tr>

    </table>
</div>
