
<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="kardex-tabla" name="kardex-tabla">
        <thead>
        <tr>
            <p id="campos" style="font-size: 1px; visibility: hidden">{{$campos}}</p>
            <th colspan="44" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>KARDEX INVENTARIO
                @if($fechaInicio)<br>PRODUCTO: PLOMO (PB) - PLATA (AG)@endif
                <br>
                @if($fechaInicio)<b id="fechas"></b>@endif
                <br><br>
            </th>

        </tr>
        <tr>
            <th rowspan="2" style=" border: 1px solid black;">N°</th>
            <th rowspan="2" style=" border: 1px solid black;" id="fechaRecepcion">FEC. REC.</th>
            <th rowspan="2" style=" border: 1px solid black;" id="fechaLiquidacion">FEC. LIQ</th>
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
            <th rowspan="2" style=" border: 1px solid black;" id="valorPorTonelada">VALOR POR TONELADA USD</th>
            <th rowspan="2" style=" border: 1px solid black;" id="valorNetoVenta">VALOR NETO VENTA</th>
            <th rowspan="2" style=" border: 1px solid black;" id="regaliaMinera">REGALIA MINERA</th>
            <th colspan="2" style=" border: 1px solid black; text-align: center" id="retencionesDeLey" class="retencionesDeLey">RETENCIONES DE LEY</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center" id="descuentosInstitucionales" class="descuentosInstitucionales">DESCUENTOS INSTITUCIONALES</th>
            <th rowspan="2" style=" border: 1px solid black;" id="totalRetencionesDescuento">TOTAL RETENCIONES Y DESCUENTOS</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center" id="bonificaciones" class="bonificaciones">BONIFICACIONES</th>
            <th rowspan="2" style=" border: 1px solid black;" id="totalBonificaciones">TOTAL BONIFICACIONES EN LIQUIDACIÓN</th>
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
            <th rowspan="2" style=" border: 1px solid black;" id="estado">ESTADO</th>
        </tr>
        <tr>
            <th style=" border: 1px solid black;" id="pb" class="pb">%Pb</th>
            <th style=" border: 1px solid black;" id="pb" class="pb">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="pb" class="pb">C. Diaria</th>
            <th style=" border: 1px solid black;" id="ag" class="ag">DM Ag</th>
            <th style=" border: 1px solid black;" id="ag" class="ag">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="ag" class="ag">C. Diaria</th>

            <th style=" border: 1px solid black;" class="retencionesDeLey">CAJA NACIONAL DE SALUD</th>
            <th style=" border: 1px solid black;" class="retencionesDeLey">COMIBOL</th>

            <th style=" border: 1px solid black;" class="descuentosInstitucionales">FENCOMIN-NORPO</th>
            <th style=" border: 1px solid black;" class="descuentosInstitucionales">FENCOMIN</th>
            <th style=" border: 1px solid black;" class="descuentosInstitucionales">GASTOS ADMIN.</th>

            <th style=" border: 1px solid black;" class="bonificaciones">TRANSPORTE</th>
            <th style=" border: 1px solid black;" class="bonificaciones">LEY</th>
            <th style=" border: 1px solid black;" class="bonificaciones">VIÁTICOS</th>

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

                <td style=" border: 1px solid black;" id="loteVenta" >{{ $formulario->lote_venta }}</td>
                <td style=" border: 1px solid black;" id="proveedorTd" class="proveedorTd">{{ $formulario->cliente->cooperativa->razon_social }}</td>
                <td style=" border: 1px solid black;" id="clienteTd" class="clienteTd">{{ $formulario->cliente->nombre}}</td>
                <td style=" border: 1px solid black;" id="pesoBrutoHumedoTd" class="pesoBrutoHumedoTd">{{number_format($formulario->peso_bruto,5, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="taraTd" class="taraTd">{{number_format($formulario->tara,2, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="pesoNetoHumedoTd" class="pesoNetoHumedoTd">{{number_format($formulario->peso_neto,5, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="humedadPorcentajeTd" class="humedadPorcentajeTd">
                    {{number_format($formulario->humedad_promedio,2, ',', '')}}
{{--                    @foreach($formulario->laboratorio_promedio as $lab)--}}
{{--                        @if($lab->simbolo=='H2O'){{number_format($lab->promedio,2, ',', '')}} @endif--}}
{{--                    @endforeach--}}
                </td>
                <td style=" border: 1px solid black;" id="humedadKgTd" class="humedadKgTd">
                    {{number_format($formulario->humedad_kilo,2, ',', '')}}
{{--                    @foreach($formulario->laboratorio_promedio as $lab)--}}
{{--                        @if($lab->simbolo=='H2O'){{number_format(($lab->promedio * $formulario->peso_neto / 100),2, ',', '')}}  @endif--}}
{{--                    @endforeach--}}
                </td>
                <td style=" border: 1px solid black;" id="mermaTd" class="mermaTd">{{number_format($formulario->merma_kg,2, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="pesoNetoSecoTd" class="pesoNetoSecoTd">{{number_format($formulario->peso_seco,5, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="leyPbTd" class="pbTd">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Pb') {{number_format($lab->promedio,3, ',', '')}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="pesoFinoPbTd" class="pbTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Pb'){{number_format(($lab->promedio * $formulario->peso_seco / 100),5, ',', '')}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="cotizacionPbTd" class="pbTd">{{number_format($formulario->cotizacion_pb,3, ',', '')}}</td>
                <td style=" border: 1px solid black;" class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{number_format($lab->promedio,3, ',', '')}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{number_format(($lab->promedio * $formulario->peso_seco / 10000),5, ',', '')}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" class="agTd">{{number_format($formulario->cotizacion_ag,3, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="valorPorToneladaTd" class="valorPorToneladaTd">{{number_format($formulario->valor_por_tonelada,2, ',', '')}}</td>
{{--                <td style=" border: 1px solid black;" id="valorNetoVentaTd" class="valorNetoVentaTd">{{number_format($formulario->valor_neto_venta,2, ',', '')}}</td>--}}
                <td style=" border: 1px solid black;" id="valorNetoVentaTd" class="valorNetoVentaTd">{{number_format($formulario->neto_venta,2, ',', '')}}</td>

{{--                <td style=" border: 1px solid black;" id="regaliaMineraTd" class="regaliaMineraTd">{{number_format($formulario->totales['total_minerales'],2, ',', '') }}</td>--}}
                <td style=" border: 1px solid black;" id="regaliaMineraTd" class="regaliaMineraTd">{{number_format($formulario->regalia_minera,2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="cajaSaludTd" class="retencionesDeLeyTd">{{number_format($formulario->bonificaciones_descuentos_bob['caja'],2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="comibolTd" class="retencionesDeLeyTd">{{number_format($formulario->bonificaciones_descuentos_bob['comibol'],2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="fencominNorpoTd" class="descuentosInstitucionalesTd">{{number_format($formulario->bonificaciones_descuentos_bob['norpo'],2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="fencominTd" class="descuentosInstitucionalesTd">{{number_format($formulario->bonificaciones_descuentos_bob['fencomin'],2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="gastosAdminTd" class="descuentosInstitucionalesTd">{{number_format($formulario->bonificaciones_descuentos_bob['administracion'],2, ',', '') }}</td>

{{--                <td style=" border: 1px solid black;" id="totalRetencionesDescuentoTd" class="totalRetencionesDescuentoTd">{{number_format(($formulario->totales['total_retenciones']+$formulario->totales['total_descuentos']),2, ',', '') }}</td>--}}
                <td style=" border: 1px solid black;" id="totalRetencionesDescuentoTd" class="totalRetencionesDescuentoTd">{{number_format($formulario->total_retencion_descuento,2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="transporteTd" class="bonificacionesTd">{{number_format($formulario->bonificaciones_descuentos_bob['transporte'],2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="leyTd" class="bonificacionesTd">{{number_format($formulario->bonificaciones_descuentos_bob['ley'],2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="viaticosTd" class="bonificacionesTd">{{number_format($formulario->bonificaciones_descuentos_bob['viaticos'],2, ',', '') }}</td>

{{--                <td style=" border: 1px solid black;" id="totalBonificacionesTd" class="totalBonificacionesTd">{{number_format($formulario->totales['total_bonificaciones'],2, ',', '') }}</td>--}}
                <td style=" border: 1px solid black;" id="totalBonificacionesTd" class="totalBonificacionesTd">{{number_format($formulario->total_bonificacion,2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="totalBonificacionesAcumulativasTd" class="totalBonificacionesAcumulativasTd">{{round($formulario->total_bonificacion_acumulativa,2) }}</td>

{{--                <td style=" border: 1px solid black;" id="liquidoPagableTd" class="liquidoPagableTd">{{number_format($formulario->totales['total_liquidacion'],2, ',', '') }}</td>--}}
                <td style=" border: 1px solid black;" id="liquidoPagableTd" class="liquidoPagableTd">{{number_format($formulario->liquido_pagable,2, ',', '') }}</td>
{{--                <td style=" border: 1px solid black;" id="anticiposTd" class="anticiposTd">{{number_format($formulario->totales['total_anticipos'],2, ',', '') }}</td>--}}
                <td style=" border: 1px solid black;" id="anticiposTd" class="anticiposTd">{{number_format($formulario->total_anticipo,2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="cuentasCobrarTd" class="cuentasCobrarTd">
                    {{number_format($formulario->total_cuenta_cobrar,2, ',', '') }}
{{--                    {{number_format($formulario->totales['total_cuentas_cobrar'],2, ',', '') }}--}}
                </td>

                <td style=" border: 1px solid black;" id="aporteFundacionTd" class="aporteFundacionTd">{{number_format(($formulario->aporte_fundacion),2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="saldoFavorTd" class="saldoFavorTd">{{number_format(($formulario->saldo_favor),2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="tratamientoTd" class="tratamientoTd">{{number_format(($formulario->peso_bruto * $formulario->costo->tratamiento * 6.97),2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="laboratorioTd" class="laboratorioTd">{{number_format(($formulario->costo->laboratorio + $formulario->costo->dirimicion),2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="pesajeTd" class="pesajeTd">{{number_format($formulario->costo_pesaje,2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="comisionTd" class="comisionTd">{{number_format(($formulario->peso_seco * $formulario->costo->comision * 6.86),2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="estadoTd" class="estadoTd">{{$formulario->estado}}</td>
            </tr>
        @endforeach
        <tr>
        <td colspan="7" class="text-center" style=" border: 1px solid black;">
                <b style="text-align: center">
                    TOTALES
                </b>
            </td>
            <td style=" border: 1px solid black;" id="pesoBrutoHumedoTotal"><b> {{ number_format($formularios->sum('peso_bruto'),5, ',', '')}}</b></td>
            <td style=" border: 1px solid black;" id="taraTotal"><b> {{ number_format($formularios->sum('tara'),2, ',', '')}}</b></td>
            <td style=" border: 1px solid black;" id="pesoNetoHumedoTotal"><b> {{ number_format($formularios->sum('peso_neto'),5, ',', '')}}</b></td>
            <td style=" border: 1px solid black;font-weight: bold" id="humedadPorcentajeTotal"></td>
            <td style=" border: 1px solid black;font-weight: bold" id="humedadKgTotal">{{ number_format($formularios->sum('humedad_kilo'),2, ',', '')}}</td>
            <td style=" border: 1px solid black;font-weight: bold" id="mermaTotal">{{ number_format($formularios->sum('merma_kg'),2, ',', '')}}</td>
            <td style=" border: 1px solid black;" id="pesoNetoSecoTotal"><b> {{ number_format($formularios->sum('peso_seco'),5, ',', '')}}</b></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyPbTotal" class="pbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoPbTotal" class="pbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="cotizacionPbTotal" class="pbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyAgTotal" class="agTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoAgTotal" class="agTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="cotizacionAgTotal" class="agTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="valorPorToneladaTotal"></td>
            <td style=" border: 1px solid black;" id="valorNetoVentaTotal"><b> {{ number_format($formularios->sum('neto_venta'),2, ',', '')}}</b></td>
            <td style=" border: 1px solid black; font-weight: bold" id="regaliaMineraTotal">{{ number_format($formularios->sum('regalia_minera'),2, ',', '')}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="cajaSaludTotal" class="retencionesDeLeyTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="comibolTotal" class="retencionesDeLeyTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="fencominNorpoTotal" class="descuentosInstitucionalesTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="fencominTotal" class="descuentosInstitucionalesTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="gastosAdminTotal" class="descuentosInstitucionalesTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="totalRetencionesDescuentoTotal">{{ number_format($formularios->sum('total_retencion_descuento'),2, ',', '')}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="transporteTotal" class="bonificacionesTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyTotal" class="bonificacionesTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="viaticosTotal" class="bonificacionesTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="totalBonificacionesTotal">{{ number_format($formularios->sum('total_bonificacion'),2, ',', '')}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="totalBonificacionesAcumulativasTotal">{{ round($formularios->sum('total_bonificacion_acumulativa'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="liquidoPagableTotal">{{ number_format($formularios->sum('liquido_pagable'),2, ',', '')}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="anticiposTotal">{{ number_format($formularios->sum('total_anticipo'),2, ',', '')}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="cuentasCobrarTotal">{{ number_format($formularios->sum('total_cuenta_cobrar'),2, ',', '')}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="aporteFundacionTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="saldoFavorTotal">{{ number_format($formularios->where('saldo_favor','>', '0.00')->sum('saldo_favor'),2, ',', '')}}</td>

            <td style=" border: 1px solid black; font-weight: bold" id="tratamientoTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="laboratorioTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesajeTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="comisionTotal"></td>
            <td id="estadoTotal"></td>
        </tr>
        </tbody>
    </table>
</div>
<script>
    document.getElementById("fechas").innerHTML = "CORRESPONDIENTE A LAS FECHAS: {{ date('d/m/y', strtotime($fechaInicio)) }} AL {{ date('d/m/y', strtotime($fechaFinal)) }}";
    var table = document.getElementById("kardex-tabla"), sumaHumedad = 0, sumaLeyPb = 0, sumaHumedadKg = 0, sumaPesoFinoPb = 0
    , sumaPesoFinoAg = 0, sumaCajaSalud = 0, sumaComibol = 0, sumaFencominNorpo = 0, sumaFencomin = 0, sumaGastosAdmin = 0
    , sumaTransporte = 0, sumaLeyes = 0, sumaViaticos = 0, sumaTratamiento = 0, sumaLaboratorio = 0
    , sumaComision = 0, sumaCotizacionPb = 0, sumaPesoNetoSeco = 0, sumaValorTonelada=0
    , sumaCuentasCobrar = 0, sumaLeyAg = 0, sumaCotizacionAg = 0, sumaPesaje = 0, sumaAporteFundacion = 0;
    for(var i = 3; i < (table.rows.length - 1); i++) {

        // sumaHumedadKg = parseFloat(sumaHumedadKg) + parseFloat(table.rows[i].cells[11].innerHTML.replace(/,/g, "."));
        sumaPesoFinoPb = parseFloat(sumaPesoFinoPb) + parseFloat(table.rows[i].cells[15].innerHTML.replace(/,/g, "."));
        sumaPesoFinoAg = parseFloat(sumaPesoFinoAg) + parseFloat(table.rows[i].cells[18].innerHTML.replace(/,/g, "."));
        sumaCajaSalud = parseFloat(sumaCajaSalud) + parseFloat(table.rows[i].cells[23].innerHTML.replace(/,/g, "."));
        sumaComibol = parseFloat(sumaComibol) + parseFloat(table.rows[i].cells[24].innerHTML.replace(/,/g, "."));
        sumaFencominNorpo = parseFloat(sumaFencominNorpo) + parseFloat(table.rows[i].cells[25].innerHTML.replace(/,/g, "."));
        sumaFencomin = parseFloat(sumaFencomin) + parseFloat(table.rows[i].cells[26].innerHTML.replace(/,/g, "."));
        sumaGastosAdmin = parseFloat(sumaGastosAdmin) + parseFloat(table.rows[i].cells[27].innerHTML.replace(/,/g, "."));
        sumaTransporte = parseFloat(sumaTransporte) + parseFloat(table.rows[i].cells[29].innerHTML.replace(/,/g, "."));
        sumaLeyes = parseFloat(sumaLeyes) + parseFloat(table.rows[i].cells[30].innerHTML.replace(/,/g, "."));
        sumaViaticos = parseFloat(sumaViaticos) + parseFloat(table.rows[i].cells[31].innerHTML.replace(/,/g, "."));
        // sumaCuentasCobrar = parseFloat(sumaCuentasCobrar) + parseFloat(table.rows[i].cells[35].innerHTML.replace(/,/g, "."));
        sumaAporteFundacion = parseFloat(sumaAporteFundacion) + parseFloat(table.rows[i].cells[37].innerHTML.replace(/,/g, "."));
        sumaTratamiento = parseFloat(sumaTratamiento) + parseFloat(table.rows[i].cells[39].innerHTML.replace(/,/g, "."));
        sumaLaboratorio = parseFloat(sumaLaboratorio) + parseFloat(table.rows[i].cells[40].innerHTML.replace(/,/g, "."));
        sumaPesaje = parseFloat(sumaPesaje) + parseFloat(table.rows[i].cells[41].innerHTML.replace(/,/g, "."));
        sumaComision = parseFloat(sumaComision) + parseFloat(table.rows[i].cells[42].innerHTML.replace(/,/g, "."));
        sumaCotizacionPb = parseFloat(sumaCotizacionPb) + (parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[16].innerHTML.replace(/,/g, ".")));
        sumaPesoNetoSeco = parseFloat(sumaPesoNetoSeco) + parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, "."));
        sumaCotizacionAg = parseFloat(sumaCotizacionAg) + (parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[19].innerHTML.replace(/,/g, ".")));
        sumaValorTonelada= parseFloat(sumaValorTonelada) + (parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[20].innerHTML.replace(/,/g, ".")));

    }
        sumaHumedad = (parseFloat("{{ $formularios->sum('humedad_kilo')}}") / parseFloat("{{ $formularios->sum('peso_neto')}}"))*100;
        sumaCotizacionPb = parseFloat(sumaCotizacionPb) / parseFloat(sumaPesoNetoSeco);
        sumaCotizacionAg = parseFloat(sumaCotizacionAg) / parseFloat(sumaPesoNetoSeco);
        sumaLeyPb = ((parseFloat(sumaPesoFinoPb) /  parseFloat(sumaPesoNetoSeco)) * 100);
        sumaLeyAg = ((parseFloat(sumaPesoFinoAg) /  parseFloat(sumaPesoNetoSeco)) * 10000);
        sumaValorTonelada = parseFloat(sumaValorTonelada) / parseFloat(sumaPesoNetoSeco);

    document.getElementById("humedadPorcentajeTotal").innerHTML = agregarComma(sumaHumedad.toFixed(5));
    document.getElementById("leyPbTotal").innerHTML = agregarComma(sumaLeyPb.toFixed(3));
    // document.getElementById("humedadKgTotal").innerHTML = agregarComma(sumaHumedadKg.toFixed(2));
    document.getElementById("pesoFinoPbTotal").innerHTML = agregarComma(sumaPesoFinoPb.toFixed(5));
    document.getElementById("leyAgTotal").innerHTML = agregarComma(sumaLeyAg.toFixed(2));
    document.getElementById("pesoFinoAgTotal").innerHTML = agregarComma(sumaPesoFinoAg.toFixed(5));
    document.getElementById("cajaSaludTotal").innerHTML = agregarComma(sumaCajaSalud.toFixed(2));
    document.getElementById("comibolTotal").innerHTML = agregarComma(sumaComibol.toFixed(2));
    document.getElementById("fencominNorpoTotal").innerHTML = agregarComma(sumaFencominNorpo.toFixed(2));
    document.getElementById("fencominTotal").innerHTML = agregarComma(sumaFencomin.toFixed(2));
    document.getElementById("gastosAdminTotal").innerHTML = agregarComma(sumaGastosAdmin.toFixed(2));
    document.getElementById("transporteTotal").innerHTML = agregarComma(sumaTransporte.toFixed(2));
    document.getElementById("leyTotal").innerHTML = agregarComma(sumaLeyes.toFixed(2));
    document.getElementById("viaticosTotal").innerHTML = agregarComma(sumaViaticos.toFixed(2));
    // document.getElementById("cuentasCobrarTotal").innerHTML = agregarComma(sumaCuentasCobrar.toFixed(2));
    document.getElementById("aporteFundacionTotal").innerHTML = agregarComma(sumaAporteFundacion.toFixed(2));
    document.getElementById("tratamientoTotal").innerHTML = agregarComma(sumaTratamiento.toFixed(2));
    document.getElementById("laboratorioTotal").innerHTML = agregarComma(sumaLaboratorio.toFixed(2));
    document.getElementById("pesajeTotal").innerHTML = agregarComma(sumaPesaje.toFixed(2));
    document.getElementById("comisionTotal").innerHTML = agregarComma(sumaComision.toFixed(2));
    document.getElementById("cotizacionPbTotal").innerHTML = agregarComma(sumaCotizacionPb.toFixed(2));
    document.getElementById("cotizacionAgTotal").innerHTML = agregarComma(sumaCotizacionAg.toFixed(2));
    document.getElementById("valorPorToneladaTotal").innerHTML = agregarComma(sumaValorTonelada.toFixed(2));

    // sumaHumedad = parseFloat(sumaHumedadKg) + parseFloat(table.rows[i].cells[9].innerHTML);

    var campos = (document.getElementById("campos").innerHTML);
    $(document).ready(function(){
        for(var i = 0; i < JSON.parse(campos).length; i++) {
            if(!JSON.parse(campos)[i].visible){
                if(JSON.parse(campos)[i].codigo == 'pb' || JSON.parse(campos)[i].codigo == 'ag' || JSON.parse(campos)[i].codigo == 'retencionesDeLey'
                    || JSON.parse(campos)[i].codigo == 'descuentosInstitucionales' || JSON.parse(campos)[i].codigo == 'bonificaciones'){
                    $('.'+JSON.parse(campos)[i].codigo).remove();
                    $('.'+JSON.parse(campos)[i].codigo+'Td').remove();
                    $('.'+JSON.parse(campos)[i].codigo + 'Total').remove();
                }
                else{
                    console.log(JSON.parse(campos)[i]);
                    $('#'+JSON.parse(campos)[i].codigo).remove();
                    $('#'+JSON.parse(campos)[i].codigo+"Total").remove();
                    var className = $('#'+JSON.parse(campos)[i].codigo+'Td').attr('class');
                    $('.'+className).remove();
                }
            }
        }
    });

    function agregarComma(nStr) {
        return nStr.replace('.', ',')
    }

</script>
