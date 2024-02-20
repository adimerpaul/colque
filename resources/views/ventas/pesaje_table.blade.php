<div id="appPesaje">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Conductor</th>
                <th>Vehículo</th>
                <th>Nro. Pesaje</th>
                <th>PBH (Kg)</th>
                <th>Tara (Kg)</th>
                <th>PNH (Kg)</th>
            </tr>
            </thead>
            <tbody id="tablaPesajes">
            <tr v-for="(row, index) in pesajesLista" :key="index">
                <td>@{{ row.chofer.nombre }}</td>
                <td>@{{ row.vehiculo.placa }}</td>
                <td>@{{ row.numero_pesaje }}</td>
                <td>@{{ row.peso_bruto_humedo }}</td>
                <td>@{{ row.tara }}</td>
                <td>@{{ dosDecimales(row.peso_neto_humedo) }}</td>

                <td style="width: 80px">
{{--                    @if(\App\Patrones\Permiso::esOperaciones())--}}
                    @if(\App\Patrones\Permiso::esComercial())
                        <button v-if="venta.es_escritura" title="Eliminar" @click="eliminarPesaje(row.id)"
                                class="btn btn-danger btn-xs">
                            <i class="glyphicon glyphicon-trash"></i></button>
                    @endif
                </td>
            </tr>
            <tr>
                <th colspan="3" class="text-center">TOTALES</th>
                <th>@{{ sumaPesajeBruto }}</th>
                <th></th>
                <th>@{{ sumaPesajeNeto }}</th>
                <th></th>
            </tr>

            </tbody>
        </table>
    </div>
</div>

