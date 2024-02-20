<div class="table-responsive">
    <table class="table table-striped" id="vehiculo-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Placa</th>
            <th>Marca</th>
            <th>Color</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($vehiculos as $vehiculo)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $vehiculo->placa }}</td>
                <td>{{ $vehiculo->marca }}</td>
                <td>{{ $vehiculo->color }}</td>
                <td>

                    <div class='btn-group'>

                        <a href="{{ route('vehiculos.edit', [$vehiculo->id]) }}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
