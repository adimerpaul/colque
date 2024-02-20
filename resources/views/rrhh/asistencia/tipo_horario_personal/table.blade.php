<table table class="table">
    <thead class="table-red">
    <tr>
        <th scope="coll">#</th>
        <th scope="coll">Fecha inicial</th>
        <th scope="coll">Fecha Final</th>
        <th scope="coll">Tipo de horario</th>
        <th>
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach ($horario as $item)
    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{ \Carbon\Carbon::parse($item->fecha_inicial)->format('d/m/y') }}</td>
        <td>{{ \Carbon\Carbon::parse($item->fecha_fin)->format('d/m/y') }}</td>
        <td>{{ $item->tiposHorarios->horario }}</td>
        <td> @if(\App\Patrones\Permiso::esAdmin())    
                <a  class="glyphicon glyphicon-edit" href="#" data-target="#modalEdit"
                    style="margin-top: 7px;"
                    data-txtid="{{$item->id}}"
                    data-txtnombre="{{$item->personal->nombre_completo}}"
                    data-txtfecha="{{$item->fecha_fin}}"
                    data-toggle="modal"
                    title="Editar">
                </a>
            @endif
        </td>
        <td>
        {!! Form::open(['route' => ['tipo-horario-personal.eliminar', $item->id], 'method' => 'delete']) !!}
                               {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('¿Estás seguro de eliminar?')"]) !!}
                            {!! Form::close() !!}
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
{!! Form::open(['route' => 'tipo-horario-personal.editar', 'id' => 'guardarModal']) !!}
            @include("rrhh.asistencia.tipo_horario_personal.modal_edit")
{!! Form::close() !!} 
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
                modal.find('.modal-body #idfecha').val(id);
                modal.find('.modal-body #nombre').val(nombre);
                modal.find('.modal-body #fecha').val(fecha);


            })
    </script>
@endpush
