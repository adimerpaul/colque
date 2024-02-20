<div class="table-responsive">
    <table class="table" id="empresas-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Identificación <br> Tributaria</th>
            <th>Razón <br> Social</th>
            <th>Dirección</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Celular</th>
            <th>Alta</th>
            <th>Usuarios <br>Permitidos</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @php
            $nro = 1;
        @endphp
        @foreach($empresas as $empresa)
            <tr>
                <td>{{ $nro++ }}</td>
                <td>{{ $empresa->identificacion_tributaria }}</td>
                <td>{{ $empresa->razon_social }}</td>
                <td>{{ $empresa->direccion }}</td>
                <td>{{ $empresa->email }}</td>
                <td>{{ $empresa->telefono }}</td>
                <td>{{ $empresa->celular }}</td>
                <td>
                    @if($empresa->alta)
                        <span class="label label-success">Alta</span>
                    @else
                        <span class="label label-danger">Baja</span>
                    @endif
                </td>
                <td>{{ $empresa->cantidad_usuario }}</td>
                <td>
                    {!! Form::open(['route' => ['empresas.destroy', $empresa->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('empresas.show', [$empresa->id]) }}" class='btn btn-info btn-xs'><i
                                class="glyphicon glyphicon-user"></i> usuarios</a>
                        <a href="{{ route('empresas.edit', [$empresa->id]) }}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Estas seguro de eliminar?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
