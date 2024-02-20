
<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="kardex-tabla" name="kardex-tabla">
        <thead>
        <tr>
            <p id="campos" style="font-size: 1px; visibility: hidden">{{$campos}}</p>
            <th colspan="47" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>KARDEX INVENTARIO
                @if($fechaInicio)<br>PRODUCTO: COMPLEJO @endif
                <br>
                @if($fechaInicio)<b id="fechas"></b>@endif
                <br>PRODUCTOR: {{$productor->razon_social}}
                <br><br>
            </th>

        </tr>
        <tr>
            <th rowspan="2" style=" border: 1px solid black;">N°</th>
            <th rowspan="2" style=" border: 1px solid black;" id="fechaRecepcion">FEC. REC.</th>
            <th rowspan="2" style=" border: 1px solid black;" id="loteCompra">LOTE DE COMPRA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="loteVenta">LOTE DE VENTA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="proveedor">PRODUCTOR</th>
            <th rowspan="2" style=" border: 1px solid black;" id="cliente">CLIENTE</th>
            <th rowspan="2" style=" border: 1px solid black;" id="pesoBrutoHumedo">PESO BRUTO HUMEDO (Kg)</th>
            <th rowspan="2" style=" border: 1px solid black;" id="tara">TARA (Kg)</th>
            <th rowspan="2" style=" border: 1px solid black;" id="pesoNetoHumedo">PESO NETO HUMEDO (Kg)</th>
            <th rowspan="2" style=" border: 1px solid black;" id="humedadPorcentaje">Humedad (%)</th>
            <th rowspan="2" style=" border: 1px solid black;" id="humedadKg">Humedad (Kg)</th>
            <th rowspan="2" style=" border: 1px solid black;" id="merma">MERMA (Kg)</th>
            <th rowspan="2" style=" border: 1px solid black;" id="pesoNetoSeco">PESO NETO SECO (Kg)</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center" id="pb" class="pb">PB</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center" id="ag" class="ag">AG</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center" id="zn" class="zn">ZN</th>
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
            @if($nroBonificaciones >0)
            <th colspan="{{$nroBonificaciones}}" style=" border: 1px solid black; text-align: center" id="bonificaciones"
                class="bonificaciones">BONIFICACIONES
            </th>
            @endif
            <th rowspan="2" style=" border: 1px solid black;" id="totalBonificaciones">TOTAL BONIFICACIONES EN LIQUIDACIÓN</th>
            @if($nroBonificacionesAcumulativas >0)
                <th colspan="{{$nroBonificacionesAcumulativas}}" style=" border: 1px solid black; text-align: center" id="bonificacionesAcumulativas"
                    class="bonificacionesAcumulativas">BONIFICACIONES ACUMULATIVAS
                </th>
            @endif
            <th rowspan="2" style=" border: 1px solid black;" id="totalBonificacionesAcumulativas">TOTAL BONIFICACIONES ACUMULATIVAS</th>
            <th rowspan="2" style=" border: 1px solid black;" id="liquidoPagable">LIQUIDO PAGABLE</th>
            <th rowspan="2" style=" border: 1px solid black;" id="anticipos">ANTICIPO/ENTREGA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="cuentasCobrar">SALDO POR DEUDA/PRÉSTAMO</th>
            <th rowspan="2" style=" border: 1px solid black;" id="aporteFundacion">APORTE FUNDACIÓN</th>
            <th rowspan="2" style=" border: 1px solid black;" id="saldoFavor">SALDO A FAVOR</th>
            <th rowspan="2" style=" border: 1px solid black;" id="tratamiento">COSTO TRATAMIENTO</th>
            <th rowspan="2" style=" border: 1px solid black;" id="laboratorio">COSTO LABORATORIO</th>
            <th rowspan="2" style=" border: 1px solid black;" id="pesaje">COSTO PESAJE</th>
            <th rowspan="2" style=" border: 1px solid black;" id="comision">COSTO DE COMISIONES</th>
            <th rowspan="2" style=" border: 1px solid black;" id="comprobanteLiquidacion">COMPROBANTE LIQUIDACIÓN</th>
            <th rowspan="2" style=" border: 1px solid black;" id="comprobantesAnticipos">COMPROBANTES ANTICIPOS</th>
            <th rowspan="2" style=" border: 1px solid black;" id="fechaLiquidacion">FEC. LIQ</th>
            <th rowspan="2" style=" border: 1px solid black;" id="estado">ESTADO</th>
        </tr>
        <tr>
            <th style=" border: 1px solid black;" id="pb" class="pb">%Pb</th>
            <th style=" border: 1px solid black;" id="pb" class="pb">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="pb" class="pb">C. Diaria</th>
            <th style=" border: 1px solid black;" id="ag" class="ag">DM Ag</th>
            <th style=" border: 1px solid black;" id="ag" class="ag">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="ag" class="ag">C. Diaria</th>
            <th style=" border: 1px solid black;" id="zn" class="zn">%Zn</th>
            <th style=" border: 1px solid black;" id="zn" class="zn">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="zn" class="zn">C. Diaria</th>

            @foreach($retenciones as $retencion)
                <th style=" border: 1px solid black;"
                    id="{{$retencion->nombre}}" class="retencionesDeLey">{{$retencion->nombre}}</th>
            @endforeach
            @foreach($descuentos as $descuento)
                <th style=" border: 1px solid black;"
                    id="{{$descuento->nombre}}" class="descuentosInstitucionales">{{$descuento->nombre}}</th>
            @endforeach

            @foreach($bonificaciones as $bonificacion)
                <th style=" border: 1px solid black;"
                    id="{{$bonificacion->nombre}}" class="bonificaciones">{{$bonificacion->nombre}}</th>
            @endforeach

            @foreach($bonificacionesAcumulativas as $bonificacion)
                <th style=" border: 1px solid black;"
                    id="{{$bonificacion->nombre}}" class="bonificacionesAcumulativas">{{$bonificacion->nombre}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($formularios as $formulario)
            <tr>
                <td style=" border: 1px solid black;">{{$loop->iteration}}</td>
                <td style=" border: 1px solid black;" id="fechaRecepcionTd" class="fechaRecepcionTd">{{ date('d/m/y', strtotime($formulario->created_at)) }}</td>
                <td style=" border: 1px solid black;" id="loteCompraTd" clas="loteCompraTd">{{ $formulario->lote_sin_gestion }}</td>

                <td style=" border: 1px solid black;" id="loteVenta" >{{ $formulario->lote_venta }}</td>
                <td style=" border: 1px solid black;" id="proveedorTd" class="proveedorTd">{{ $formulario->cliente->cooperativa->razon_social }}</td>
                <td style=" border: 1px solid black;" id="clienteTd" class="clienteTd">{{ $formulario->cliente->nombre}}</td>
                <td style=" border: 1px solid black;" id="pesoBrutoHumedoTd" class="pesoBrutoHumedoTd">{{round($formulario->peso_bruto,5)}}</td>
                <td style=" border: 1px solid black;" id="taraTd" class="taraTd">{{round($formulario->tara,2)}}</td>
                <td style=" border: 1px solid black;" id="pesoNetoHumedoTd" class="pesoNetoHumedoTd">{{round($formulario->peso_neto,5)}}</td>
                <td style=" border: 1px solid black;" id="humedadPorcentajeTd" class="humedadPorcentajeTd"> {{round($formulario->humedad_promedio,2)}}</td>
                <td style=" border: 1px solid black;" id="humedadKgTd" class="humedadKgTd"> {{round($formulario->humedad_kilo,2)}}</td>
                <td style=" border: 1px solid black;" id="mermaTd" class="mermaTd">{{round($formulario->merma_kg,2)}}</td>
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
                <td style=" border: 1px solid black;" id="cotizacionPbTd" class="pbTd">{{round($formulario->cotizacion_pb,3)}}</td>
                <td style=" border: 1px solid black;" class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{round($lab->promedio,3)}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{round(($lab->promedio * $formulario->peso_seco / 10000),5)}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" class="agTd">{{round($formulario->cotizacion_ag,3)}}</td>
                <td style=" border: 1px solid black;" id="leyZnTd" class="znTd">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Zn') {{round($lab->promedio,3)}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"  id="pesoFinoZnTd" class="znTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Zn'){{round(($lab->promedio * $formulario->peso_seco / 100),5)}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="cotizacionZnTd" class="znTd">{{round($formulario->cotizacion_zn,3)}}</td>
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

                @foreach($bonificaciones as $bonificacion)
                    <td style=" border: 1px solid black;" id="{{'bonificacion'.$loop->iteration}}" class="bonificacionesTd">
                        {{round(($formulario->bonificaciones_cooperativa[$bonificacion->nombre]),2)}}
                    </td>
                @endforeach

                <td style=" border: 1px solid black;" id="totalBonificacionesTd" class="totalBonificacionesTd">{{round($formulario->total_bonificacion,2) }}</td>
                @foreach($bonificacionesAcumulativas as $bonificacion)
                    <td style=" border: 1px solid black;" id="{{'bonificacionAcumulativa'.$loop->iteration}}" class="bonificacionesAcumulativasTd">
                        {{round(($formulario->bonificaciones_cooperativa[$bonificacion->nombre]),2)}}
                    </td>
                @endforeach
                <td style=" border: 1px solid black;" id="totalBonificacionesAcumulativasTd" class="totalBonificacionesAcumulativasTd">{{round($formulario->total_bonificacion_acumulativa,2) }}</td>

                <td style=" border: 1px solid black;" id="liquidoPagableTd" class="liquidoPagableTd">{{round($formulario->liquido_pagable,2) }}</td>
                <td style=" border: 1px solid black;" id="anticiposTd" class="anticiposTd">{{round($formulario->total_anticipo,2) }}</td>
                <td style=" border: 1px solid black;" id="cuentasCobrarTd" class="cuentasCobrarTd">{{round($formulario->total_cuenta_cobrar,2) }}</td>

                <td style=" border: 1px solid black;" id="aporteFundacionTd" class="aporteFundacionTd">{{round(($formulario->aporte_fundacion),2) }}</td>
                <td style=" border: 1px solid black;" id="saldoFavorTd" class="saldoFavorTd">{{round(($formulario->saldo_favor),2) }}</td>

                <td style=" border: 1px solid black;" id="tratamientoTd" class="tratamientoTd">{{round(($formulario->peso_bruto * $formulario->costo->tratamiento * 6.97),2) }}</td>

                <td style=" border: 1px solid black;" id="laboratorioTd" class="laboratorioTd">{{round(($formulario->costo->laboratorio + $formulario->costo->dirimicion),2) }}</td>
                <td style=" border: 1px solid black;" id="pesajeTd" class="pesajeTd">{{round(($formulario->costo_pesaje),2) }}</td>
                <td style=" border: 1px solid black;" id="comisionTd" class="comisionTd">{{round(($formulario->peso_seco * $formulario->costo->comision * 6.86),2) }}</td>
                <td style=" border: 1px solid black;" id="comprobanteLiquidacionTd" class="comprobanteLiquidacionTd">{{$formulario->codigo_caja }}</td>
                <td style=" border: 1px solid black;" id="comprobantesAnticiposTd" class="comprobantesAnticiposTd">{{$formulario->codigos_anticipos }}</td>
                <td style=" border: 1px solid black;" id="fechaLiquidacionTd"
                    class="fechaLiquidacionTd">{{ date('d/m/y', strtotime($formulario->fecha_liquidacion)) }}</td>
                <td style=" border: 1px solid black;" id="estadoTd" class="estadoTd">{{$formulario->estado}}</td>

            </tr>
        @endforeach
        <tr>
            <td colspan="6" class="text-center" style=" border: 1px solid black;">
                <b style="text-align: center">
                    TOTALES
                </b>
            </td>
            <td style=" border: 1px solid black;" id="pesoBrutoHumedoTotal"><b> {{ round($formularios->sum('peso_bruto'),5)}}</b></td>
            <td style=" border: 1px solid black;" id="taraTotal"><b> {{ round($formularios->sum('tara'),2)}}</b></td>
            <td style=" border: 1px solid black;" id="pesoNetoHumedoTotal"><b> {{ round($formularios->sum('peso_neto'),5)}}</b></td>
            <td style=" border: 1px solid black;font-weight: bold" id="humedadPorcentajeTotal"></td>
            <td style=" border: 1px solid black;font-weight: bold" id="humedadKgTotal">{{ round($formularios->sum('humedad_kilo'),2)}}</td>
            <td style=" border: 1px solid black;font-weight: bold" id="mermaTotal">{{ round($formularios->sum('merma_kg'),2)}}</td>
            <td style=" border: 1px solid black;" id="pesoNetoSecoTotal"><b> {{ round($formularios->sum('peso_seco'),5)}}</b></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyPbTotal" class="pbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoPbTotal" class="pbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="cotizacionPbTotal" class="pbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyAgTotal" class="agTd"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoAgTotal" class="agTd"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="cotizacionAgTotal" class="agTd"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyZnTotal" class="znTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoZnTotal" class="znTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="cotizacionZnTotal" class="znTotal"></td>
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
            @foreach($bonificaciones as $bonificacion)
                <td style=" border: 1px solid black; font-weight: bold" class="bonificacionesTotal">{{round(($bonificacionesTotales[$bonificacion->nombre]),2)}}</td>
            @endforeach
            <td style=" border: 1px solid black; font-weight: bold" id="totalBonificacionesTotal">{{ round($formularios->sum('total_bonificacion'),2)}}</td>
            @foreach($bonificacionesAcumulativas as $bonificacion)
                <td style=" border: 1px solid black; font-weight: bold" class="bonificacionesAcumulativasTotal">{{round(($bonificacionesAcumulativasTotales[$bonificacion->nombre]),2)}}</td>
            @endforeach
            <td style=" border: 1px solid black; font-weight: bold" id="totalBonificacionesAcumulativasTotal">{{ round($formularios->sum('total_bonificacion_acumulativa'),2)}}</td>

            <td style=" border: 1px solid black; font-weight: bold" id="liquidoPagableTotal">{{ round($formularios->sum('liquido_pagable'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="anticiposTotal">{{ round($formularios->sum('total_anticipo'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="cuentasCobrarTotal">{{ round($formularios->sum('total_cuenta_cobrar'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="aporteFundacionTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="saldoFavorTotal">{{ round($formularios->where('saldo_favor','>', '0.00')->sum('saldo_favor'),2)}}</td>

            <td style=" border: 1px solid black; font-weight: bold" id="tratamientoTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="laboratorioTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesajeTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="comisionTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="comprobanteLiquidacionTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="comprobantesAnticiposTotal"></td>
            <td style=" border: 1px solid black;"id ="fechaLiquidacionTotal"></td>
            <td id="estadoTotal"></td>
        </tr>
        </tbody>
    </table>
</div>
<script>
    document.getElementById("fechas").innerHTML = "CORRESPONDIENTE A LAS FECHAS: {{ date('d/m/y', strtotime($fechaInicio)) }} AL {{ date('d/m/y', strtotime($fechaFinal)) }}";
    var table = document.getElementById("kardex-tabla"), sumaHumedad = 0, sumaLeyPb = 0, sumaHumedadKg = 0, sumaPesoFinoPb = 0
        , sumaPesoFinoAg = 0, sumaTratamiento = 0, sumaLaboratorio = 0, sumaValorTonelada=0
        , sumaComision = 0, sumaCotizacionPb = 0, sumaPesoNetoSeco = 0
        , sumaCuentasCobrar = 0, sumaLeyAg = 0, sumaCotizacionAg = 0, sumaPesoFinoZn = 0, sumaCotizacionZn = 0, sumaLeyZn = 0,
        sumaPesaje = 0, sumaAporteFundacion = 0,
        nroDescRet=parseInt("{{$nroDescRet}}"), nroBon=parseInt("{{$nroBonificaciones}}")
        , nroBonAcu=parseInt("{{$nroBonificacionesAcumulativas}}");
    for(var i = 3; i < (table.rows.length - 1); i++) {
        // sumaHumedadKg = parseFloat(sumaHumedadKg) + parseFloat(table.rows[i].cells[10].innerHTML.replace(/,/g, "."));
        sumaPesoFinoPb = parseFloat(sumaPesoFinoPb) + parseFloat(table.rows[i].cells[14].innerHTML.replace(/,/g, "."));
        sumaPesoFinoAg = parseFloat(sumaPesoFinoAg) + parseFloat(table.rows[i].cells[17].innerHTML.replace(/,/g, "."));
        sumaPesoFinoZn = parseFloat(sumaPesoFinoZn) + parseFloat(table.rows[i].cells[20].innerHTML.replace(/,/g, "."));

        // sumaCuentasCobrar = parseFloat(sumaCuentasCobrar) + parseFloat(table.rows[i].cells[29+nroDescRet+nroBon+nroBonAcu].innerHTML.replace(/,/g, "."));
        sumaAporteFundacion = parseFloat(sumaAporteFundacion) + parseFloat(table.rows[i].cells[31+nroDescRet+nroBon+nroBonAcu].innerHTML.replace(/,/g, "."));
        sumaTratamiento = parseFloat(sumaTratamiento) + parseFloat(table.rows[i].cells[33+nroDescRet+nroBon+nroBonAcu].innerHTML.replace(/,/g, "."));
        sumaLaboratorio = parseFloat(sumaLaboratorio) + parseFloat(table.rows[i].cells[34+nroDescRet+nroBon+nroBonAcu].innerHTML.replace(/,/g, "."));
        sumaPesaje = parseFloat(sumaPesaje) + parseFloat(table.rows[i].cells[35+nroDescRet+nroBon+nroBonAcu].innerHTML.replace(/,/g, "."));
        sumaComision = parseFloat(sumaComision) + parseFloat(table.rows[i].cells[36+nroDescRet+nroBon+nroBonAcu].innerHTML.replace(/,/g, "."));

        sumaCotizacionPb = parseFloat(sumaCotizacionPb) + (parseFloat(table.rows[i].cells[12].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[15].innerHTML.replace(/,/g, ".")));
        sumaPesoNetoSeco = parseFloat(sumaPesoNetoSeco) + parseFloat(table.rows[i].cells[12].innerHTML.replace(/,/g, "."));
        sumaCotizacionAg = parseFloat(sumaCotizacionAg) + (parseFloat(table.rows[i].cells[12].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[18].innerHTML.replace(/,/g, ".")));
        sumaCotizacionZn = parseFloat(sumaCotizacionZn) + (parseFloat(table.rows[i].cells[12].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[21].innerHTML.replace(/,/g, ".")));
        sumaValorTonelada= parseFloat(sumaValorTonelada) + (parseFloat(table.rows[i].cells[12].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[22].innerHTML.replace(/,/g, ".")));

    }
    sumaHumedad = (parseFloat("{{ $formularios->sum('humedad_kilo')}}") / parseFloat("{{ $formularios->sum('peso_neto')}}"))*100;
    sumaCotizacionPb = parseFloat(sumaCotizacionPb) / parseFloat(sumaPesoNetoSeco);
    sumaCotizacionAg = parseFloat(sumaCotizacionAg) / parseFloat(sumaPesoNetoSeco);
    sumaCotizacionZn = parseFloat(sumaCotizacionZn) / parseFloat(sumaPesoNetoSeco);
    sumaLeyPb = ((parseFloat(sumaPesoFinoPb) /  parseFloat(sumaPesoNetoSeco)) * 100);
    sumaLeyAg = ((parseFloat(sumaPesoFinoAg) /  parseFloat(sumaPesoNetoSeco)) * 10000);
    sumaLeyZn = ((parseFloat(sumaPesoFinoZn) /  parseFloat(sumaPesoNetoSeco)) * 100);
    sumaValorTonelada = parseFloat(sumaValorTonelada) / parseFloat(sumaPesoNetoSeco);

    document.getElementById("humedadPorcentajeTotal").innerHTML = (sumaHumedad.toFixed(5));
    document.getElementById("leyPbTotal").innerHTML = (sumaLeyPb.toFixed(3));
    document.getElementById("leyZnTotal").innerHTML = (sumaLeyZn.toFixed(3));
    // document.getElementById("humedadKgTotal").innerHTML = (sumaHumedadKg.toFixed(2));
    document.getElementById("pesoFinoPbTotal").innerHTML = (sumaPesoFinoPb.toFixed(5));
    document.getElementById("pesoFinoZnTotal").innerHTML = (sumaPesoFinoZn.toFixed(5));
    document.getElementById("leyAgTotal").innerHTML = (sumaLeyAg.toFixed(3));
    document.getElementById("pesoFinoAgTotal").innerHTML = (sumaPesoFinoAg.toFixed(5));

    // document.getElementById("cuentasCobrarTotal").innerHTML = (sumaCuentasCobrar.toFixed(2));
    document.getElementById("aporteFundacionTotal").innerHTML = (sumaAporteFundacion.toFixed(2));
    document.getElementById("tratamientoTotal").innerHTML = (sumaTratamiento.toFixed(2));
    document.getElementById("laboratorioTotal").innerHTML = (sumaLaboratorio.toFixed(2));
    document.getElementById("pesajeTotal").innerHTML = (sumaPesaje.toFixed(2));
    document.getElementById("comisionTotal").innerHTML = (sumaComision.toFixed(2));
    document.getElementById("cotizacionPbTotal").innerHTML = (sumaCotizacionPb.toFixed(3));
    document.getElementById("cotizacionZnTotal").innerHTML = (sumaCotizacionZn.toFixed(3));
    document.getElementById("cotizacionAgTotal").innerHTML = (sumaCotizacionAg.toFixed(3));
    document.getElementById("valorPorToneladaTotal").innerHTML = (sumaValorTonelada.toFixed(2));

    // sumaHumedad = parseFloat(sumaHumedadKg) + parseFloat(table.rows[i].cells[9].innerHTML);

    var campos = (document.getElementById("campos").innerHTML);
    $(document).ready(function(){
        for(var i = 0; i < JSON.parse(campos).length; i++) {
            if(!JSON.parse(campos)[i].visible){
                if(JSON.parse(campos)[i].codigo == 'pb' || JSON.parse(campos)[i].codigo == 'ag' || JSON.parse(campos)[i].codigo == 'sn'
                    || JSON.parse(campos)[i].codigo == 'zn' || JSON.parse(campos)[i].codigo == 'retencionesDeLey'
                    || JSON.parse(campos)[i].codigo == 'descuentosInstitucionales' || JSON.parse(campos)[i].codigo == 'bonificaciones'
                    || JSON.parse(campos)[i].codigo == 'bonificacionesAcumulativas'){
                    $('.'+JSON.parse(campos)[i].codigo).remove();
                    $('.'+JSON.parse(campos)[i].codigo+'Td').remove();
                    $('.'+JSON.parse(campos)[i].codigo + 'Total').remove();
                }
                else{
                    $('#'+JSON.parse(campos)[i].codigo).remove();
                    $('#'+JSON.parse(campos)[i].codigo+"Total").remove();
                    var className = $('#'+JSON.parse(campos)[i].codigo+'Td').attr('class');
                    $('.'+className).remove();
                }
            }
        }
    });

</script>
