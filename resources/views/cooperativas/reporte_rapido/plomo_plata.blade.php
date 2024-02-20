<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="kardex-tabla" name="kardex-tabla">
        <thead>
        <tr>
            <th colspan="21" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>KARDEX INVENTARIO
                <br>PRODUCTO: PLOMO (PB) - PLATA (AG)
                <br>
                @if($fechaInicio)<b id="fechas"></b>@endif
                <br>PRODUCTOR: {{$productor->razon_social}}
                <br><br>
            </th>

        </tr>
        <tr>
            <th rowspan="2" style=" border: 1px solid black;">N°</th>
            <th rowspan="2" style=" border: 1px solid black;" id="fechaRecepcion">FEC. REC.</th>
            <th rowspan="2" style=" border: 1px solid black;" id="fechaLiquidacion">FEC. LIQ</th>
            <th rowspan="2" style=" border: 1px solid black;" id="loteCompra">LOTE DE COMPRA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="cliente">CLIENTE</th>
            <th rowspan="2" style=" border: 1px solid black;" id="pesoNetoSeco">PESO NETO SECO (Kg)</th>
            <th colspan="2" style=" border: 1px solid black; text-align: center" id="pb" class="pb">PB</th>
            <th colspan="2" style=" border: 1px solid black; text-align: center" id="ag" class="ag">AG</th>
            <th rowspan="2" style=" border: 1px solid black;" id="valorPorTonelada">VALOR POR TONELADA USD</th>
            <th rowspan="2" style=" border: 1px solid black;" id="valorNetoVenta">VALOR NETO VENTA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="regaliaMinera">REGALIA MINERA</th>

            @if($nroRetenciones >0)
                <th colspan="{{$nroRetenciones}}" style=" border: 1px solid black; text-align: center" id="retencionesDeLey" class="retencionesDeLey">RETENCIONES DE LEY</th>
            @endif
            @if($nroDescuentos >0)
                <th colspan="{{$nroDescuentos}}" style=" border: 1px solid black; text-align: center" id="descuentosInstitucionales" class="descuentosInstitucionales">DESCUENTOS INSTITUCIONALES</th>
            @endif
            <th rowspan="2" style=" border: 1px solid black;" id="totalRetencionesDescuento">TOTAL RETENCIONES Y
                DESCUENTOS
            </th>

            <th rowspan="2" style=" border: 1px solid black;" id="totalBonificaciones">TOTAL BONIFICACIONES</th>
            <th rowspan="2" style=" border: 1px solid black;" id="liquidoPagable">LIQUIDO PAGABLE</th>

        </tr>
        <tr>
            <th style=" border: 1px solid black;" id="pb" class="pb">%Pb</th>
            <th style=" border: 1px solid black;" id="pb" class="pb">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="ag" class="ag">DM Ag</th>
            <th style=" border: 1px solid black;" id="ag" class="ag">Peso Fino (Kg)</th>

            @foreach($retenciones as $retencion)
                <th style=" border: 1px solid black;"
                    id="{{$retencion->nombre}}" class="retencionesDeLey">{{$retencion->nombre}}</th>
            @endforeach
            @foreach($descuentos as $descuento)
                <th style=" border: 1px solid black;"
                    id="{{$descuento->nombre}}" class="descuentosInstitucionales">{{$descuento->nombre}}</th>
            @endforeach


        </tr>
        </thead>
        <tbody>
        @foreach($formularios as $formulario)
            <tr>
                <td style=" border: 1px solid black;">{{$loop->iteration}}</td>
                <td style=" border: 1px solid black;" id="fechaRecepcionTd"
                    class="fechaRecepcionTd">{{ date('d/m/y', strtotime($formulario->created_at)) }}</td>
                <td style=" border: 1px solid black;" id="fechaLiquidacionTd"
                    class="fechaLiquidacionTd">{{ date('d/m/y', strtotime($formulario->fecha_liquidacion)) }}</td>
                <td style=" border: 1px solid black;" id="loteCompraTd" clas="loteCompraTd">{{ $formulario->lote_sin_gestion }}</td>

                <td style=" border: 1px solid black;" id="clienteTd"
                    class="clienteTd">{{ $formulario->cliente->nombre}}</td>
                <td style=" border: 1px solid black;" id="pesoNetoSecoTd"
                    class="pesoNetoSecoTd">{{round($formulario->peso_seco,5)}}</td>
                <td style=" border: 1px solid black;" id="leyPbTd" class="pbTd">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Pb') {{round( $lab->promedio,3)}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="pesoFinoPbTd"
                    class="pbTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Pb'){{round(($lab->promedio * $formulario->peso_seco / 100),5)}}  @endif
                    @endforeach
                </td>

                <td style=" border: 1px solid black;" class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{round($lab->promedio,3)}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{round(($lab->promedio * $formulario->peso_seco / 10000),5)}}  @endif
                    @endforeach
                </td>

                <td style=" border: 1px solid black;" id="valorPorToneladaTd"
                    class="valorPorToneladaTd">{{round($formulario->valor_por_tonelada,2)}}</td>
                <td style=" border: 1px solid black;" id="valorNetoVentaTd"
                    class="valorNetoVentaTd">{{round($formulario->neto_venta,2)}}</td>
                <td style=" border: 1px solid black;" id="regaliaMineraTd"
                    class="regaliaMineraTd">{{round($formulario->regalia_minera,2) }}</td>

                @foreach($retenciones as $retencion)
                    <td style=" border: 1px solid black;" id="{{'retencion'.$loop->iteration}}" class="retencionesDeLeyTd">
                        {{round(($formulario->retenciones_cooperativa[$retencion->nombre]),2)}}
                    </td>
                @endforeach
                @foreach($descuentos as $descuento)
                    <td style=" border: 1px solid black;" id="{{'descuento'.$loop->iteration}}" class="descuentosInstitucionalesTd">
                        {{round(($formulario->descuentos_cooperativa[$descuento->nombre]),2)}}
                    </td>
                @endforeach

                <td style=" border: 1px solid black;" id="totalRetencionesDescuentoTd"
                    class="totalRetencionesDescuentoTd">{{round(($formulario->total_retencion_descuento),2) }}</td>

                <td style=" border: 1px solid black;" id="totalBonificacionesTd"
                    class="totalBonificacionesTd">{{round($formulario->total_bonificacion,2) }}</td>

                <td style=" border: 1px solid black;" id="liquidoPagableTd"
                    class="liquidoPagableTd">{{round($formulario->liquido_pagable,2) }}</td>

            </tr>

        @endforeach
        <tr>
            <td colspan="5" class="text-center" style=" border: 1px solid black;">
                <b style="text-align: center">
                    TOTALES
                </b>
            </td>
            <td style=" border: 1px solid black;" id="pesoNetoSecoTotal">
                <b> {{ round($formularios->sum('peso_seco'),5)}}</b></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyPbTotal" class="pbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoPbTotal" class="pbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyAgTotal" class="agTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoAgTotal" class="agTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="valorPorToneladaTotal"></td>
            <td style=" border: 1px solid black;" id="valorNetoVentaTotal">
                <b> {{ round($formularios->sum('neto_venta'),2)}}</b></td>
            <td style=" border: 1px solid black; font-weight: bold" id="regaliaMineraTotal">{{ round($formularios->sum('regalia_minera'),2)}}</td>
            @foreach($retenciones as $retencion)
                <td style=" border: 1px solid black; font-weight: bold" class="retencionesDeLeyTotal">{{round(($retencionesTotales[$retencion->nombre]),2)}}</td>
            @endforeach
            @foreach($descuentos as $descuento)
                <td style=" border: 1px solid black; font-weight: bold" class="descuentosInstitucionalesTotal">{{round(($descuentosTotales[$descuento->nombre]),2)}}</td>
            @endforeach
            <td style=" border: 1px solid black; font-weight: bold" id="totalRetencionesDescuentoTotal">{{ round($formularios->sum('total_retencion_descuento'),2)}}</td>

            <td style=" border: 1px solid black; font-weight: bold" id="totalBonificacionesTotal">{{ round($formularios->sum('total_bonificacion'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="liquidoPagableTotal">{{ round($formularios->sum('liquido_pagable'),2)}}</td>

        </tr>
        </tbody>
    </table>
</div>
<script>

    document.getElementById("fechas").innerHTML = "CORRESPONDIENTE A LAS FECHAS: {{ date('d/m/y', strtotime($fechaInicio)) }} AL {{ date('d/m/y', strtotime($fechaFinal)) }}";
    var table = document.getElementById("kardex-tabla"), sumaLeyPb = 0, sumaPesoFinoPb = 0
        , sumaPesoFinoAg = 0, sumaPesoNetoSeco = 0,
        sumaValorTonelada=0, sumaLeyAg = 0,
        nroDescRet=parseInt("{{$nroDescRet}}");

    for (var i = 3; i < (table.rows.length - 1); i++) {
        sumaPesoFinoPb = parseFloat(sumaPesoFinoPb) + parseFloat(table.rows[i].cells[7].innerHTML.replace(/,/g, "."));
        sumaPesoFinoAg = parseFloat(sumaPesoFinoAg) + parseFloat(table.rows[i].cells[9].innerHTML.replace(/,/g, "."));

        sumaPesoNetoSeco = parseFloat(sumaPesoNetoSeco) + parseFloat(table.rows[i].cells[5].innerHTML.replace(/,/g, "."));
        sumaValorTonelada= parseFloat(sumaValorTonelada) + (parseFloat(table.rows[i].cells[5].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[10].innerHTML.replace(/,/g, ".")));

    }
    sumaLeyPb = ((parseFloat(sumaPesoFinoPb) / parseFloat(sumaPesoNetoSeco)) * 100);
    sumaLeyAg = ((parseFloat(sumaPesoFinoAg) / parseFloat(sumaPesoNetoSeco)) * 10000);
    sumaValorTonelada = parseFloat(sumaValorTonelada) / parseFloat(sumaPesoNetoSeco);

    document.getElementById("leyPbTotal").innerHTML = (sumaLeyPb.toFixed(3));
    document.getElementById("pesoFinoPbTotal").innerHTML = (sumaPesoFinoPb.toFixed(5));
    document.getElementById("leyAgTotal").innerHTML = (sumaLeyAg.toFixed(3));
    document.getElementById("pesoFinoAgTotal").innerHTML = (sumaPesoFinoAg.toFixed(5));

    document.getElementById("valorPorToneladaTotal").innerHTML = (sumaValorTonelada.toFixed(2));


</script>
