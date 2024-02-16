
<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="kardex-tabla" name="kardex-tabla">
        <thead>
        <tr>
            <p id="campos" style="font-size: 1px; visibility: hidden">{{$campos}}</p>
            <th colspan="59" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>KARDEX INVENTARIO

                @if($productoLetra=='%')
                <br>PRODUCTO: A - B - C : COMPLEJO
                @endif
                <br>@if($fechaInicio)<b id="fechas"></b>@endif
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
            <th colspan="3" style=" border: 1px solid black; text-align: center" id="zn" class="zn">ZN</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center" id="sn" class="sn">SN</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center" id="au" class="au">AU</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center" id="sb" class="sb">SB</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center" id="sb" class="sb">CU</th>
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
            <th style=" border: 1px solid black;" id="zn" class="zn">%Zn</th>
            <th style=" border: 1px solid black;" id="zn" class="zn">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="zn" class="zn">C. Diaria</th>
            <th style=" border: 1px solid black;" id="sn" class="sn">%Sn</th>
            <th style=" border: 1px solid black;" id="sn" class="sn">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="sn" class="sn">C. Diaria</th>
            <th style=" border: 1px solid black;" id="au" class="au">G/T Au</th>
            <th style=" border: 1px solid black;" id="au" class="au">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="au" class="au">C. Diaria</th>
            <th style=" border: 1px solid black;" id="sb" class="sb">%Sb</th>
            <th style=" border: 1px solid black;" id="sb" class="sb">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="sb" class="sb">C. Diaria</th>
            <th style=" border: 1px solid black;" id="cu" class="cu">%Cu</th>
            <th style=" border: 1px solid black;" id="cu" class="cu">Peso Fino (Kg)</th>
            <th style=" border: 1px solid black;" id="cu" class="cu">C. Diaria</th>

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
                <td style=" border: 1px solid black;" id="cotizacionPbTd" class="pbTd">
                    @if(round($formulario->cotizacion_pb,3)!=0)
                        {{round($formulario->cotizacion_pb,3)}}
                    @endif
                </td>
                <td style=" border: 1px solid black;" id="leyAgTd" class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{round($lab->promedio,3)}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="pesoFinoAgTd" class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{round(($lab->promedio * $formulario->peso_seco / 10000),5)}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="cotizacionAgTd" class="agTd">
                    @if(round($formulario->cotizacion_ag,3)!=0)
                        {{round($formulario->cotizacion_ag,3)}}
                    @endif
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
                <td style=" border: 1px solid black;" id="cotizacionZnTd" class="znTd">
                    @if(round($formulario->cotizacion_zn,3)!=0)
                        {{round($formulario->cotizacion_zn,3)}}
                    @endif
                    </td>
                <td style=" border: 1px solid black;" id="leySnTd" class="snTd">
                    {{ !is_null($formulario->ley_sn) ? round($formulario->ley_sn,3):'' }}
                </td>
                <td style=" border: 1px solid black;"  id="pesoFinoSnTd" class="snTd">
                    {{!is_null($formulario->ley_sn) ? round(($formulario->ley_sn * $formulario->peso_seco / 100),5):''}}
                </td>
                <td style=" border: 1px solid black;" id="cotizacionSnTd" class="snTd">
                    @if(round($formulario->cotizacion_sn,3)!=0)
                        {{round($formulario->cotizacion_sn,3)}}
                    @endif
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
                <td style=" border: 1px solid black;" id="cotizacionAuTd" class="auTd">
                    @if(round($formulario->cotizacion_au,3)!=0)
                        {{round($formulario->cotizacion_au,3)}}
                    @endif
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
                <td style=" border: 1px solid black;" id="cotizacionSbTd" class="sbTd">
                    @if(round($formulario->cotizacion_sb,3)!=0)
                        {{round($formulario->cotizacion_sb,3)}}
                    @endif
                </td>

                <td style=" border: 1px solid black;" id="leyCuTd" class="cuTd">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Cu') {{round($lab->promedio,3)}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" class="cuTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Cu'){{round(($lab->promedio * $formulario->peso_seco / 100),5)}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="cotizacionCuTd" class="cuTd"> @if(round($formulario->cotizacion_cu,3)!=0)
                        {{round($formulario->cotizacion_cu,3)}}
                    @endif</td>



                <td style=" border: 1px solid black;" id="valorPorToneladaTd" class="valorPorToneladaTd">{{round($formulario->valor_por_tonelada,2)}}</td>
                {{--                <td style=" border: 1px solid black;" id="valorNetoVentaTd" class="valorNetoVentaTd">{{round($formulario->valor_neto_venta,2)}}</td>--}}
                <td style=" border: 1px solid black;" id="valorNetoVentaTd" class="valorNetoVentaTd">{{round($formulario->neto_venta,2)}}</td>

                {{--                <td style=" border: 1px solid black;" id="regaliaMineraTd" class="regaliaMineraTd">{{round($formulario->totales['total_minerales'],2) }}</td>--}}
                <td style=" border: 1px solid black;" id="regaliaMineraTd" class="regaliaMineraTd">{{round($formulario->regalia_minera,2) }}</td>
                <td style=" border: 1px solid black;" id="cajaSaludTd" class="retencionesDeLeyTd">{{round($formulario->bonificaciones_descuentos_bob['caja'],2) }}</td>
                <td style=" border: 1px solid black;" id="comibolTd" class="retencionesDeLeyTd">{{round($formulario->bonificaciones_descuentos_bob['comibol'],2) }}</td>

                <td style=" border: 1px solid black;" id="fencominNorpoTd" class="descuentosInstitucionalesTd">{{round($formulario->bonificaciones_descuentos_bob['norpo'],2) }}</td>
                <td style=" border: 1px solid black;" id="fencominTd" class="descuentosInstitucionalesTd">{{round($formulario->bonificaciones_descuentos_bob['fencomin'],2) }}</td>
                <td style=" border: 1px solid black;" id="gastosAdminTd" class="descuentosInstitucionalesTd">{{round($formulario->bonificaciones_descuentos_bob['administracion'],2) }}</td>

                {{--                <td style=" border: 1px solid black;" id="totalRetencionesDescuentoTd" class="totalRetencionesDescuentoTd">{{round(($formulario->totales['total_retenciones']+$formulario->totales['total_descuentos']),2) }}</td>--}}
                <td style=" border: 1px solid black;" id="totalRetencionesDescuentoTd" class="totalRetencionesDescuentoTd">{{round($formulario->total_retencion_descuento,2) }}</td>

                <td style=" border: 1px solid black;" id="transporteTd" class="bonificacionesTd">{{round($formulario->bonificaciones_descuentos_bob['transporte'],2) }}</td>
                <td style=" border: 1px solid black;" id="leyTd" class="bonificacionesTd">{{round($formulario->bonificaciones_descuentos_bob['ley'],2) }}</td>
                <td style=" border: 1px solid black;" id="viaticosTd" class="bonificacionesTd">{{round($formulario->bonificaciones_descuentos_bob['viaticos'],2) }}</td>

                {{--                <td style=" border: 1px solid black;" id="totalBonificacionesTd" class="totalBonificacionesTd">{{round($formulario->totales['total_bonificaciones'],2) }}</td>--}}
                <td style=" border: 1px solid black;" id="totalBonificacionesTd" class="totalBonificacionesTd">{{round($formulario->total_bonificacion,2) }}</td>
                <td style=" border: 1px solid black;" id="totalBonificacionesAcumulativasTd" class="totalBonificacionesAcumulativasTd">{{round($formulario->total_bonificacion_acumulativa,2) }}</td>

                {{--                <td style=" border: 1px solid black;" id="liquidoPagableTd" class="liquidoPagableTd">{{round($formulario->totales['total_liquidacion'],2) }}</td>--}}
                <td style=" border: 1px solid black;" id="liquidoPagableTd" class="liquidoPagableTd">{{round($formulario->liquido_pagable,2) }}</td>
                {{--                <td style=" border: 1px solid black;" id="anticiposTd" class="anticiposTd">{{round($formulario->totales['total_anticipos'],2) }}</td>--}}
                <td style=" border: 1px solid black;" id="anticiposTd" class="anticiposTd">{{round($formulario->total_anticipo,2) }}</td>

                <td style=" border: 1px solid black;" id="cuentasCobrarTd" class="cuentasCobrarTd">{{round($formulario->total_cuenta_cobrar,2) }}</td>

                <td style=" border: 1px solid black;" id="aporteFundacionTd" class="aporteFundacionTd">{{round(($formulario->aporte_fundacion),2) }}</td>
                <td style=" border: 1px solid black;" id="saldoFavorTd" class="saldoFavorTd">{{round(($formulario->saldo_favor),2) }}</td>

                <td style=" border: 1px solid black;" id="tratamientoTd" class="tratamientoTd">{{round(($formulario->peso_bruto * $formulario->costo->tratamiento * 6.97),2) }}</td>

                <td style=" border: 1px solid black;" id="laboratorioTd" class="laboratorioTd">{{round(($formulario->costo->laboratorio + $formulario->costo->dirimicion),2) }}</td>
                <td style=" border: 1px solid black;" id="pesajeTd" class="pesajeTd">{{round(($formulario->costo_pesaje),2) }}</td>
                <td style=" border: 1px solid black;" id="comisionTd" class="comisionTd">{{round(($formulario->peso_seco * $formulario->costo->comision * 6.86),2) }}</td>
                <td style=" border: 1px solid black;" id="estadoTd" class="estadoTd">{{$formulario->estado}}</td>

            </tr>
        @endforeach
        <tr>
            <td colspan="7" class="text-center" style=" border: 1px solid black;">
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
            <td style=" border: 1px solid black; font-weight: bold" id="leySnTotal" class="snTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoSnTotal" class="snTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="cotizacionSnTotal" class="snTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyAuTotal" class="auTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoAuTotal" class="auTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="cotizacionAuTotal" class="auTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leySbTotal" class="sbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoSbTotal" class="sbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="cotizacionSbTotal" class="sbTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyCuTotal" class="cuTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="pesoFinoCuTotal" class="cuTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="cotizacionCuTotal" class="cuTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="valorPorToneladaTotal"></td>
            <td style=" border: 1px solid black;" id="valorNetoVentaTotal"><b> {{ round($formularios->sum('neto_venta'),2)}}</b></td>
            <td style=" border: 1px solid black; font-weight: bold" id="regaliaMineraTotal">{{ round($formularios->sum('regalia_minera'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="cajaSaludTotal" class="retencionesDeLeyTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="comibolTotal" class="retencionesDeLeyTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="fencominNorpoTotal" class="descuentosInstitucionalesTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="fencominTotal" class="descuentosInstitucionalesTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="gastosAdminTotal" class="descuentosInstitucionalesTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="totalRetencionesDescuentoTotal">{{ round($formularios->sum('total_retencion_descuento'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold" id="transporteTotal" class="bonificacionesTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="leyTotal" class="bonificacionesTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="viaticosTotal" class="bonificacionesTotal"></td>
            <td style=" border: 1px solid black; font-weight: bold" id="totalBonificacionesTotal">{{ round($formularios->sum('total_bonificacion'),2)}}</td>
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
            <td id="estadoTotal"></td>
        </tr>
        </tbody>
    </table>
</div>
<script>
    document.getElementById("fechas").innerHTML = "CORRESPONDIENTE A LAS FECHAS: {{ date('d/m/y', strtotime($fechaInicio)) }} AL {{ date('d/m/y', strtotime($fechaFinal)) }}";
    var table = document.getElementById("kardex-tabla"), sumaRegalia = 0, sumaHumedad = 0, sumaLeyPb = 0, sumaHumedadKg = 0, sumaPesoFinoPb = 0
        , sumaPesoFinoAg = 0, sumaCajaSalud = 0, sumaComibol = 0, sumaFencominNorpo = 0, sumaFencomin = 0, sumaGastosAdmin = 0
        , sumaTransporte = 0, sumaLeyes = 0, sumaViaticos = 0, sumaTratamiento = 0, sumaLaboratorio = 0
        , sumaComision = 0, sumaCotizacionPb = 0, sumaPesoNetoSeco = 0, sumaValorTonelada=0
        , cuentasCobrar = 0, sumaLeyAg = 0, sumaCotizacionAg = 0, sumaPesoFinoZn = 0, sumaCotizacionZn = 0, sumaLeyZn = 0,
        sumaPesoFinoSn = 0, sumaCotizacionSn = 0, sumaLeySn = 0, sumaPesoFinoAu = 0, sumaCotizacionAu = 0, sumaLeyAu = 0,
        sumaPesoFinoSb = 0, sumaCotizacionSb = 0, sumaLeySb = 0, sumaPesoFinoCu = 0, sumaCotizacionCu = 0, sumaLeyCu = 0,
        sumaPesaje = 0, sumaAporteFundacion = 0
        sumaPesoNetoSecoZn=0, sumaPesoNetoSecoSn=0, sumaPesoNetoSecoAg=0, sumaPesoNetoSecoPb=0, sumaPesoNetoSecoAu=0, sumaPesoNetoSecoSb=0, sumaPesoNetoSecoCu=0;
    for(var i = 3; i < (table.rows.length - 1); i++) {

        // sumaHumedadKg = parseFloat(sumaHumedadKg) + parseFloat(table.rows[i].cells[10].innerHTML.replace(/,/g, "."));

        sumaPesoFinoPb = parseFloat(sumaPesoFinoPb) +
            (table.rows[i].cells[15].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[15].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoAg = parseFloat(sumaPesoFinoAg) +
            (table.rows[i].cells[18].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[18].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoZn = parseFloat(sumaPesoFinoZn) +
            (table.rows[i].cells[21].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[21].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoSn = parseFloat(sumaPesoFinoSn) +
            (table.rows[i].cells[24].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[24].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoAu = parseFloat(sumaPesoFinoAu) +
            (table.rows[i].cells[27].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[27].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoSb = parseFloat(sumaPesoFinoSb) +
            (table.rows[i].cells[30].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[30].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoCu = parseFloat(sumaPesoFinoCu) +
            (table.rows[i].cells[33].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[33].innerHTML.replace(/,/g, ".")));

        sumaCajaSalud = parseFloat(sumaCajaSalud) + parseFloat(table.rows[i].cells[38].innerHTML.replace(/,/g, "."));
        sumaComibol = parseFloat(sumaComibol) + parseFloat(table.rows[i].cells[39].innerHTML.replace(/,/g, "."));
        sumaFencominNorpo = parseFloat(sumaFencominNorpo) + parseFloat(table.rows[i].cells[40].innerHTML.replace(/,/g, "."));
        sumaFencomin = parseFloat(sumaFencomin) + parseFloat(table.rows[i].cells[41].innerHTML.replace(/,/g, "."));
        sumaGastosAdmin = parseFloat(sumaGastosAdmin) + parseFloat(table.rows[i].cells[42].innerHTML.replace(/,/g, "."));
        sumaTransporte = parseFloat(sumaTransporte) + parseFloat(table.rows[i].cells[44].innerHTML.replace(/,/g, "."));
        sumaLeyes = parseFloat(sumaLeyes) + parseFloat(table.rows[i].cells[45].innerHTML.replace(/,/g, "."));
        sumaViaticos = parseFloat(sumaViaticos) + parseFloat(table.rows[i].cells[46].innerHTML.replace(/,/g, "."));
        // cuentasCobrar = parseFloat(cuentasCobrar) + parseFloat(table.rows[i].cells[47].innerHTML.replace(/,/g, "."));
        sumaAporteFundacion = parseFloat(sumaAporteFundacion) + parseFloat(table.rows[i].cells[52].innerHTML.replace(/,/g, "."));
        sumaTratamiento = parseFloat(sumaTratamiento) + parseFloat(table.rows[i].cells[54].innerHTML.replace(/,/g, "."));
        sumaLaboratorio = parseFloat(sumaLaboratorio) + parseFloat(table.rows[i].cells[55].innerHTML.replace(/,/g, "."));
        sumaPesaje = parseFloat(sumaPesaje) + parseFloat(table.rows[i].cells[56].innerHTML.replace(/,/g, "."));
        sumaComision = parseFloat(sumaComision) + parseFloat(table.rows[i].cells[57].innerHTML.replace(/,/g, "."));

        // sumaPesoFinoPb = parseFloat(sumaPesoFinoPb) +
        //      (table.rows[i].cells[14].innerHTML.trim()=='' ?parseFloat(0.0) :parseFloat(table.rows[i].cells[14].innerHTML.replace(/,/g, ".")));
        //
        sumaPesoNetoSeco = parseFloat(sumaPesoNetoSeco) + parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, "."));

        sumaCotizacionPb = parseFloat(sumaCotizacionPb) + (
            table.rows[i].cells[16].innerHTML.trim()=='' ? parseFloat(0.0) :
            (parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")) *  parseFloat(table.rows[i].cells[16].innerHTML.replace(/,/g, "."))));
        sumaPesoNetoSecoPb = parseFloat(sumaPesoNetoSecoPb) + (table.rows[i].cells[16].innerHTML.trim()=='' ? parseFloat(0.0) : parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")));

        sumaCotizacionAg = parseFloat(sumaCotizacionAg) + (
            table.rows[i].cells[19].innerHTML.trim()=='' ? parseFloat(0.0) :
                (parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[19].innerHTML.replace(/,/g, "."))));
        sumaPesoNetoSecoAg = parseFloat(sumaPesoNetoSecoAg) + (table.rows[i].cells[19].innerHTML.trim()=='' ? parseFloat(0.0) : parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")));

        sumaCotizacionZn = parseFloat(sumaCotizacionZn) + (
            table.rows[i].cells[22].innerHTML.trim()=='' ? parseFloat(0.0) :
            (parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[22].innerHTML.replace(/,/g, "."))));
        sumaPesoNetoSecoZn = parseFloat(sumaPesoNetoSecoZn) + (table.rows[i].cells[22].innerHTML.trim()=='' ? parseFloat(0.0) : parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")));

        sumaCotizacionSn = parseFloat(sumaCotizacionSn) + (
            table.rows[i].cells[25].innerHTML.trim()=='' ? parseFloat(0.0) :
            (parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[25].innerHTML.replace(/,/g, "."))));
        sumaPesoNetoSecoSn = parseFloat(sumaPesoNetoSecoSn) + (table.rows[i].cells[25].innerHTML.trim()=='' ? parseFloat(0.0) : parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")));

        sumaCotizacionAu = parseFloat(sumaCotizacionAu) + (
            table.rows[i].cells[28].innerHTML.trim()=='' ? parseFloat(0.0) :
                (parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[28].innerHTML.replace(/,/g, "."))));
        sumaPesoNetoSecoAu = parseFloat(sumaPesoNetoSecoAu) + (table.rows[i].cells[28].innerHTML.trim()=='' ? parseFloat(0.0) : parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")));

        sumaCotizacionSb = parseFloat(sumaCotizacionSb) + (
            table.rows[i].cells[31].innerHTML.trim()=='' ? parseFloat(0.0) :
                (parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[31].innerHTML.replace(/,/g, "."))));
        sumaPesoNetoSecoSb = parseFloat(sumaPesoNetoSecoSb) + (table.rows[i].cells[31].innerHTML.trim()=='' ? parseFloat(0.0) : parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")));

        sumaCotizacionCu = parseFloat(sumaCotizacionCu) + (
            table.rows[i].cells[34].innerHTML.trim()=='' ? parseFloat(0.0) :
                (parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[34].innerHTML.replace(/,/g, "."))));
        sumaPesoNetoSecoCu = parseFloat(sumaPesoNetoSecoCu) + (table.rows[i].cells[34].innerHTML.trim()=='' ? parseFloat(0.0) : parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")));

        sumaValorTonelada= parseFloat(sumaValorTonelada) + (parseFloat(table.rows[i].cells[13].innerHTML.replace(/,/g, ".")) * parseFloat(table.rows[i].cells[35].innerHTML.replace(/,/g, ".")));

    }
    sumaHumedad = (parseFloat("{{ $formularios->sum('humedad_kilo')}}") / parseFloat("{{ $formularios->sum('peso_neto')}}"))*100;
    sumaCotizacionPb = sumaCotizacionPb==0 ? 0.00: parseFloat(sumaCotizacionPb) / parseFloat(sumaPesoNetoSecoPb);
    sumaCotizacionAg = sumaCotizacionAg==0 ? 0.00: parseFloat(sumaCotizacionAg) / parseFloat(sumaPesoNetoSecoAg);
    sumaCotizacionZn = sumaCotizacionZn==0 ? 0.00: parseFloat(sumaCotizacionZn) / parseFloat(sumaPesoNetoSecoZn);
    sumaCotizacionSn = sumaCotizacionSn==0 ? 0.00: parseFloat(sumaCotizacionSn) / parseFloat(sumaPesoNetoSecoSn);
    sumaCotizacionAu = sumaCotizacionAu==0 ? 0.00: parseFloat(sumaCotizacionAu) / parseFloat(sumaPesoNetoSecoAu);
    sumaCotizacionSb = sumaCotizacionSb==0 ? 0.00: parseFloat(sumaCotizacionSb) / parseFloat(sumaPesoNetoSecoSb);
    sumaCotizacionCu = sumaCotizacionCu==0 ? 0.00: parseFloat(sumaCotizacionCu) / parseFloat(sumaPesoNetoSecoCu);
    sumaValorTonelada = parseFloat(sumaValorTonelada) / parseFloat(sumaPesoNetoSeco);

    // sumaLeyPb = sumaPesoNetoSecoPb==0 ? 0.00: ((parseFloat(sumaPesoFinoPb) /  parseFloat(sumaPesoNetoSecoPb)) * 100);
    // sumaLeyAg = sumaPesoNetoSecoAg==0 ? 0.00: ((parseFloat(sumaPesoFinoAg) /  parseFloat(sumaPesoNetoSecoAg)) * 10000);
    // sumaLeyZn = sumaPesoNetoSecoZn==0 ? 0.00: ((parseFloat(sumaPesoFinoZn) /  parseFloat(sumaPesoNetoSecoZn)) * 100);
    // sumaLeySn = sumaPesoNetoSecoSn==0 ? 0.00: ((parseFloat(sumaPesoFinoSn) /  parseFloat(sumaPesoNetoSecoSn)) * 100);
    // sumaLeyAu = sumaPesoNetoSecoAu==0 ? 0.00: ((parseFloat(sumaPesoFinoAu) /  parseFloat(sumaPesoNetoSecoAu)) * 10000);
    // sumaLeySb = sumaPesoNetoSecoSb==0 ? 0.00: ((parseFloat(sumaPesoFinoSb) /  parseFloat(sumaPesoNetoSecoSb)) * 100);

    sumaLeyPb = ((parseFloat(sumaPesoFinoPb) /  parseFloat(sumaPesoNetoSeco)) * 100);
    sumaLeyAg = ((parseFloat(sumaPesoFinoAg) /  parseFloat(sumaPesoNetoSeco)) * 10000);
    sumaLeyZn = ((parseFloat(sumaPesoFinoZn) /  parseFloat(sumaPesoNetoSeco)) * 100);
    sumaLeySn = ((parseFloat(sumaPesoFinoSn) /  parseFloat(sumaPesoNetoSeco)) * 100);
    sumaLeyAu = ((parseFloat(sumaPesoFinoAu) /  parseFloat(sumaPesoNetoSeco)) * 10000);
    sumaLeySb = ((parseFloat(sumaPesoFinoSb) /  parseFloat(sumaPesoNetoSeco)) * 100);
    sumaLeyCu = ((parseFloat(sumaPesoFinoCu) /  parseFloat(sumaPesoNetoSeco)) * 100);

    document.getElementById("humedadPorcentajeTotal").innerHTML = (sumaHumedad.toFixed(5));
    document.getElementById("leyPbTotal").innerHTML = (sumaLeyPb.toFixed(3));
    document.getElementById("leyZnTotal").innerHTML = (sumaLeyZn.toFixed(3));
    document.getElementById("leySnTotal").innerHTML = (sumaLeySn.toFixed(3));
    document.getElementById("leyAuTotal").innerHTML = (sumaLeyAu.toFixed(3));
    document.getElementById("leySbTotal").innerHTML = (sumaLeySb.toFixed(3));
    document.getElementById("leyCuTotal").innerHTML = (sumaLeyCu.toFixed(3));

    // document.getElementById("humedadKgTotal").innerHTML = (sumaHumedadKg.toFixed(2));
    document.getElementById("pesoFinoPbTotal").innerHTML = (sumaPesoFinoPb.toFixed(5));
    document.getElementById("pesoFinoZnTotal").innerHTML = (sumaPesoFinoZn.toFixed(5));
    document.getElementById("pesoFinoSnTotal").innerHTML = (sumaPesoFinoSn.toFixed(5));
    document.getElementById("pesoFinoAuTotal").innerHTML = (sumaPesoFinoAu.toFixed(5));
    document.getElementById("pesoFinoSbTotal").innerHTML = (sumaPesoFinoSb.toFixed(5));
    document.getElementById("pesoFinoCuTotal").innerHTML = (sumaPesoFinoCu.toFixed(5));

    document.getElementById("leyAgTotal").innerHTML = (sumaLeyAg.toFixed(3));
    document.getElementById("pesoFinoAgTotal").innerHTML = (sumaPesoFinoAg.toFixed(5));
    document.getElementById("cajaSaludTotal").innerHTML = (sumaCajaSalud.toFixed(2));
    document.getElementById("comibolTotal").innerHTML = (sumaComibol.toFixed(2));
    document.getElementById("fencominNorpoTotal").innerHTML = (sumaFencominNorpo.toFixed(2));
    document.getElementById("fencominTotal").innerHTML = (sumaFencomin.toFixed(2));
    document.getElementById("gastosAdminTotal").innerHTML = (sumaGastosAdmin.toFixed(2));
    document.getElementById("transporteTotal").innerHTML = (sumaTransporte.toFixed(2));
    document.getElementById("leyTotal").innerHTML = (sumaLeyes.toFixed(2));
    document.getElementById("viaticosTotal").innerHTML = (sumaViaticos.toFixed(2));
    // document.getElementById("cuentasCobrarTotal").innerHTML = (cuentasCobrar.toFixed(2));
    document.getElementById("aporteFundacionTotal").innerHTML = (sumaAporteFundacion.toFixed(2));
    document.getElementById("tratamientoTotal").innerHTML = (sumaTratamiento.toFixed(2));
    document.getElementById("laboratorioTotal").innerHTML = (sumaLaboratorio.toFixed(2));
    document.getElementById("pesajeTotal").innerHTML = (sumaPesaje.toFixed(2));
    document.getElementById("comisionTotal").innerHTML = (sumaComision.toFixed(2));
    document.getElementById("cotizacionPbTotal").innerHTML = (sumaCotizacionPb.toFixed(3));
    document.getElementById("cotizacionZnTotal").innerHTML = (sumaCotizacionZn.toFixed(3));
    document.getElementById("cotizacionAgTotal").innerHTML = (sumaCotizacionAg.toFixed(3));
    document.getElementById("cotizacionSnTotal").innerHTML = (sumaCotizacionSn.toFixed(3));
    document.getElementById("cotizacionAuTotal").innerHTML = (sumaCotizacionAu.toFixed(3));
    document.getElementById("cotizacionSbTotal").innerHTML = (sumaCotizacionSb.toFixed(3));
    document.getElementById("cotizacionCuTotal").innerHTML = (sumaCotizacionCu.toFixed(3));
    document.getElementById("valorPorToneladaTotal").innerHTML = (sumaValorTonelada.toFixed(2));

    // sumaHumedad = parseFloat(sumaHumedadKg) + parseFloat(table.rows[i].cells[9].innerHTML);

    var campos = (document.getElementById("campos").innerHTML);
    $(document).ready(function(){
        for(var i = 0; i < JSON.parse(campos).length; i++) {
            if(!JSON.parse(campos)[i].visible){
                if(JSON.parse(campos)[i].codigo == 'pb' || JSON.parse(campos)[i].codigo == 'ag' || JSON.parse(campos)[i].codigo == 'sn'
                    || JSON.parse(campos)[i].codigo == 'au' || JSON.parse(campos)[i].codigo == 'sb'|| JSON.parse(campos)[i].codigo == 'cu'
                    || JSON.parse(campos)[i].codigo == 'zn' || JSON.parse(campos)[i].codigo == 'retencionesDeLey'
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

</script>
