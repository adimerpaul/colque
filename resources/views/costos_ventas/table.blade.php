<div id="appCostoVenta">
    <h4 class="text-center">OTROS COSTOS</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Descripción</th>
                <th>Monto (BOB)</th>
            </tr>
            </thead>
            <tbody id="tablaCostos">
            <tr v-for="(row, index) in costosLista" :key="index">
                <td>@{{ row.descripcion }}</td>
                <td style="width: 50px" class="text-right">@{{ row.monto }}</td>
                <td style="width: 80px">
                    @if($venta->estado==\App\Patrones\EstadoVenta::EnProceso AND \App\Patrones\Permiso::esComercial())
                        <button title="Eliminar" @click="eliminarOtroCosto(row.id)"
                                class="btn btn-danger btn-xs">
                            <i class="glyphicon glyphicon-trash"></i></button>
                    @endif

                </td>
            </tr>


            </tbody>
        </table>
    </div>
</div>

