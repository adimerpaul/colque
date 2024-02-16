<table class="table table-striped">
    <thead class="table-red">
    <tr>
        <th scope="coll">#</th>
        <th scope="coll">Nombre</th>
        <th scope="coll">Fecha Marcada</th>
        <th scope="coll">Atraso</th>
        <th scope="coll">Horas Extras</th>
        <th scope="coll">Estado</th>



    </tr>
    </thead>
    <tbody>
    @foreach ($resultados as $item)
    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{ \App\Patrones\Fachada::getPersonal()[$item->personal_id] }}</td>
        <td>{{ \Carbon\Carbon::parse($item->hora_marcada)->format('d/m/Y H:i:s') }}
            <br>
            <small class='text-muted'>
                {{$item->dia}}
            </small>
        </td>
 
        <td>{{$item->atraso}}</td>
        <td>{{ $item->hora_extra_manana !== null && $item->hora_extra_manana !== 0 ? $item->hora_extra_manana : $item->hora_extra_tarde }}</td>

        <td>{!!  \App\Patrones\Fachada::estadoAsistencia($item->tipo_asistencia,$item->control_asistencia) !!}
            @if(!is_null($item->userEdicion)||!is_null($item->userRegistro))
            <br>
            <small class='text-muted'>
                <a  href="#" data-target="#modalDetalle"
                    style="margin-top: 7px;"
                    data-txtid="{{$item->id}}"
                    data-txtnombre="{{$item->personal->nombre_completo}}"
                    data-txtuserregistro="{{ $item->userRegistro->personal->nombre_completo ?? ''}}"
                    data-txtuseredit="{{$item->userEdicion->personal->nombre_completo ?? ''}}"
                    data-txtobservacion="{{$item->observacion}}"
                    data-toggle="modal"
                    >Detalle
                </a>
            </small>
            @endif
        </td>
        <td>    
            @if(\App\Patrones\Permiso::esAdmin())
                @if($item->tipo_asistencia != "horaExtra" && $item->tipo_asistencia != "permiso" && $item->tipo_asistencia != "feriado" && $item->tipo_asistencia != "falta")    
                    <a  class="glyphicon glyphicon-edit" href="#" data-target="#modalEdit"
                        data-txtid="{{$item->id}}"
                        data-txtnombre="{{$item->personal->nombre_completo}}"
                        data-txtfecha="{{$item->hora_marcada}}"
                        data-toggle="modal"
                        title="Editar">
                    </a>
                @endif
            @endif                                     
        </td>

                            
    </tr>
    @endforeach
        <tr>
            <td>
                <b style="text-align: center">
                    TOTALES
                </b>
            </td>
            <td></td>
            <td></td>
            <td><b><?php print($resultados->sum("sumatoria_atrasos_minutos"). " min")?></b></td>
            <td><b><?php print($resultados->sum("sumatoria_horas_extras") . " min" )?></b></td>
        </tr>
    </tbody>
</table>
    {!! Form::open(['route' => 'asistencias.editar', 'id' => 'guardarModal']) !!}
            @include("rrhh.asistencia.modal_edit")
    {!! Form::close() !!} 
    @include("rrhh.asistencia.modal_detalles")
        
@push('scripts')
    <script>
        $("#guardarModal").on("submit", function() {
            $("#guardar").prop("disabled", true);
        });
        $('#modalEdit').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var id = button.data('txtid')
                var nombre = button.data('txtnombre')
                var fecha = button.data('txtfecha')
                var modal = $(this)
                modal.find('.modal-body #idAsistencia').val(id);
                modal.find('.modal-body #nombre').val(nombre);
                modal.find('.modal-body #fecha').val(fecha);


            })
        $('#modalDetalle').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var nombre = button.data('txtnombre')
            var userRegistro = button.data('txtuserregistro')
            var userEdit = button.data('txtuseredit')
            var observacion = button.data('txtobservacion')
            var modal = $(this)
            modal.find('.modal-body #idAsistencia').val(id);
            modal.find('.modal-body #nombre').val(nombre);
            modal.find('.modal-body #userRegistro').val(userRegistro);
            modal.find('.modal-body #userEdit').val(userEdit);
            modal.find('.modal-body #observacion').val(observacion);
            if (modal.find('.modal-body #userRegistro').val() == "") {
                modal.find('.modal-body #aprobado').hide();}
            else {modal.find('.modal-body #aprobado').show();}
            if (modal.find('.modal-body #userEdit').val() == "") {
                modal.find('.modal-body #fechaMarcada').hide();}
            else {modal.find('.modal-body #fechaMarcada').show();}
            if (modal.find('.modal-body #observacion').val() == "") {
                modal.find('.modal-body #detalle').hide();}
            else {modal.find('.modal-body #detalle').show();}
        })

    </script>
@endpush

