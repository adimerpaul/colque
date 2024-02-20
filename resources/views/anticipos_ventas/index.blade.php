<div id="appAnticipo">
    <h4 class="text-center">ANTICIPOS CANCELADOS</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Observaciones</th>
                <th>Monto (BOB)</th>
            </tr>
            </thead>
            <tbody id="tablaAnticipos">
            <tr v-for="(row, index) in anticiposLista" :key="index">
                <td style="width: 80px">@{{ getDateYear(row.created_at) }}</td>
                <td>@{{ row.motivo }}</td>
                <td style="width: 50px" class="text-right">@{{ row.monto }}</td>
                <td style="width: 80px">
                    @if(\App\Patrones\Permiso::esComercial())
                        <button v-if="venta.es_escritura && !row.es_cancelado" title="Eliminar" @click="eliminarAnticipo(row.id)"
                                class="btn btn-danger btn-xs">
                            <i class="glyphicon glyphicon-trash"></i></button>
                    @endif
                    <button @click="imprimirAnticipo(row.id)" title="Imprimir" class="btn btn-warning btn-xs">
                        <i class="glyphicon glyphicon-print"></i></button>
                </td>
            </tr>
            <tr>
                <th colspan="2" class="text-right">TOTAL ANTICIPOS (BOB)</th>
                <th class="text-right">@{{ dosDecimales(venta.total_anticipos) }}</th>
                <th></th>
            </tr>

            </tbody>
        </table>
    </div>
</div>

