<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="kardex-tabla" name="kardex-tabla">
        <thead>
        <tr>
            <th colspan="43" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>PRODUCTO:
                @if($productoLetra==='A')
                    ZINC (ZN) - PLATA (AG)
                @elseif($productoLetra==='B')
                    PLOMO (PB) - PLATA (AG)
                @elseif($productoLetra==='C')
                    COMPLEJO
                @elseif($productoLetra==='D')
                    ESTAÑO (SN)
                @elseif($productoLetra==='E')
                    PLATA (AG)
                @elseif($productoLetra==='F')
                    ANTIMONIO (SB)
                @elseif($productoLetra==='G')
                    COBRE (CU)
                @endif

                <br>
                @if($fechaInicio)<b id="fechas"></b>@endif
                <br>PRODUCTOR: {{$productor->razon_social}}
                <br><br>
            </th>

        </tr>
        <tr>
            <th rowspan="2" style=" border: 1px solid black;">N°</th>
            <th rowspan="2" style=" border: 1px solid black;">LOTE DE COMPRA</th>
            <th rowspan="2" style=" border: 1px solid black;">CLIENTE</th>
            <th rowspan="2" style=" border: 1px solid black;">VALOR NETO VENTA</th>
            <th rowspan="2" style=" border: 1px solid black;">REGALIA MINERA</th>

            @if($nroRetenciones >0)
                <th colspan="{{$nroRetenciones}}" style=" border: 1px solid black; text-align: center">RETENCIONES DE
                    LEY
                </th>
            @endif
            @if($nroDescuentos >0)
                <th colspan="{{$nroDescuentos}}" style=" border: 1px solid black; text-align: center">DESCUENTOS
                    INSTITUCIONALES
                </th>
            @endif
            <th rowspan="2" style=" border: 1px solid black;">TOTAL RETENCIONES Y DESCUENTOS</th>
            @if($nroBonificaciones >0)
                <th colspan="{{$nroBonificaciones}}" style=" border: 1px solid black; text-align: center"
                >BONIFICACIONES
                </th>
            @endif
            <th rowspan="2" style=" border: 1px solid black;">TOTAL BONIFICACIONES</th>
            <th rowspan="2" style=" border: 1px solid black;">LIQUIDO PAGABLE</th>
            <th rowspan="2" style=" border: 1px solid black;">ANTICIPO/ENTREGA</th>
            <th rowspan="2" style=" border: 1px solid black;">CUENTA POR SALDO NEGATIVO</th>
            <th rowspan="2" style=" border: 1px solid black;">CUENTA POR PRÉSTAMO</th>
            <th rowspan="2" style=" border: 1px solid black;">CUENTA POR RETIRO</th>
            <th rowspan="2" style=" border: 1px solid black;">APORTE FUNDACIÓN</th>
            <th rowspan="2" style=" border: 1px solid black;">DEVOLUCIÓN ANTICIPO</th>
            <th rowspan="2" style=" border: 1px solid black;">DEVOLUCIÓN ANÁLISIS</th>
            <th rowspan="2" style=" border: 1px solid black;">SALDO A FAVOR</th>
            <th rowspan="2" style=" border: 1px solid black;">RESPALDO DE PAGO</th>
            <th rowspan="2" style=" border: 1px solid black;">MÉTODO PAGO</th>

        </tr>
        <tr>
            @foreach($retenciones as $retencion)
                <th style=" border: 1px solid black;"
                    id="{{$retencion->nombre}}">{{$retencion->nombre}}</th>
            @endforeach
            @foreach($descuentos as $descuento)
                <th style=" border: 1px solid black;"
                    id="{{$descuento->nombre}}">{{$descuento->nombre}}</th>
            @endforeach

            @foreach($bonificaciones as $bonificacion)
                <th style=" border: 1px solid black;"
                    id="{{$bonificacion->nombre}}">{{$bonificacion->nombre}}</th>
            @endforeach

        </tr>
        </thead>
        <tbody>
        @foreach($formularios as $formulario)
            <tr>
                <td style=" border: 1px solid black;">{{$loop->iteration}}</td>
                <td style=" border: 1px solid black;">{{ $formulario->lote_sin_gestion }}</td>
                <td style=" border: 1px solid black;">{{ $formulario->cliente->nombre}}</td>
                <td style=" border: 1px solid black;">{{round($formulario->neto_venta,2)}}</td>
                <td style=" border: 1px solid black;">{{round($formulario->regalia_minera,2) }}</td>

                @foreach($retenciones as $retencion)
                    <td style=" border: 1px solid black;" id="{{'retencion'.$loop->iteration}}">
                        {{round(($formulario->retenciones_cooperativa[$retencion->nombre]),2)}}
                    </td>
                @endforeach
                @foreach($descuentos as $descuento)
                    <td style=" border: 1px solid black;" id="{{'descuento'.$loop->iteration}}">
                        {{round(($formulario->descuentos_cooperativa[$descuento->nombre]),2)}}
                    </td>
                @endforeach

                <td style=" border: 1px solid black;">{{round(($formulario->total_retencion_descuento),2) }}</td>

                @foreach($bonificaciones as $bonificacion)
                    <td style=" border: 1px solid black;" id="{{'bonificacion'.$loop->iteration}}">
                        {{round(($formulario->bonificaciones_cooperativa[$bonificacion->nombre]),2)}}
                    </td>
                @endforeach

                <td style=" border: 1px solid black;">{{round($formulario->total_bonificacion,2) }}</td>

                <td style=" border: 1px solid black;">{{round($formulario->liquido_pagable,2) }}</td>
                <td style=" border: 1px solid black;">{{round($formulario->total_anticipo,2) }}</td>
                <td style=" border: 1px solid black;">{{round($formulario->cuentas_saldo_negativo,2) }}</td>
                <td style=" border: 1px solid black;">{{round($formulario->cuentas_prestamo,2) }}</td>
                <td style=" border: 1px solid black;">{{round($formulario->cuentas_retiro,2) }}</td>

                <td style=" border: 1px solid black;">{{round(($formulario->aporte_fundacion),2) }}</td>
                <td style=" border: 1px solid black;">{{round(($formulario->devolucion_anticipo),2) }}</td>
                <td style=" border: 1px solid black;">{{round(($formulario->devolucion_laboratorio),2) }}</td>
                <td style=" border: 1px solid black;">{{round(($formulario->saldo_favor),2) }}</td>
                <td style=" border: 1px solid black;">
                    @if(date('Y-m-d', strtotime($formulario->fecha_cancelacion)) <= $fechaFinal)
                        {{$formulario->recibo_bancario }}
                    @endif
                </td>
                <td style=" border: 1px solid black;">@if(date('Y-m-d', strtotime($formulario->fecha_cancelacion)) <= $fechaFinal){{$formulario->tipo_pago }} @endif</td>

            </tr>
        @endforeach
        @include('cooperativas.reporte_contabilidad.totales_individuales')

        <tr style="background-color: #90CAF9">
            <td colspan="3" class="text-center" style=" border: 1px solid black;">
                <b style="text-align: center">
                    TOTALES
                </b>
            </td>
            <td style=" border: 1px solid black;"><b> {{ round($formularios->sum('neto_venta'),2)}}</b></td>
            <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->sum('regalia_minera'),2)}}</td>
            @foreach($retenciones as $retencion)
                <td style=" border: 1px solid black; font-weight: bold">{{round(($retencionesTotales[$retencion->nombre]),2)}}</td>
            @endforeach
            @foreach($descuentos as $descuento)
                <td style=" border: 1px solid black; font-weight: bold">{{round(($descuentosTotales[$descuento->nombre]),2)}}</td>
            @endforeach

            <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->sum('total_retencion_descuento'),2)}}</td>
            @foreach($bonificaciones as $bonificacion)
                <td style=" border: 1px solid black; font-weight: bold">{{round(($bonificacionesTotales[$bonificacion->nombre]),2)}}</td>
            @endforeach
            <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->sum('total_bonificacion'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->sum('liquido_pagable'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->sum('total_anticipo'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->sum('cuentas_saldo_negativo'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->sum('cuentas_prestamo'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->sum('cuentas_retiro'),2)}}</td>

            <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->sum('aporte_fundacion'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->sum('devolucion_anticipo'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->sum('devolucion_laboratorio'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('saldo_favor','>', '0.00')->sum('saldo_favor'),2)}}</td>
            <td style=" border: 1px solid black; font-weight: bold"></td>
            <td style=" border: 1px solid black; font-weight: bold"></td>


        </tr>
        </tbody>
    </table>
</div>
<script>
    document.getElementById("fechas").innerHTML = "CORRESPONDIENTE A LAS FECHAS: {{ date('d/m/y', strtotime($fechaInicio)) }} AL {{ date('d/m/y', strtotime($fechaFinal)) }}";

</script>
