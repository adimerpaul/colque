<div id="modalDetalle" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg"> <!-- Se añadió la clase modal-lg para hacer el modal más grande en pantallas grandes -->

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-size: 16px; display: inline-block;">Detalle permisos:</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive"> <!-- Se añadió la clase table-responsive para hacer la tabla responsive -->
                    <table id="permisos-table" class="table table-striped" style="border: 1px solid black;">
                        <thead class="thead-dark">
                            <tr>
                                <th colspan="5" style="text-align: center; border: 0px white !important">
                                    CANTIDAD DE PERMISO
                                    <br>{{$nombre}}
                                </th>
                            </tr>
                            <tr>
                                <th style="border: 1px solid black;">#</th>
                                <th style="border: 1px solid black;">Tipo de permiso</th>
                                <th style="border: 1px solid black;">Cantidad Acumulada</th>
                                <th style="border: 1px solid black;">Cantidad Solicitada</th>
                                <th style="border: 1px solid black;">Cantidad Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($permisos as $item)
                                <tr>
                                    <td style="border: 1px solid black;">{{ $loop->iteration }}</td>
                                    <td style="border: 1px solid black;">{{ $item->tipoPermiso->descripcion }}</td>
                                    <td style="border: 1px solid black;">{{ \App\Patrones\Fachada::minutosAHorasPermisos($item->tipo_permiso_id, $item->cantidad_permiso) }}</td>
                                    <td style="border: 1px solid black;">{{ \App\Patrones\Fachada::minutosAHorasPermisos($item->tipo_permiso_id, $item->permisos_sacados) }}</td>
                                    <td style="border: 1px solid black; {{ \App\Patrones\Fachada::determinarEstiloFondo($item->tipo_permiso_id,$item->cantidad_actual) }}">
                                        {{ \App\Patrones\Fachada::minutosAHorasPermisos($item->tipo_permiso_id, $item->cantidad_actual) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No se tienen permisos habilitados </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none">
                <!-- Puedes agregar botones u otros elementos en el pie del modal si es necesario -->
            </div>
        </div>
    </div>
</div>
