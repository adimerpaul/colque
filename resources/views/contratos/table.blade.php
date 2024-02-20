<div class="table-responsive">
    <table class="table" id="contrato-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Producto</th>
            <th>Laboratorio</th>
            <th>Manipuleo</th>
            <th>Molienda</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($contratos as $contrato)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $contrato->producto->nombre }}</td>
                <td>{{ $contrato->laboratorio }}</td>
                <td>{{ $contrato->manipuleo }}</td>
                <td>{{ $contrato->molienda }}</td>
                <td>

                    <div class='btn-group'>
                        @if(\App\Patrones\Permiso::esAdmin())
                        <a href="{{ route('contratos.edit', [$contrato->id]) }}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit" title="Editar"></i></a>
                        @endif
                        <a href="{{ route('contratos.show', [$contrato->id]) }}" class='btn btn-info btn-xs'><i
                                class="glyphicon glyphicon-eye-open" title="Detalle"></i> </a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
