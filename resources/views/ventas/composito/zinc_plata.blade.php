<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="kardex-tabla" name="kardex-tabla">
        <thead>
        <tr>
            <th colspan="18" style="text-align: center; border: 0px white !important">COMPOSITO
                <br>LOTE COLQUECHACA: {{$venta->lote}}
                <br>CÓDIGO: {{$venta->letra}}
                <br>PRODUCTO: ZINC - PLATA
                <br><br>
            </th>
            <th colspan="2" style="text-align: left; border: 0px white !important">
                @if($venta->estado==\App\Patrones\EstadoVenta::Liquidado)
                    PRI: {{$venta->periodo_rotacion}}
                @endif

                @if($venta->es_cancelado)<br>PC: {{$venta->periodo_cobro}}@endif
                <br><br><br>
            </th>
            <th colspan="3" style="text-align: left; border: 0px white !important">
                @if($venta->estado==\App\Patrones\EstadoVenta::Liquidado)
                    FECHA RECEPCIÓN (MEDIA): {{date('d/m/y', strtotime($venta->fecha_promedio))}}
                @endif
                <br>FECHA DE ENTREGA:
                <br>FECHA DE LIQUIDACIÓN: {{date('d/m/y', strtotime($venta->fecha_venta))}}
                @if($venta->es_cancelado)<br>FECHA DE COBRO: {{date('d/m/y', strtotime($venta->fecha_cobro))}}@endif
                <br><br>
            </th>
        </tr>
        <tr style="background-color: #042E44; color: white;">
            <th rowspan="2" style=" border: 1px solid #999999;">N°</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="fechaLiquidacion">FEC. LIQ.</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="loteCompra">LOTE DE COMPRA</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="razonSocial">PRODUCTOR</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="pesoNetoHumedo">PESO NETO HUMEDO (Kg)</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="humedadPorcentaje">Humedad (%)</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="pesoNetoSeco">PESO NETO SECO (Kg)</th>
            <th colspan="3" style=" border: 1px solid #999999; text-align: center" id="zn" class="zn">ZN</th>
            <th colspan="3" style=" border: 1px solid #999999; text-align: center" id="ag" class="ag">AG</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="valorNetoVenta">VALOR NETO VENTA</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="regaliaMinera">REGALIA MINERA</th>

            <th rowspan="2" style=" border: 1px solid #999999;" id="bonoTransporte">BONO TRANSPORTE</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="bonoCliente">BONO CLIENTE</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="comisionExterna">COMISIÓN EXTERNA</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="laboratorio">COSTO LABORATORIO</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="pesaje">COSTO PESAJE</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="proProductor">PRO PRODUCTOR</th>
            <th rowspan="2" style=" border: 1px solid #999999;" id="promocion">PROMOCIÓN</th>

            <th rowspan="2" style=" border: 1px solid black; min-width: 70px"></th>
        </tr>
        <tr style="background-color: #042E44; color: white">
            <th style=" border: 1px solid #999999;" id="zn" class="zn">%Zn</th>
            <th style=" border: 1px solid #999999;" id="zn" class="zn">Peso Fino (Kg)</th>
            <th style=" border: 1px solid #999999;" id="zn" class="zn">C. Diaria</th>
            <th style=" border: 1px solid #999999;" id="ag" class="ag">DM Ag</th>
            <th style=" border: 1px solid #999999;" id="ag" class="ag">Peso Fino (Kg)</th>
            <th style=" border: 1px solid #999999;" id="ag" class="ag">C. Diaria</th>
        </tr>
        </thead>
        <tbody id="tbody">
        <?php
        $row = 1;
        ?>
        @foreach($formularios as $formulario)
            <tr>
                <td style=" border: 1px solid black;">{{($row++)}}</td>
                <td style=" border: 1px solid black;" id="fechaLiquidacionTd"
                    class="fechaLiquidacionTd">{{ date('d/m/y', strtotime($formulario->fecha_liquidacion)) }}</td>
                <td style=" border: 1px solid black;" id="loteCompraTd"
                    clas="loteCompraTd">{{ $formulario->lote_sin_gestion }}</td>

                <td style=" border: 1px solid black;"
                    id="razonSocial">{{ $formulario->cliente->cooperativa->razon_social }}</td>

                <td style=" border: 1px solid black;" id="pesoNetoHumedoTd"
                    class="pesoNetoHumedoTd">{{number_format($formulario->peso_neto,2, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="humedadPorcentajeTd"
                    class="humedadPorcentajeTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='H2O'){{number_format($lab->promedio,2, ',', '')}} @endif
                    @endforeach
                </td>

                <td style=" border: 1px solid black;" id="pesoNetoSecoTd"
                    class="pesoNetoSecoTd">{{number_format($formulario->peso_neto_seco,2, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="leyZnTd" class="znTd">
                    @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Zn') {{number_format($lab->promedio,2, ',', '')}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="pesoFinoZnTd"
                    class="znTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Zn'){{number_format(($lab->promedio * $formulario->peso_neto_seco / 100),2, ',', '')}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" id="cotizacionZnTd"
                    class="znTd">@if(round($formulario->cotizacion_zn,2)!=0){{number_format($formulario->cotizacion_zn,2, ',', '')}}@endif</td>
                <td style=" border: 1px solid black;" class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{number_format($lab->promedio,2, ',', '')}} @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;" class="agTd"> @foreach($formulario->laboratorio_promedio as $lab)
                        @if($lab->simbolo=='Ag'){{number_format(($lab->promedio * $formulario->peso_seco / 10000),2, ',', '')}}  @endif
                    @endforeach
                </td>
                <td style=" border: 1px solid black;"
                    class="agTd">{{number_format($formulario->cotizacion_ag,2, ',', '')}}</td>

                <td style=" border: 1px solid black;" id="valorNetoVentaTd"
                    class="valorNetoVentaTd">{{number_format($formulario->neto_venta,2, ',', '')}}</td>
                <td style=" border: 1px solid black;" id="regaliaMineraTd"
                    class="regaliaMineraTd">{{number_format($formulario->regalia_minera,2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="bonoTransporteTd"
                    class="bonoTransporteTd">{{number_format($formulario->bonificaciones_descuentos_bob['transporte'],2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="bonoClienteTd"
                    class="bonoClienteTd">{{number_format(($formulario->bonificaciones_descuentos_bob['productor']),2, ',', '') }}</td>


                <td style=" border: 1px solid black;" id="comisionExternaTd"
                    class="comisionExternaTd">{{number_format(($formulario->costo_comision),2, ',', '') }}</td>

                <td style=" border: 1px solid black;" id="laboratorioTd"
                    class="laboratorioTd">{{number_format(($formulario->costo->laboratorio + $formulario->costo->dirimicion),2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="pesajeTd"
                    class="pesajeTd">{{number_format($formulario->costo_pesaje,2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="proProductorTd"
                    class="proProductor">{{number_format(( $formulario->costo_pro_productor),2, ',', '') }}</td>
                <td style=" border: 1px solid black;" id="promocionTd"
                    class="promocionTd">{{number_format(($formulario->puntos * 0.5),2, ',', '') }}</td>

                <td style=" border: 1px solid black;">
                    @if($formulario->estado==\App\Patrones\Estado::Composito AND \App\Patrones\Permiso::esComercial())

                        {!! Form::open(['route' => ['ventas.destroy', $formulario->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'title' => 'Eliminar', 'onclick' => "return confirm('Estás seguro de eliminar?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    @endif
                </td>
            </tr>

        @endforeach
        @foreach($ingenios as $ingenio)
            <tr>
                <td style=" border: 1px solid black;">{{($row++)}}</td>
                <td style=" border: 1px solid black;" name="fecha">{{ date('d/m/y', strtotime($ingenio->fecha)) }}
                </td>
                <td style="border: 1px solid black;" colspan="1" name="nombre">
                    @if(is_null($ingenio->ingenio_id))
                        {{  $ingenio->nombre }}
                    @else
                        <a style="color: black" href="{{ route('ventas.edit', $ingenio->origen_ingenio->id) }}"
                           target="_blank"> INGENIO: {{ $ingenio->origen_ingenio->lote }}</a>
                    @endif
                </td>
                <td style="border: 1px solid black;" colspan="1" name="nombre"
                >{{ $ingenio->nombre }}
                </td>
                <td style=" border: 1px solid black;" name="peso_neto_humedo"
                >{{ number_format($ingenio->peso_neto_humedo,2, ',', '') }}
                </td>
                <td style=" border: 1px solid black;" name="humedad"
                >{{ number_format($ingenio->humedad,2, ',', '') }}
                </td>

                <td style=" border: 1px solid black;">{{ number_format($ingenio->peso_neto_seco,2, ',', '') }}</td>
                <td style=" border: 1px solid black;" name="ley_zn"
                >@if(round($ingenio->ley_zn,2)!=0){{ number_format($ingenio->ley_zn,2, ',', '') }}@endif
                </td>
                <td style=" border: 1px solid black;">@if(round($ingenio->peso_fino_zn,2)!=0){{ number_format($ingenio->peso_fino_zn,2, ',', '') }}@endif</td>
                <td style=" border: 1px solid black;" name="cotizacion_zn"
                >@if(round($ingenio->cotizacion_zn,2)!=0){{ number_format($ingenio->cotizacion_zn,2, ',', '') }}@endif
                </td>
                <td style=" border: 1px solid black;" name="ley_ag"
                >{{ number_format($ingenio->ley_ag,2, ',', '') }}
                </td>
                <td style=" border: 1px solid black;">{{ number_format($ingenio->peso_fino_ag,2, ',', '') }}</td>
                <td style=" border: 1px solid black;" name="cotizacion_ag"
                >{{ number_format($ingenio->cotizacion_ag,2, ',', '') }}
                </td>

                <td style=" border: 1px solid black;" name="valor_neto_venta"
                >{{ number_format($ingenio->valor_neto_venta,2, ',', '') }}
                </td>
                <td style=" border: 1px solid black;" name="regalia_minera"
                >{{ number_format($ingenio->regalia_minera,2, ',', '') }}
                </td>

                <td style=" border: 1px solid black;">0,00</td>
                <td style=" border: 1px solid black;">0,00</td>
                <td style=" border: 1px solid black;">0,00</td>
                <td style=" border: 1px solid black;">0,00</td>
                <td style=" border: 1px solid black;">0,00</td>
                <td style=" border: 1px solid black;">0,00</td>
                <td style=" border: 1px solid black;">0,00</td>
                <td style=" border: 1px solid black;">
                    <button title="Eliminar"
                            @click="eliminarConcentrado({{$ingenio->id}})"
                            class="btn btn-danger btn-xs">
                        <i class="glyphicon glyphicon-trash"></i></button>
                </td>
            </tr>
        @endforeach
        <tr style="background-color: #042E44; color: white">
            <td rowspan="3" colspan="4" class="text-center" style=" border: 1px solid #999999;">
                <b style="text-align: center">
                    COMPOSITO FINAL
                </b>
            </td>

            <td rowspan="3" style=" border: 1px solid #999999; font-weight: bold" id="pesoNetoHumedoTotal">/td>
            <td rowspan="3" style=" border: 1px solid #999999;font-weight: bold" id="humedadPorcentajeTotal"></td>
            <td rowspan="3" style=" border: 1px solid #999999; font-weight: bold" id="pesoNetoSecoTotal"></td>
            <td rowspan="3" style=" border: 1px solid #999999; font-weight: bold" id="leyZnTotal" class="znTotal"></td>
            <td rowspan="3" style=" border: 1px solid #999999; font-weight: bold" id="pesoFinoZnTotal"
                class="znTotal"></td>
            <td rowspan="3" style=" border: 1px solid #999999; font-weight: bold" id="cotizacionZnTotal"
                class="znTotal"></td>
            <td rowspan="3" style=" border: 1px solid #999999; font-weight: bold" id="leyAgTotal" class="agTotal"></td>
            <td rowspan="3" style=" border: 1px solid #999999; font-weight: bold" id="pesoFinoAgTotal"
                class="agTotal"></td>
            <td rowspan="3" style=" border: 1px solid #999999; font-weight: bold" id="cotizacionAgTotal"
                class="agTotal"></td>
            <td rowspan="3" style=" border: 1px solid #999999; font-weight: bold" id="valorNetoVentaTotal"></td>
            <td rowspan="3" style=" border: 1px solid #999999; font-weight: bold" id="regaliaMineraTotal"></td>

            <td style=" border: 1px solid #999999; font-weight: bold" id="bonoTransporteTotal">
            </td>
            <td style=" border: 1px solid #999999; font-weight: bold" id="bonoClienteTotal">
            </td>

            <td style=" border: 1px solid #999999; font-weight: bold" id="comisionExternaTotal"></td>
            <td style=" border: 1px solid #999999; font-weight: bold" id="laboratorioTotal"></td>
            <td style=" border: 1px solid #999999; font-weight: bold" id="pesajeTotal"></td>
            <td style=" border: 1px solid #999999; font-weight: bold" id="proProductorTotal"></td>
            <td style=" border: 1px solid #999999; font-weight: bold" id="promocionTotal"></td>
        </tr>
        <tr style="background-color: #042E44; color: white">
            <td colspan="2" style=" border: 1px solid #999999; font-weight: bold">COSTO DE MERCADERÍA</td>
            <td colspan="2" style=" border: 1px solid #999999; font-weight: bold" id="costoMercaderia"></td>
            <td colspan="2"
                style=" border: 1px solid #999999; font-weight: bold; background-color: #042E44; color: white">OTROS
                COSTOS
            </td>
            <td colspan="1"
                style=" border: 1px solid #999999; font-weight: bold; background-color: #042E44; color: white"

                :value="venta.costos_otros" name="otros_costos" id="otrosCostos">
                @{{agregarComa(venta.costos_otros)}}
            </td>
        </tr>

        <tr>
            <td colspan="4"
                style=" border: 1px solid #999999; font-weight: bold; background-color: #042E44; color: white">COSTO
                TOTAL DE COMERCIALIZACIÓN
            </td>
            <td colspan="3"
                style=" border: 1px solid #999999; font-weight: bold; background-color: #042E44; color: white">
                @{{ costoComercializacion }}
            </td>
            <td>
                <button title="Agregar costo" data-target="#modalOtrosCostos" data-toggle="modal"
                        class="btn btn-primary btn-xs">
                    <i class="glyphicon glyphicon-plus"></i></button>
            </td>

        </tr>
        </tbody>
        <tfoot id="pie">

        <tr v-for="(concentrado, index) in concentrados" :key="index">
            <td style=" border: 1px solid black;">@{{ index=index+1 }}</td>
            <td style=" border: 1px solid black;"
                :contenteditable="concentrado.habilitado_ingenio &&{{ ($venta->es_escritura AND \App\Patrones\Permiso::esComercial()) ? 'true': 'false'}}"
                :value="concentrado.fecha" :id="concentrado.id" name="fecha"
                v-on:blur="actualizarConcentrado($event)">@{{ getDateOnly(concentrado.fecha) }}
            </td>
            <td style="border: 1px solid black;" colspan="2"
                :contenteditable="concentrado.habilitado_ingenio && concentrado.tipo_lote=='Venta' && {{ ($venta->es_escritura AND \App\Patrones\Permiso::esComercial()) ? 'true': 'false'}}"
                :value="concentrado.nombre"
                :id="concentrado.id" name="nombre"
                v-on:blur="actualizarConcentrado($event)">@{{ concentrado.nombre }}
            </td>

            <td style=" border: 1px solid black;"
                :contenteditable="concentrado.habilitado_ingenio &&{{ ($venta->es_escritura AND \App\Patrones\Permiso::esComercial()) ? 'true': 'false'}}"
                :value="concentrado.peso_neto_humedo" :id="concentrado.id" name="peso_neto_humedo"
                v-on:blur="actualizarConcentrado($event)">@{{ agregarComa(concentrado.peso_neto_humedo) }}
            </td>
            <td style=" border: 1px solid black;"
                :contenteditable="concentrado.habilitado_ingenio &&{{ ($venta->es_escritura AND \App\Patrones\Permiso::esComercial()) ? 'true': 'false'}}"
                :value="concentrado.humedad" :id="concentrado.id" name="humedad"
                v-on:blur="actualizarConcentrado($event)">@{{ agregarComaCuatro(concentrado.humedad) }}
            </td>

            <td style=" border: 1px solid black;">@{{ agregarComa(concentrado.peso_neto_seco) }}</td>
            <td style=" border: 1px solid black;"
                :contenteditable="concentrado.habilitado_ingenio &&{{ ($venta->es_escritura AND \App\Patrones\Permiso::esComercial()) ? 'true': 'false'}}"
                :value="concentrado.ley_zn" :id="concentrado.id" name="ley_zn"
                v-on:blur="actualizarConcentrado($event)">@{{ agregarComa(concentrado.ley_zn) }}
            </td>
            <td style=" border: 1px solid black;">@{{ agregarComa(concentrado.peso_fino_zn) }}</td>
            <td style=" border: 1px solid black;"
                :contenteditable="concentrado.habilitado_ingenio &&{{ ($venta->es_escritura AND \App\Patrones\Permiso::esComercial()) ? 'true': 'false'}}"
                :value="concentrado.cotizacion_zn" :id="concentrado.id" name="cotizacion_zn"
                v-on:blur="actualizarConcentrado($event)">@{{ agregarComa(concentrado.cotizacion_zn) }}
            </td>
            <td style=" border: 1px solid black;"
                :contenteditable="concentrado.habilitado_ingenio &&{{ ($venta->es_escritura AND \App\Patrones\Permiso::esComercial()) ? 'true': 'false'}}"
                :value="concentrado.ley_ag" :id="concentrado.id" name="ley_ag"
                v-on:blur="actualizarConcentrado($event)">@{{ agregarComa(concentrado.ley_ag) }}
            </td>
            <td style=" border: 1px solid black;">@{{ agregarComa(concentrado.peso_fino_ag) }}</td>
            <td style=" border: 1px solid black;"
                :contenteditable="concentrado.habilitado_ingenio &&{{ ($venta->es_escritura AND \App\Patrones\Permiso::esComercial()) ? 'true': 'false'}}"
                :value="concentrado.cotizacion_ag" :id="concentrado.id" name="cotizacion_ag"
                v-on:blur="actualizarConcentrado($event)">@{{ agregarComa(concentrado.cotizacion_ag) }}
            </td>

            <td style=" border: 1px solid black;"
                :contenteditable="concentrado.habilitado_ingenio &&{{ ($venta->es_escritura AND \App\Patrones\Permiso::esComercial()) ? 'true': 'false'}}"
                :value="concentrado.valor_neto_venta" :id="concentrado.id" name="valor_neto_venta"
                v-on:blur="actualizarConcentrado($event)">@{{ agregarComa(concentrado.valor_neto_venta) }}
            </td>
            <td style=" border: 1px solid black;"
                :contenteditable="concentrado.habilitado_ingenio &&{{ ($venta->es_escritura AND \App\Patrones\Permiso::esComercial()) ? 'true': 'false'}}"
                :value="concentrado.regalia_minera" :id="concentrado.id" name="regalia_minera"
                v-on:blur="actualizarConcentrado($event)">@{{ agregarComa(concentrado.regalia_minera) }}
            </td>

            <td style=" border: 1px solid black;" colspan="7">@{{ concentrado.total }}</td>
            <td style=" border: 1px solid black;">
                <p style="color: blue; font-weight: bold"> @{{concentrado.lote_destino_ingenio}}</p>
                @if($venta->estado==\App\Patrones\EstadoVenta::EnProceso AND \App\Patrones\Permiso::esComercial())
                    <div v-if="concentrado.habilitado_ingenio==true">
                        <button title="Eliminar" @click="eliminarConcentrado(concentrado.id)"
                                class="btn btn-danger btn-xs">
                            <i class="glyphicon glyphicon-trash"></i></button>
                        <button title="Editar merma" @click="editarMerma(concentrado.id, concentrado.merma_porcentaje)"
                                class="btn btn-warning btn-xs">
                            <i class="glyphicon glyphicon-pencil"></i></button>

                        @if($venta->tipo_lote==\App\Patrones\TipoLoteVenta::Ingenio)
                            <button title="Enviar a lote"
                                    @click="dialogoEnviarLote(concentrado.id)"
                                    class="btn btn-success btn-xs">
                                <i class="glyphicon glyphicon-send"></i></button>
                        @endif
                        <button v-if="concentrado.tipo_lote=='Sobrante'"
                                title="Enviar a lote"
                                @click="dialogoEnviarLote(concentrado.id)"
                                class="btn btn-success btn-xs">
                            <i class="glyphicon glyphicon-send"></i></button>
                    </div>
                @endif
            </td>
        </tr>
        <tr style="background-color: #042E44; color: white">
            <td colspan="6" class="text-center" style=" border: 1px solid #999999; font-weight: bold">
                DIFERENCIAS / RECUPERACIÓN / UTILIDAD BRUTA
            </td>
            <td style=" border: 1px solid #999999; font-weight: bold">@{{ pnsSuma }}</td>
            <td style=" border: 1px solid #999999; font-weight: bold">@{{ leyZnSuma }}</td>
            <td style=" border: 1px solid #999999; font-weight: bold">@{{ pesoFinoZnSuma }}</td>
            <td style=" border: 1px solid #999999; font-weight: bold">@{{ cotizacionZnSuma }}</td>
            <td style=" border: 1px solid #999999; font-weight: bold">@{{ leyAgSuma }}</td>
            <td style=" border: 1px solid #999999; font-weight: bold">@{{ pesoFinoAgSuma }}</td>
            <td style=" border: 1px solid #999999; font-weight: bold">@{{ cotizacionAgSuma }}</td>

            <td style=" border: 1px solid #999999; font-weight: bold" colspan="2">@{{ porcentajeSuma }}</td>
            <td style=" border: 1px solid #999999; font-weight: bold" colspan="7">@{{ totalSuma }}</td>
            <td>
                <button title="Refrescar concentrados" @click="getConcentrados"
                        class="btn btn-info btn-xs">
                    <i class="glyphicon glyphicon-refresh"></i></button>
            </td>
        </tr>
        </tfoot>

    </table>

</div>
<script type="application/javascript">
    var tbody = document.getElementById("tbody")
    var table = document.getElementById("kardex-tabla"), sumaHumedad = 0, sumaLeyZn = 0,
        sumaPesoFinoZn = 0
        , sumaPesoFinoAg = 0, costoMercaderia = 0
        , sumaComisionExterna = 0, sumaLaboratorio = 0, sumaBonoTransporte = 0, sumaBonoCliente = 0
        , sumaPromocion = 0, sumaCotizacionZn = 0, sumaPesoNetoSeco = 0, sumaPesoNetoSecoZn = 0
        , sumaLeyAg = 0, sumaCotizacionAg = 0, sumaPesaje = 0, sumaProProductor = 0
        , sumaPesoNeto = 0, sumaNetoVenta = 0, sumaRegalia = 0;
    for (var i = 0; i < (tbody.rows.length - 3); i++) {
        sumaPesoNeto = parseFloat(sumaPesoNeto) + parseFloat(tbody.rows[i].cells[4].innerHTML.replace(/,/g, "."));
        sumaNetoVenta = parseFloat(sumaNetoVenta) + parseFloat(tbody.rows[i].cells[13].innerHTML.replace(/,/g, "."));
        sumaRegalia = parseFloat(sumaRegalia) + parseFloat(tbody.rows[i].cells[14].innerHTML.replace(/,/g, "."));
        sumaPesoFinoZn = parseFloat(sumaPesoFinoZn) +
            (tbody.rows[i].cells[8].innerHTML.trim() == '' ? parseFloat(0.0) : parseFloat(tbody.rows[i].cells[8].innerHTML.replace(/,/g, ".")));
        sumaPesoFinoAg = parseFloat(sumaPesoFinoAg) + parseFloat(tbody.rows[i].cells[11].innerHTML.replace(/,/g, "."));

        sumaBonoTransporte = parseFloat(sumaBonoTransporte) + parseFloat(tbody.rows[i].cells[15].innerHTML.replace(/,/g, "."));
        sumaBonoCliente = parseFloat(sumaBonoCliente) + parseFloat(tbody.rows[i].cells[16].innerHTML.replace(/,/g, "."));

        sumaComisionExterna = parseFloat(sumaComisionExterna) + parseFloat(tbody.rows[i].cells[17].innerHTML.replace(/,/g, "."));
        sumaLaboratorio = parseFloat(sumaLaboratorio) + parseFloat(tbody.rows[i].cells[18].innerHTML.replace(/,/g, "."));
        sumaPesaje = parseFloat(sumaPesaje) + parseFloat(tbody.rows[i].cells[19].innerHTML.replace(/,/g, "."));
        sumaProProductor = parseFloat(sumaProProductor) + parseFloat(tbody.rows[i].cells[20].innerHTML.replace(/,/g, "."));
        sumaPromocion = parseFloat(sumaPromocion) + parseFloat(tbody.rows[i].cells[21].innerHTML.replace(/,/g, "."));

        sumaCotizacionZn = parseFloat(sumaCotizacionZn) + (
            tbody.rows[i].cells[9].innerHTML.trim() == '' ? parseFloat(0.0) :
                (parseFloat(tbody.rows[i].cells[6].innerHTML.replace(/,/g, ".")) * parseFloat(tbody.rows[i].cells[9].innerHTML.replace(/,/g, "."))));
        sumaPesoNetoSecoZn = parseFloat(sumaPesoNetoSecoZn) + (tbody.rows[i].cells[9].innerHTML.trim() == '' ? parseFloat(0.0) : parseFloat(tbody.rows[i].cells[6].innerHTML.replace(/,/g, ".")));

        sumaPesoNetoSeco = parseFloat(sumaPesoNetoSeco) + parseFloat(tbody.rows[i].cells[6].innerHTML.replace(/,/g, "."));
        sumaCotizacionAg = parseFloat(sumaCotizacionAg) + (parseFloat(tbody.rows[i].cells[6].innerHTML.replace(/,/g, ".")) * parseFloat(tbody.rows[i].cells[12].innerHTML.replace(/,/g, ".")));

    }
    sumaHumedad = ((parseFloat("{{ $formularios->sum('humedad_kilo')}}") + parseFloat("{{ $ingenios->sum('humedad_kg')}}")) /
        (sumaPesoNeto)) * 100;
    sumaCotizacionZn = sumaCotizacionZn == 0 ? 0.00 : parseFloat(sumaCotizacionZn) / parseFloat(sumaPesoNetoSecoZn);
    sumaCotizacionAg = parseFloat(sumaCotizacionAg) / parseFloat(sumaPesoNetoSeco);
    sumaLeyZn = ((parseFloat(sumaPesoFinoZn) / parseFloat(sumaPesoNetoSeco)) * 100);
    sumaLeyAg = ((parseFloat(sumaPesoFinoAg) / parseFloat(sumaPesoNetoSeco)) * 10000);

    document.getElementById("humedadPorcentajeTotal").innerHTML = agregarComma(sumaHumedad.toFixed(2));
    document.getElementById("leyZnTotal").innerHTML = agregarComma(sumaLeyZn.toFixed(2));
    document.getElementById("pesoFinoZnTotal").innerHTML = agregarComma(sumaPesoFinoZn.toFixed(2));
    document.getElementById("leyAgTotal").innerHTML = agregarComma(sumaLeyAg.toFixed(2));
    document.getElementById("pesoFinoAgTotal").innerHTML = agregarComma(sumaPesoFinoAg.toFixed(2));

    document.getElementById("bonoTransporteTotal").innerHTML = agregarComma(sumaBonoTransporte.toFixed(2));
    document.getElementById("bonoClienteTotal").innerHTML = agregarComma(sumaBonoCliente.toFixed(2));
    document.getElementById("comisionExternaTotal").innerHTML = agregarComma(sumaComisionExterna.toFixed(2));
    document.getElementById("laboratorioTotal").innerHTML = agregarComma(sumaLaboratorio.toFixed(2));
    document.getElementById("pesajeTotal").innerHTML = agregarComma(sumaPesaje.toFixed(2));
    document.getElementById("promocionTotal").innerHTML = agregarComma(sumaPromocion.toFixed(2));
    document.getElementById("cotizacionZnTotal").innerHTML = agregarComma(sumaCotizacionZn.toFixed(2));
    document.getElementById("cotizacionAgTotal").innerHTML = agregarComma(sumaCotizacionAg.toFixed(2));
    document.getElementById("proProductorTotal").innerHTML = agregarComma(sumaProProductor.toFixed(2));

    document.getElementById("pesoNetoHumedoTotal").innerHTML = agregarComma(sumaPesoNeto.toFixed(2));
    document.getElementById("pesoNetoSecoTotal").innerHTML = agregarComma(sumaPesoNetoSeco.toFixed(2));
    document.getElementById("valorNetoVentaTotal").innerHTML = agregarComma(sumaNetoVenta.toFixed(2));
    document.getElementById("regaliaMineraTotal").innerHTML = agregarComma(sumaRegalia.toFixed(2));


    costoMercaderia = parseFloat(sumaNetoVenta) + parseFloat(sumaBonoTransporte) + parseFloat(sumaBonoCliente)
        + parseFloat(sumaComisionExterna) + parseFloat(sumaPesaje)
        + parseFloat(sumaLaboratorio) + parseFloat(sumaPromocion) + parseFloat(sumaProProductor);

    document.getElementById("costoMercaderia").innerHTML = agregarComma(costoMercaderia.toFixed(2));

    function agregarComma(nStr) {
        return nStr.replace('.', ',')
    }

</script>
