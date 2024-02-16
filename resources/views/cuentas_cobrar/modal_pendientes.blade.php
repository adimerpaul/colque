<div id="modalCuentasPendientes" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" id="appCuentasPendientes">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cuentas por cobrar del cliente pendientes</h4>
            </div>
            <div class="modal-body">

                <div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr class="bg-info">
                                <th>Fecha</th>
                                <th>Motivo</th>
                                <th>Monto (BOB)</th>
                            </tr>

                            <tbody id="myTable">
                            <tr v-for="(row, index) in cuentasPendientes" :key="index">
                                <td style="width: 80px">@{{ getDateOnly(row.created_at) }}</td>
                                <td>@{{ row.motivo }}</td>
                                <td style="width: 50px" class="text-right">@{{ row.monto }}</td>

                                <td style="width: 80px">
                                    <button title="Agregar" @click="agregarCuenta(row.id)"
                                            class="btn btn-primary btn-xs">
                                        <i class="glyphicon glyphicon-plus"></i></button>

{{--                                    <a v-if="formulario.es_escritura" title="Transferir" data-toggle="modal" href="#"--}}
{{--                                       :data-txtid="row.id"--}}
{{--                                       data-target="#modalCuenta"--}}
{{--                                       class="btn btn-info btn-xs">--}}
{{--                                        <i class="glyphicon glyphicon-share-alt"></i>--}}
{{--                                    </a>--}}
                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
            <div class="modal-footer" style="border-top: none">
            </div>
        </div>

    </div>
</div>


