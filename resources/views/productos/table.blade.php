<div class="table-responsive">
    <table class="table table-striped" id="productos-table">
        <thead>
        <tr>
            <th>Código</th>
            <th>Producto</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->letra }}</td>
                <td>{{ $producto->nombre }}</td>
                <td style="width: 280px">
                    {!! Form::open(['route' => ['productos.destroy', $producto->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('productos.show', [$producto->id]) }}" class='btn btn-info btn-xs'><i
                                class="glyphicon glyphicon-list"></i> Minerales pagables y penalizables</a>
                        <a href="{{ route('productos.edit', [$producto->id]) }}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Estas seguro de eliminar?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
