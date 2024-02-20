<div class="table-responsive">
    <table class="table" id="compradores-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Nit</th>
            <th>Nro. Nim</th>
            <th>Razón Social</th>
            <th>Dirección</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($compradores as $comprador)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $comprador->nit }}</td>
                <td>{{ $comprador->nro_nim }}</td>
                <td>{{ $comprador->razon_social }}</td>
                <td>{{ $comprador->direccion }}</td>
                <td>
                    <div class='btn-group'>
                        <a href="{{ route('compradores.edit', [$comprador->id]) }}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
