<div class="table-responsive">
    <table class="table" id="choferes-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Licencia</th>
            <th>Nombre completo</th>
            <th>Celular</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($choferes as $chofer)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $chofer->licencia }}</td>
                <td>{{ $chofer->nombre }}</td>
                <td>{{ $chofer->celular }}</td>
                <td>

                    <div class='btn-group'>

                        <a href="{{ route('choferes.edit', [$chofer->id]) }}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
