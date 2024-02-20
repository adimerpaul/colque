<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="kardex-tabla" name="kardex-tabla">
        <thead>
        <tr>
            <th colspan="57" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>KARDEX INVENTARIO
                @if($productoLetra=='%')
                    <br>PRODUCTO: A - B - C : COMPLEJO
                @endif
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
            <th colspan="2" style=" border: 1px solid black; text-align: center" id="zn" class="zn">ZN</th>
            <th colspan="2" style=" border: 1px solid black; text-align: center" id="sn" class="sn">SN</th>
            <th colspan="2" style=" border: 1px solid black; text-align: center" id="au" class="au">AU</th>
            <th colspan="2" style=" border: 1px solid black; text-align: center" id="sb" class="sb">SB</th>
            <th colspan="2" style=" border: 1px solid black; text-align: center" id="cu" class="cu">CU</th>
            <th rowspan="2" style=" border: 1px solid black;" id="valorPorTonelada">VALOR POR TONELADA USD</th>
            <th rowspan="2" style=" border: 1px solid black;" id="valorNetoVenta">VALOR NETO VENTA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="regaliaMinera">REGALIA MINERA</th>
            @if($nroRetenciones >0)
                <th colspan="{{$nroRetenciones}}" style=" border: 1px solid black; text-align: center" id="retencionesDeLey" class="retencionesDeLey">RETENCIONES DE LEY</th>
            @endif
            @if($nroDescuentos >0)
                <th colspan="{{$nroDescuentos}}" style=" border: 1px solid black; text-align: center" id="descuentosInstitucionales" class="descuentosInstitucionales">DESCUENTOS INSTITUCIONALES</th>
            @endif

            <th rowspan="2" style=" border: 1px solid black;" id="totalRetencionesDescuento">TOTAL RETENCIONES Y DESCUENTOS</th>

            <th rowspan="2" style=" border: 1px solid black;" id="totalBonificaciones">TOTAL BONIFICACIONES</th>
            <th rowspan="2" style=" border: 1px solid black;" id="liquidoPagable">LIQUIDO PAGABLE</th>

        </tr>
        <tr>
            <th style=" border: 1px solid black;" id="pb" class="pb">%Pb</th>
            <th style=" border: 1px solid black;" id="pb" class="pb">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="ag" class="ag">DM Ag</th>
            <th style=" border: 1px solid black;" id="ag" class="ag">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="zn" class="zn">%Zn</th>
            <th style=" border: 1px solid black;" id="zn" class="zn">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="sn" class="sn">%Sn</th>
            <th style=" border: 1px solid black;" id="sn" class="sn">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="au" class="au">G/T Au</th>
            <th style=" border: 1px solid black;" id="au" class="au">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="sb" class="sb">%Sb</th>
            <th style=" border: 1px solid black;" id="sb" class="sb">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="cu" class="cu">%Cu</th>
            <th style=" border: 1px solid black;" id="cu" class="cu">Peso Fino (Kg)</th>

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
                <td style=" border: 1px solid black;" id="fechaRecepcionTd" class="fechaRecepcionTd">{{ date('d/m/y', strtotime($formulario->created_at)) }}</td>
                <td style=" border: 1px solid black;" id="fechaLiquidacionTd"
                    class="fechaLiquidacionTd">{{ date('d/m/y', strtotime($formulario->fecha_liquidacion)) }}</td>
                <td style=" border: 1px solid black;" id="loteCompraTd" clas="loteCompraTd">{{ $formulario->lote_sin_gestion }}</td>

                <td style=" border: 1px solid black;" id="clienteTd" class="clienteTd">{{ $formulario->cliente->nombre}}</td>
                <td style=" border: 1px solid black;" id="pesoNetoSecoTd" class="pesoNetoSecoTd">{{round($formulario->peso_seco,5)}}</td>
                <td style=" border: 1px solid black;" id="leyPbTd" class="pbTd">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Pb') {{round($lab->promedio,3)}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="pesoFinoPbTd" class="pbTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Pb'){{round(($lab->promedio * $formulario->peso_seco / 100),5)}}  @endif
                    @endforeach
                </td>

                <td style=" border: 1px solid black;" id="leyAgTd" class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{round($lab->promedio,3)}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="pesoFinoAgTd" class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{round(($lab->promedio * $formulario->peso_seco / 10000),3)}}  @endif
                    @endforeach
                </td>

                <td style=" border: 1px solid black;" id="leyZnTd" class="znTd">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Zn') {{round($lab->promedio,3)}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"  id="pesoFinoZnTd" class="znTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Zn'){{round(($lab->promedio * $formulario->peso_seco / 100),5)}}  @endif
                    @endforeach
                </td>

                <td style=" border: 1px solid black;" id="leySnTd" class="snTd">
                    {{!is_null($formulario->ley_sn) ? round($formulario->ley_sn,3):''}}
                </td>
                <td style=" border: 1px solid black;"  id="pesoFinoSnTd" class="snTd">  {{!is_null($formulario->ley_sn) ? round(($formulario->ley_sn * $formulario->peso_seco / 100),5):''}}
                </td>

                <td style=" border: 1px solid black;" id="leyAuTd" class="auTd">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Au') {{round($lab->promedio,3)}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"  id="pesoFinoAuTd" class="auTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Au'){{round(($lab->promedio * $formulario->peso_seco / 100),5)}}  @endif
                    @endforeach
                </td>

                <td style=" border: 1px solid black;" id="leySbTd" class="sbTd">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Sb') {{round($lab->promedio,3)}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"  id="pesoFinoSbTd" class="sbTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Sb'){{round(($lab->promedio * $formulario->peso_seco / 100),5)}}  @endif
                    @endforeach
                </td>

                <td style=" border: 1px solid black;" id="leyCuTd" class="cuTd">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Cu') {{round($lab->promedio,3)}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"  id="pesoFinoCuTd" class="cuTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Cu'){{round(($lab->promedio * $formulario->peso_seco / 100),5)}}  @endif
                    @endforeach
                </td>

                <td style=" border: 1px solid black;" id="valorPorToneladaTd" class="valorPorToneladaTd">{{round($formulario->valor_por_tonelada,2)}}</td>
                <td style=" border: 1px solid black;" id="valorNetoVentaTd" class="valorNetoVentaTd">{{round($formulario->neto_venta,2)}}</td>
                <td style=" border: 1px solid black;" id="regaliaMineraTd" class="regaliaMineraTd">{{round($formulario->regalia_minera,2) }}</td>

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

                <td style=" border: 1px solid black;" id="totalRetencionesDescuentoTd" class="totalRetencionesDescuentoTd">{{round(($formulario->total_retencion_descuento),2) }}</td>

                <td style=" border: 1px solid black;" id="totalBonificacionesTd" class="totalBonificacionesTd">{{round($formulario->total_bonificacion,2) }}</td>

                <td style=" border: 1px solid black;" id="liquidoPagableTd" class="liquidoPagableTd">{{round($formulario->liquido_pagable,2) }}</td>

            </tr>
        @endforeach
        <tr>
            <td colspan="5" class="text-center" style=" border: 1px solid black;">
                <b style="text-align: center">
                    TOTALES
                </b>
            </td>
            <td style=" border: 1px solid black;" id="pesoNetoSecoTotal"><b> {{ round($formularios->sum('peso_seco'),5)}}</b></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyPbTotal" class="pbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoPbTotal" class="pbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyAgTotal" class="agTd"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoAgTotal" class="agTd"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyZnTotal" class="znTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoZnTotal" class="znTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leySnTotal" class="snTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoSnTotal" class="snTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyAuTotal" class="auTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoAuTotal" class="auTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leySbTotal" class="sbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoSbTotal" class="sbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyCuTotal" class="cuTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoCuTotal" class="cuTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="valorPorToneladaTotal"></td>
            <td style=" border: 1px solid black;" id="valorNetoVentaTotal"><b> {{ round($formularios->sum('neto_venta'),2)}}</b></td>
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
        , sumaPesoFinoAg = 0
        , sumaPesoNetoSeco = 0, sumaValorTonelada=0
        , sumaLeyAg = 0, sumaPesoFinoZn = 0, sumaLeyZn = 0,
        sumaPesoFinoSb = 0, sumaLeySb = 0, sumaPesoFinoAu = 0, sumaLeyAu = 0,
        sumaPesoFinoCu = 0, sumaLeyCu = 0,
        sumaPesoFinoSn = 0, sumaLeySn = 0,
        nroDescRet=parseInt("{{$nroDescRet}}");
    for(var i = 3; i < (table.rows.length - 1); i++) {

        sumaPesoFinoPb = parseFloat(sumaPesoFinoPb) +
            (table.rows[i].cells[7].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[7].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoAg = parseFloat(sumaPesoFinoAg) +
            (table.rows[i].cells[9].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[9].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoZn = parseFloat(sumaPesoFinoZn) +
            (table.rows[i].cells[11].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[11].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoSn = parseFloat(sumaPesoFinoSn) +
            (table.rows[i].cells[13].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoAu = parseFloat(sumaPesoFinoAu) +
            (table.rows[i].cells[15].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[15].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoSb = parseFloat(sumaPesoFinoSb) +
            (table.rows[i].cells[17].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[17].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoCu = parseFloat(sumaPesoFinoCu) +
            (table.rows[i].cells[19].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[19].innerHTML.replace(/,/g, ".")));

        sumaPesoNetoSeco = parseFloat(sumaPesoNetoSeco) + parseFloat(table.rows[i].cells[5].innerHTML.replace(/,/g, "."));

        sumaValorTonelada= parseFloat(sumaValorTonelada) + (parseFloat(table.rows[i].cells[5].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[20].innerHTML.replace(/,/g, ".")));

    }

    sumaValorTonelada = parseFloat(sumaValorTonelada) / parseFloat(sumaPesoNetoSeco);

    sumaLeyPb = ((parseFloat(sumaPesoFinoPb) /  parseFloat(sumaPesoNetoSeco)) * 100);
    sumaLeyAg = ((parseFloat(sumaPesoFinoAg) /  parseFloat(sumaPesoNetoSeco)) * 10000);
    sumaLeyZn = ((parseFloat(sumaPesoFinoZn) /  parseFloat(sumaPesoNetoSeco)) * 100);
    sumaLeySn = ((parseFloat(sumaPesoFinoSn) /  parseFloat(sumaPesoNetoSeco)) * 100);
    sumaLeyAu = ((parseFloat(sumaPesoFinoAu) /  parseFloat(sumaPesoNetoSeco)) * 10000);
    sumaLeySb = ((parseFloat(sumaPesoFinoSb) /  parseFloat(sumaPesoNetoSeco)) * 100);
    sumaLeyCu = ((parseFloat(sumaPesoFinoCu) /  parseFloat(sumaPesoNetoSeco)) * 100);


    document.getElementById("leyPbTotal").innerHTML = (sumaLeyPb.toFixed(3));
    document.getElementById("leyZnTotal").innerHTML = (sumaLeyZn.toFixed(3));
    document.getElementById("leySnTotal").innerHTML = (sumaLeySn.toFixed(3));
    document.getElementById("leyAuTotal").innerHTML = (sumaLeyAu.toFixed(3));
    document.getElementById("leySbTotal").innerHTML = (sumaLeySb.toFixed(3));
    document.getElementById("leyCuTotal").innerHTML = (sumaLeyCu.toFixed(3));

    document.getElementById("pesoFinoPbTotal").innerHTML = (sumaPesoFinoPb.toFixed(5));
    document.getElementById("pesoFinoZnTotal").innerHTML = (sumaPesoFinoZn.toFixed(5));
    document.getElementById("leyAgTotal").innerHTML = (sumaLeyAg.toFixed(3));
    document.getElementById("pesoFinoAgTotal").innerHTML = (sumaPesoFinoAg.toFixed(5));
    document.getElementById("pesoFinoSnTotal").innerHTML = (sumaPesoFinoSn.toFixed(5));
    document.getElementById("pesoFinoAuTotal").innerHTML = (sumaPesoFinoAu.toFixed(5));
    document.getElementById("pesoFinoSbTotal").innerHTML = (sumaPesoFinoSb.toFixed(5));
    document.getElementById("pesoFinoCuTotal").innerHTML = (sumaPesoFinoCu.toFixed(5));

    document.getElementById("valorPorToneladaTotal").innerHTML = (sumaValorTonelada.toFixed(2));


</script>
