<div id="appBono">
    <h4 class="text-center">DEVOLUCIONES</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Causa</th>
                <th>Glosa</th>
                <th>Monto (BOB)</th>
            </tr>
            </thead>
            <tbody id="tablaBonos">
            <tr v-for="(row, index) in bonosLista" :key="index">
                <td style="width: 80px">@{{ getDateOnly(row.fecha) }}</td>
                <td>@{{ row.causa }}</td>
                <td>@{{ row.motivo }}</td>
                <td style="width: 50px" class="text-right">@{{ row.monto }}</td>
                <td style="width: 80px">
                    <button v-if="formulario.es_escritura && row.clase != 'Externo'" title="Eliminar" @click="eliminarBono(row.id)"
                            class="btn btn-danger btn-xs">
                        <i class="glyphicon glyphicon-trash"></i>
                    </button>
                </td>
            </tr>
            <tr>
                <th colspan="3" class="text-right">TOTAL DEVOLUCIONES (BOB)</th>
                <th class="text-right">@{{ formulario.totales !== undefined ? redondear(formulario.totales.total_bonos) : 0 }}</th>
                <th></th>
            </tr>

            </tbody>
        </table>
    </div>
</div>

