<head>
        <title>{{$retencionPago->codigo_caja}}</title>
</head>
<div style="margin: 2px; display: inline-block;">
    <img src="{{ 'logos/'.Auth::user()->personal->empresa->logo}}" style="width: 145px; height: 70px;" align="right">
</div>
<div style="margin-top: 60px; text-align: center">
    <b style="font-size:12px; color: #042E44"><label>DETALLE DESCUENTOS / RETENCIONES {{strtoupper($retencionPago->codigo_caja)}}</label></b>
    <br><b style="font-size:12px; color: #042E44"><label>{{strtoupper($retencionPago->quincenas)}}</label></b>
    <br><b style="font-size:12px; color: #042E44"><label>RETENCIONES PAGADAS: {{strtoupper($retencionPago->nombres_retenciones)}}</label></b>
    <br><b style="font-size:12px; color: #042E44"><label>PRODUCTOR: {{$retPagoUltimo->cooperativa->razon_social}}</label></b>
</div>
<br>
<table border="1">
    <thead>
    <tr>
        <th rowspan="2">N°</th>
        <th rowspan="2" id="fechaRecepcion">FEC. LIQ.</th>
        <th rowspan="2" id="loteCompra">LOTE DE COMPRA</th>
        <th rowspan="2" id="cliente">CLIENTE</th>

            <th rowspan="2" id="pesoNetoSeco">PESO NETO SECO (Kg)</th>
            <th rowspan="2" id="valorNetoVenta">VALOR NETO VENTA</th>
            <th rowspan="2" id="regaliaMinera">REGALIA MINERA</th>
        @if($nroRetenciones >0)
            <th colspan="{{$nroRetenciones}}" style=" text-align: center" id="retencionesDeLey"
                class="retencionesDeLey">RETENCIONES DE LEY
            </th>
        @endif
        @if($nroDescuentos >0)
            <th colspan="{{$nroDescuentos}}" style=" text-align: center"
                id="descuentosInstitucionales" class="descuentosInstitucionales">DESCUENTOS INSTITUCIONALES
            </th>
        @endif


            <th rowspan="2" id="totalRetencionesDescuento">TOTAL RETENCIONES Y DESCUENTOS</th>

    </tr>
    <tr>
        @foreach($retenciones as $retencion)
            <th class="retencionesDeLey"
                id="{{$retencion->nombre}}">{{$retencion->nombre}}</th>
        @endforeach
        @foreach($descuentos as $descuento)
            <th class="descuentosInstitucionales"
                id="{{$descuento->nombre}}">{{$descuento->nombre}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($formularios as $formulario)
        <tr>
            <td class="plomo">{{$loop->iteration}}</td>
            <td class="plomo" id="fechaRecepcionTd"
                class="fechaRecepcionTd">{{ date('d/m/y', strtotime($formulario->fecha_liquidacion)) }}</td>
            <td class="plomo" id="loteCompraTd" clas="loteCompraTd">{{ $formulario->lote_sin_gestion }}</td>
            <td class="plomo" id="clienteTd" class="clienteTd">{{ $formulario->cliente->nombre}}</td>

                <td class="plomo" id="pesoNetoSecoTd"
                    class="pesoNetoSecoTd">{{number_format($formulario->peso_seco,2, ',', '')}}</td>

                <td class="plomo" id="valorNetoVentaTd"
                    class="valorNetoVentaTd">{{number_format($formulario->neto_venta,2, ',', '')}}</td>
                <td class="plomo" id="regaliaMineraTd"
                    class="regaliaMineraTd">{{number_format($formulario->regalia_minera,2, ',', '') }}</td>

            @foreach($retenciones as $retencion)
                <td class="plomo" id="{{'retencion'.$loop->iteration}}" class="retencionesDeLeyTd">
                    {{number_format(($formulario->retenciones_cooperativa[$retencion->nombre]),2, ',', '')}}
                </td>
            @endforeach
            @foreach($descuentos as $descuento)
                <td class="plomo" id="{{'descuento'.$loop->iteration}}" class="descuentosInstitucionalesTd">
                    {{number_format(($formulario->descuentos_cooperativa[$descuento->nombre]),2, ',', '')}}
                </td>
            @endforeach

                <td class="plomo" id="totalRetencionesDescuentoTd"
                    class="totalRetencionesDescuentoTd">{{number_format(($formulario->total_retencion_descuento),2, ',', '') }}</td>


        </tr>
    @endforeach

    <tr>
        <td colspan="4" class="text-center">
            <b style="text-align: center">
                TOTALES
            </b>
        </td>
        <td id="pesoNetoSecoTotal"><b> {{ number_format($formularios->sum('peso_seco'),2, ',', '')}}</b></td>
        <td id="valorNetoVentaTotal"><b> {{ number_format($formularios->sum('neto_venta'),2, ',', '')}}</b></td>
        <td style=" font-weight: bold" id="regaliaMineraTotal">{{ number_format($formularios->sum('regalia_minera'),2, ',', '')}}</td>
        @foreach($retenciones as $retencion)
            <td style=" font-weight: bold" class="retencionesDeLeyTotal">{{number_format(($retencionesTotales[$retencion->nombre]),2, ',', '')}}</td>
        @endforeach
        @foreach($descuentos as $descuento)
            <td style=" font-weight: bold" class="descuentosInstitucionalesTotal">{{number_format(($descuentosTotales[$descuento->nombre]),2, ',', '')}}</td>
        @endforeach

        <td style=" font-weight: bold" id="totalRetencionesDescuentoTotal">{{ number_format($formularios->sum('total_retencion_descuento'),2, ',', '')}}</td>


    </tr>
    </tbody>

</table>


<style>
    table {
        border-collapse: collapse;
        width: 100%;
        color: #042E44;
        margin-bottom: 20px;
        font-family: Arial, Helvetica, sans-serif;
    }

    td {
        padding: 2px;
        padding-left: 3px;
        color: #042E44;
        font-size: 7px;
        font-family: Arial, Helvetica, sans-serif;
    }

    th {
        padding: 2px;
        padding-left: 3px;
        font-size: 7px;
    }

    b {
        font-size: 7px;
    }

    img {
        width: 180px;
        height: 85px;
        margin-top: -10px
    }

    label {
        color: #042E44;
        font-family: Arial, Helvetica, sans-serif;
    }

    .plomo {
        color: #647C84;
        border-color: #042E44;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 7px;
    }
</style>
