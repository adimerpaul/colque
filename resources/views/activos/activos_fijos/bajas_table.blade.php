<table table class="table">
    <thead class="table-red">
    <tr>



        <th scope="coll">Cantidad</th>
        <th scope="coll">Motivo</th>
        <th>

        </th>
    </tr>
    </thead>
    <tbody>
    @foreach ($bajas as $item)

    <tr>

        <td>{{ $item->cantidad}}</td>
        <td>{{ $item->motivo}}</td>
        <td>
            {!! Form::open(['route' => ['bajas-activos.destroy', $item->id], 'method' => 'delete']) !!}
                               {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('¿Estás seguro de eliminar?')"]) !!}
                            {!! Form::close() !!}
        </td>

    </tr>
    @endforeach
    </tbody>
</table>
