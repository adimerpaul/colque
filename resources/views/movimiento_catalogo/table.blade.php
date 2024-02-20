<table table class="table">
    <thead class="table-dark">
    <tr>
        <th scope="coll">#</th>
        <th scope="coll">Descripción</th>
        <th scope="coll">Lote</th>
        <th scope="coll">Tipo</th>
        <th style="width: 100px"></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($movimientoCatalogo as $item)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ $item->descripcion}}</td>
            <td>
                @if ($item->es_lote)
                    <i class="fa fa-check"></i>
                @else
                    <i class="fa fa-times"></i>
                @endif
            </td>
            <td>{{ $item->tipo}}</td>
            <td>
                <a href="{{ route('movimientos-catalogos.edit', $item->id) }}" class='btn btn-default btn-xs' title='Editar'>
                    <i class="glyphicon glyphicon-edit"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
