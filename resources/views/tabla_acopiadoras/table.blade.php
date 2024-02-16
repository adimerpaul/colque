<div class="table-responsive">
    <table class="table table-striped" id="tablaAcopiadoras-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Gestión</th>
            <th>Nombre de la tabla</th>
            <th>Margen</th>
            <th>Sel.</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($tablaAcopiadoras as $tablaAcopiadora)
            <tr>
                <td>{{ $tablaAcopiadora->id }}</td>
                <td>{{ date('d/m/Y', strtotime($tablaAcopiadora->fecha)) }}</td>
                <td><strong>{{ $tablaAcopiadora->gestion }}</strong></td>
                <td>{{ $tablaAcopiadora->nombre }}</td>
                <td>{{ $tablaAcopiadora->margen }}</td>
                <td>
                    @if($tablaAcopiadora->es_seleccionado)
                        <i class="fa fa-check" style="color: red"></i>
                    @endif
                </td>
                <td style="width: 230px">
                    {!! Form::open(['route' => ['tablaAcopiadoras.destroy', $tablaAcopiadora->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        @if(\App\Patrones\Permiso::esAdmin())
                            <a href="{{ route('tablaAcopiadoras.seleccionar', [$tablaAcopiadora->id]) }}"
                               class='btn btn-info btn-xs'><i class="glyphicon glyphicon-check"></i> Seleccionar</a>
                        @endif
                        <a href="{{ route('tablaAcopiadoras.show', [$tablaAcopiadora->id]) }}"
                           class='btn btn-warning btn-xs'><i class="glyphicon glyphicon-eye-open"></i> Ver tabla</a>
                        @if(\App\Patrones\Permiso::esAdmin())
                            <a href="{{ route('tablaAcopiadoras.edit', [$tablaAcopiadora->id]) }}"
                               class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Estas seguro de eliminar?')"]) !!}

                        @endif
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
