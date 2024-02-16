
<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="kardex-tabla" name="kardex-tabla">
        <thead>
        <tr>
            <th colspan="17" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>DETALLE DESCUENTOS / RETENCIONES
                <br>{{strtoupper($retencionPago->quincena)}}
                <br>PRODUCTOR: {{$retencionPago->cooperativa->razon_social}}
                <br>

            </th>

        </tr>
        <tr>
            <th rowspan="2" style=" border: 1px solid black;">N°</th>
            <th rowspan="2" style=" border: 1px solid black;" id="fechaRecepcion">FEC. REC.</th>
            <th rowspan="2" style=" border: 1px solid black;" id="loteCompra">LOTE DE COMPRA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="cliente">CLIENTE</th>
            <th rowspan="2" style=" border: 1px solid black;" id="pesoNetoSeco">PESO NETO SECO (Kg)</th>
            <th rowspan="2" style=" border: 1px solid black;" id="valorNetoVenta">VALOR NETO VENTA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="regaliaMinera">REGALIA MINERA</th>
            @if($nroRetenciones >0)
                <th colspan="{{$nroRetenciones}}" style=" border: 1px solid black; text-align: center" id="retencionesDeLey" class="retencionesDeLey">RETENCIONES DE LEY</th>
            @endif
            @if($nroDescuentos >0)
                <th colspan="{{$nroDescuentos}}" style=" border: 1px solid black; text-align: center" id="descuentosInstitucionales" class="descuentosInstitucionales">DESCUENTOS INSTITUCIONALES</th>
            @endif

            <th rowspan="2" style=" border: 1px solid black;" id="totalRetencionesDescuento">TOTAL RETENCIONES Y DESCUENTOS</th>
            @if($nroBonificaciones >0)
                <th colspan="{{$nroBonificaciones}}" style=" border: 1px solid black; text-align: center" id="bonificaciones" class="bonificaciones">BONIFICACIONES</th>
            @endif
            <th rowspan="2" style=" border: 1px solid black;" id="liquidoPagable">LIQUIDO PAGABLE</th>
            <th rowspan="2" style=" border: 1px solid black;" id="anticipos">ANTICIPO/ENTREGA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="aporteFundacion">APORTE FUNDACIÓN</th>
            <th rowspan="2" style=" border: 1px solid black;" id="saldoFavor">SALDO A FAVOR</th>
        </tr>
        <tr>
            @foreach($retenciones as $retencion)
                <th style=" border: 1px solid black;" class="retencionesDeLey"
                    id="{{$retencion->nombre}}">{{$retencion->nombre}}</th>
            @endforeach
            @foreach($descuentos as $descuento)
                <th style=" border: 1px solid black;" class="descuentosInstitucionales"
                    id="{{$descuento->nombre}}">{{$descuento->nombre}}</th>
            @endforeach
                @foreach($bonificaciones as $bonificacion)
                    <th style=" border: 1px solid black;" class="bonificaciones"
                        id="{{$bonificacion->nombre}}">{{$bonificacion->nombre}}</th>
                @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($formularios as $formulario)
            <tr>
                <td style=" border: 1px solid black;">{{$loop->iteration}}</td>
                <td style=" border: 1px solid black;" id="fechaRecepcionTd" class="fechaRecepcionTd">{{ date('d/m/y', strtotime($formulario->created_at)) }}</td>
                <td style=" border: 1px solid black;" id="loteCompraTd" clas="loteCompraTd">{{ $formulario->lote_sin_gestion }}</td>
                <td style=" border: 1px solid black;" id="clienteTd" class="clienteTd">{{ $formulario->cliente->nombre}}</td>
                <td style=" border: 1px solid black;" id="pesoNetoSecoTd" class="pesoNetoSecoTd">{{number_format($formulario->peso_seco,2, ',', '')}}</td>

                <td style=" border: 1px solid black;" id="valorNetoVentaTd" class="valorNetoVentaTd">{{number_format($formulario->neto_venta,2, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="regaliaMineraTd" class="regaliaMineraTd">{{number_format($formulario->regalia_minera,2, ',', '') }}</td>

                @foreach($retenciones as $retencion)
                    <td style=" border: 1px solid black;" id="{{'retencion'.$loop->iteration}}" class="retencionesDeLeyTd">
                        {{number_format(($formulario->retenciones_cooperativa[$retencion->nombre]),2, ',', '')}}
                    </td>
                @endforeach
                @foreach($descuentos as $descuento)
                    <td style=" border: 1px solid black;" id="{{'descuento'.$loop->iteration}}" class="descuentosInstitucionalesTd">
                        {{number_format(($formulario->descuentos_cooperativa[$descuento->nombre]),2, ',', '')}}
                    </td>
                @endforeach

                <td style=" border: 1px solid black;" id="totalRetencionesDescuentoTd" class="totalRetencionesDescuentoTd">{{number_format(($formulario->total_retencion_descuento),2, ',', '') }}</td>

                @foreach($bonificaciones as $bonificacion)
                    <td style=" border: 1px solid black;" id="{{'bonificacion'.$loop->iteration}}" class="bonificacionesTd">
                        {{number_format(($formulario->bonificaciones_cooperativa[$bonificacion->nombre]),2, ',', '')}}
                    </td>
                @endforeach

                <td style=" border: 1px solid black;" id="liquidoPagableTd" class="liquidoPagableTd">{{number_format($formulario->liquido_pagable,2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="anticiposTd" class="anticiposTd">{{number_format($formulario->total_anticipo,2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="aporteFundacionTd" class="aporteFundacionTd">{{number_format(($formulario->aporte_fundacion),2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="saldoFavorTd" class="saldoFavorTd">{{number_format(($formulario->saldo_favor),2, ',', '') }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4" class="text-center" style=" border: 1px solid black;">
                <b style="text-align: center">
                    TOTALES
                </b>
            </td>
            <td style=" border: 1px solid black;" id="pesoNetoSecoTotal"><b> {{ number_format($formularios->sum('peso_seco'),2, ',', '')}}</b></td>
            <td style=" border: 1px solid black;" id="valorNetoVentaTotal"><b> {{ number_format($formularios->sum('neto_venta'),2, ',', '')}}</b></td>
            <td style=" border: 1px solid black; font-weight: bold" id="regaliaMineraTotal">{{ number_format($formularios->sum('regalia_minera'),2, ',', '')}}</td>
            @foreach($retenciones as $retencion)
                <td style=" border: 1px solid black; font-weight: bold" class="retencionesDeLeyTotal">{{number_format(($retencionesTotales[$retencion->nombre]),2, ',', '')}}</td>
            @endforeach
            @foreach($descuentos as $descuento)
                <td style=" border: 1px solid black; font-weight: bold" class="descuentosInstitucionalesTotal">{{number_format(($descuentosTotales[$descuento->nombre]),2, ',', '')}}</td>
            @endforeach

            <td style=" border: 1px solid black; font-weight: bold" id="totalRetencionesDescuentoTotal">{{ number_format($formularios->sum('total_retencion_descuento'),2, ',', '')}}</td>

            @foreach($bonificaciones as $bonificacion)
                <td style=" border: 1px solid black; font-weight: bold" class="bonificacionesTotal">{{number_format(($bonificacionesTotales[$bonificacion->nombre]),2, ',', '')}}</td>
            @endforeach

            <td style=" border: 1px solid black; font-weight: bold" id="liquidoPagableTotal">{{ number_format($formularios->sum('liquido_pagable'),2, ',', '')}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="anticiposTotal">{{ number_format($formularios->sum('total_anticipo'),2, ',', '')}}</td>

            <td style=" border: 1px solid black; font-weight: bold" id="aporteFundacionTotal">{{ number_format($formularios->sum('aporte_fundacion'),2, ',', '')}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="saldoFavorTotal">{{ number_format($formularios->where('saldo_favor','>', '0.00')->sum('saldo_favor'),2, ',', '')}}</td>

        </tr>
        </tbody>
    </table>
</div>
<script>

    function agregarComma(nStr) {
        return nStr.replace('.', ',')
    }

</script>
