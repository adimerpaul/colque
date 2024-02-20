<table class="table table-bordered"
       style="border:1px solid; font-size: 11px; border: #ECEFF1; border-collapse: collapse; width: 100%; margin-top: -20px">
    <thead>
    <tr style="background-color: #ECEFF1; border: #ECEFF1; border:1px solid; text-align: center">
        <th style="text-align: center" rowspan="2">1. CANTIDAD, TARA, HUMEDAD Y MERMA</th>
        <th style="text-align: center" rowspan="2">2. CALIDAD ELEMENTO</th>
        <th style="text-align: center" colspan="3">3. PRECIOS Y ALICUOTAS</th>
        <th style="text-align: center" rowspan="2">4. TIPO DE CAMBIO</th>
    </tr>
    <tr style="background-color: #ECEFF1; border: #ECEFF1; border:1px solid;">
        <th style="text-align: center">COT. DIARIA</th>
        <th style="text-align: center">COT. OFICIAL</th>
        <th style="text-align: center">ALICUOTA</th>
    </tr>
    </thead>
    <tbody>
    <tr style="font-size: 11px; vertical-align: top; ">
        <td style="width: 35%; border: #ECEFF1; border:1px solid; padding-left: 3px">
            <p style="margin-top: 2px"><strong>PESO BRUTO
                    HÚMEDO: </strong> <label
                    style="float: right; margin-right: 3px"> {{ number_format($formularioLiquidacion->peso_bruto, 2) }}
                    KG</label><br></p>
            <p style="margin-top: -8px">
                <strong>TARA:
                    @if($formularioLiquidacion->presentacion =='Ensacado')
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ({{ $formularioLiquidacion->sacos }} Sacos)
                    @endif
                </strong> <label
                    style="float: right; margin-right: 3px"> {{ number_format($formularioLiquidacion->tara, 3) }}
                    KG</label><br></p>
            <p style="margin-top: -8px"><strong>PESO NETO
                    HÚMEDO: </strong> <label
                    style="float: right; margin-right: 3px"> {{ number_format($formularioLiquidacion->peso_neto, 2) }}
                    KG</label><br></p>
            <p style="margin-top: -8px"><strong>HUMEDAD:&nbsp;&nbsp;
                    @foreach($formularioLiquidacion->laboratorio_promedio as $form)
                        @if($form->simbolo=='H2O')
                            {{ number_format($form->promedio,2) }} {{ $form->unidad }}
                        @endif
                    @endforeach
                </strong>
                <label
                    style="float: right; margin-right: 3px"> {{ number_format($formularioLiquidacion->humedad_kg, 3) }}
                    KG</label>

                <br>
            </p>
            <p style="margin-top: -8px">
                <strong>MERMA:
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ number_format($formularioLiquidacion->merma, 2) }}
                    % </strong> <label
                    style="float: right; margin-right: 3px"> {{ number_format($formularioLiquidacion->merma_kg, 2) }}
                    KG</label><br></p>

            <p style="margin-top: -8px"><strong>PESO NETO
                    SECO: </strong> <label
                    style="float: right; margin-right: 3px"> {{ number_format($formularioLiquidacion->peso_seco, 2) }}
                    KG</label></p>
        </td>
        <td style="width: 15%; border: #ECEFF1; border:1px solid; padding-left: 3px">

            <div style="margin-top: 2px">
                @foreach($formularioLiquidacion->laboratorio_promedio as $form)
                    <template>
                        @if($form->simbolo!='H2O')
                            <strong>{{ $form->simbolo }}
                                : </strong>{{ number_format($form->promedio,3) }} {{ $form->unidad }}<br>
                        @endif
                    </template>
                @endforeach
            </div>
        </td>

        <td style="width: 12%; text-align: center">

            <div style="margin-top: 2px">

                @foreach($cotizacionesDiarias as $diaria)
                    @if($formularioLiquidacion->id==9172)
                        @if($diaria->mineral_id==1)
                            24.88 $us/OT
                            @elseif($diaria->mineral_id==2)
                            0.96 $us/LF
                            @endif
                    @elseif($formularioLiquidacion->id==9539 or $formularioLiquidacion->id==9559)

                        @if($diaria->mineral_id==1)
                            23.938 $us/OT
                        @elseif($diaria->mineral_id==2)
                            0.925 $us/LF
                        @endif

                    @elseif($formularioLiquidacion->id==10358)

                        @if($diaria->mineral_id==1)
                            22.978 $us/OT
                        @elseif($diaria->mineral_id==2)
                            0.920 $us/LF
                        @endif
                    @else
                        {{ ($formularioLiquidacion->con_cotizacion_promedio ? number_format($formularioLiquidacion->cotizacion_promedio_ag, 2): number_format($diaria->monto_form,2)) . ' ' .$diaria->unidad_form }}
                    @endif
                    <br>
                @endforeach
                @if($formularioLiquidacion->con_cotizacion_promedio and $formularioLiquidacion->cliente->cooperativa_id==44 and $formularioLiquidacion->created_at >='2023-03-01 00:00:00')
                    <br>
                    <label style="font-size: 10px;">
                        <strong>COTS. PROM.</strong><br>
                    {{$formularioLiquidacion->cotizaciones_promedio_ag}}
                    </label>
                @endif
            </div>
        </td>
        <td style="width: 14%; text-align: center">
            <div style="margin-top: 2px">
                @foreach($formularioLiquidacion->minerales_regalia as $oficial)
                    @php
                        $oficial = (object)$oficial
                    @endphp
                    {{ number_format($oficial->cotizacion_oficial, 2) . ' $us/' .$oficial->unidad }}<br>
                @endforeach
            </div>
        </td>
        <td style="width: 12%; text-align: center">
            <div style="margin-top: 2px">
                @foreach($formularioLiquidacion->minerales_regalia as $oficial)
                    @php
                        $oficial = (object)$oficial
                    @endphp
                    {{ number_format($oficial->alicuota_interna, 2) }} %<br>
                @endforeach
            </div>
        </td>

        <td style="width: 20%; border: #ECEFF1; border:1px solid; padding-left: 3px">
            <p style="margin-top: 2px">
                <strong>Oficial: </strong> <label
                    style="float: right; margin-right: 3px">{{ number_format($formularioLiquidacion->tipoCambio->dolar_venta, 2) }} </label>
            </p>

            @if($formularioLiquidacion->cliente->cooperativa_id!=44)
                <p style="margin-top: -8px">
                    <strong>Comercial: </strong> <label
                        style="float: right; margin-right: 3px"> {{ number_format($formularioLiquidacion->tipoCambio->dolar_compra, 2) }} </label>
                </p>
            @endif
        </td>
    </tr>
    </tbody>

</table>
