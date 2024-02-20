<table class="table table-bordered" style="background-color: #f9f9f9">
    <tr>
        <td>
            <p class="text-center"><strong>PESOS</strong></p>
            <strong>Peso bruto húmedo: </strong> @{{ redondear(formulario.peso_bruto) }} KG <br>
            <strong>Tara: </strong> @{{ redondear(formulario.tara) }} KG<br>
            <strong>Peso neto húmedo: </strong> @{{ redondear(formulario.peso_neto) }} KG<br>
            <strong>Humedad: </strong> @{{ redondear(formulario.humedad_kg) }} KG<br>
            <strong>Merma: </strong>
            {{--            {!! Form::number('merma', null, ['min' => 0, 'max' => 1, 'required', 'disabled'=> !$formularioLiquidacion->esEscritura ]) !!}--}}
            <input type="number" name="merma" id="merma" min="0" max="1" v-model="formulario.merma" required
                   :disabled="!formulario.es_escritura">
            @{{ redondear(formulario.merma_kg) }} KG<br>
            <strong>Peso neto seco: </strong> @{{ redondear(formulario.peso_neto_seco) }} KG<br>
        </td>
        <td>
            <p class="text-center"><strong>CALIDAD</strong></p>
            <div v-for="row in formulario.laboratorio_promedio" :key="row.mineral_id">
                <template>
                    <strong>@{{ row.simbolo }}: </strong> @{{ redondearTres(row.promedio) }} @{{ row.unidad }}<br>
                </template>
            </div>
        </td>
        <td>
            <p class="text-center"><strong>COTIZACION DIARIA</strong></p>
            <div v-for="row in diarias" :key="row.id">
                <strong>@{{ row.mineral.simbolo }} (@{{ row.unidad_form }}): </strong>
                @if($formulario->con_cotizacion_promedio)
                    {{number_format($formulario->cotizacion_promedio_ag, 3)}}

                @elseif($formulario->id==9172)
                    <span v-if="row.mineral.simbolo=='Ag'">24.878</span>
                    <span v-else>0.961</span>
                @elseif($formulario->id==9539 or $formulario->id==9559)
                    <span v-if="row.mineral.simbolo=='Ag'">23.938</span>
                    <span v-else>0.925</span>
                @elseif($formulario->id==10358)
                    <span v-if="row.mineral.simbolo=='Ag'">22.978</span>
                    <span v-else>0.920</span>
                @else
                    @{{ redondearTres(row.monto_form) }}
                @endif
                <br>
            </div>

            <p class="text-center"><strong>COTIZACION OFICIAL</strong></p>
            <div v-if="formulario.minerales_regalia !== undefined" v-for="row in formulario.minerales_regalia"
                 :key="row.id">
                <strong>@{{ row.simbolo }} (USD/@{{ row.unidad }}): </strong> @{{ redondear(row.cotizacion_oficial)
                }}<br>
            </div>
        </td>
        <td>
            <p class="text-center"><strong>TIPO DE CAMBIO</strong></p>
            <strong>Comercial: </strong> @{{ redondear(formulario.tipo_cambio.dolar_compra) }}<br>
            <strong>Oficial: </strong> @{{ redondear(formulario.tipo_cambio.dolar_venta) }}<br>
        </td>
    </tr>
</table>
