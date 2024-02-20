<table table class="table">
    <thead class="table-dark">
    <tr>
        <th>#</th>
        <th>Nombre</th>
        <th>Código</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($tipos as $item)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ $item->nombre}}</td>
            <td>{{ $item->prefijo}} {{ '- ' }}{{ $item->numero }}</td>
            <td>
            <a href="{{ route('tipos-activos.edit', $item->id) }}" class='btn btn-default btn-xs' title='Editar'>
                    <i class="glyphicon glyphicon-edit"></i>
                </a>

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
