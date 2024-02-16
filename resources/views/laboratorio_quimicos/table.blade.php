<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($laboratorios as $laboratorio)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $laboratorio->nombre }}</td>
                <td>{{ $laboratorio->direccion }}</td>
                <td style="width: 280px">
                    <div class='btn-group'>
                        <a href="{{ route('laboratorioQuimicos.edit', [$laboratorio->id]) }}" title="Editar" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>

                        <a href="{{ route('laboratorioPrecios.edit', [$laboratorio->id]) }}" title="Precios" class='btn btn-info btn-xs'><i
                                class="glyphicon glyphicon-usd"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
