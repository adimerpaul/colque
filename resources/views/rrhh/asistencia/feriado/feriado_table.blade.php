<table class="table table-striped">
    <thead class="table-red">
        <tr>
            <th scope="coll">#</th>
            <th scope="coll">Fecha</th>
            <th scope="coll">Desricipción</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach($feriados as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
           <td>{{ $item->motivo . ($item->es_turno ? ' (Medio día)' : '') }}</td>
            
            <td>
                {!! Form::open(['route' => ['feriado.delete', $item->id], 'method' => 'delete']) !!}
                                {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('¿Estás seguro de eliminar?')"]) !!}
                                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
