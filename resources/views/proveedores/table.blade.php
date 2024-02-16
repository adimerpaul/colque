<div class="table-responsive">
    <table class="table" id="proveedores-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Nit</th>
            <th>Nombre completo</th>
            <th>Empresa</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($proveedores as $proveedor)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $proveedor->nit }}</td>
                <td>{{ $proveedor->nombre }}</td>
                <td>{{ $proveedor->empresa }}</td>
                <td>

                    <div class='btn-group'>

                        <a href="{{ route('proveedores.edit', [$proveedor->id]) }}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
