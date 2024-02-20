<!-- Modal -->
<div id="modalDescuento" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@{{ title }}</h4>
            </div>
            <div class="modal-body">
                <div v-if="faltantes.length <= 0">
                    No existen @{{ title }} para agregar
                </div>
                <table v-else class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Valor</th>
                        <th>En función a</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in faltantes" :key="index">
                        <td>@{{ row.nombre }}</td>
                        <td class="text-center">@{{ redondear(row.valor) }} @{{
                            row.unidad === 'Porcentaje' ? '%' :
                            row.unidad === 'Cantidad' ? 'Cant' :
                            row.unidad === 'Dolar/Tonelada' ? 'USD/Ton' :
                            'Cte'
                            }}</td>
                        <td>@{{ row.en_funcion }}</td>
                        <td style="width: 50px">
                            <button type="button" class="btn btn-primary btn-sm" @click="agregarDescuento(row.id, row.tipo)">Agregar al formulario</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeModal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
