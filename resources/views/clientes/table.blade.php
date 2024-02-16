<div class="table-responsive">
    <table class="table table-striped" id="clientes-table">
        <thead>
        <tr>
            <th>#</th>
            <th>CI</th>
            <th>Nombre</th>
            <th>Celular</th>
            <th>Puntos acumulados</th>
            <th>Estado</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($clientes->currentPage() - 1) * $clientes->perPage();
        $row = 1;
        ?>
        @foreach($clientes as $cliente)
            <tr>
                <td class="text-muted">{{ $page + ($row++) }}</td>
                <td>{{ $cliente->nit }}</td>
                <td>{{ $cliente->nombre }}</td>
                <td>{{ $cliente->celular }}</td>
                <td>{{ $cliente->total_puntos }}</td>
                <td>
                    @if($cliente->alta)
                        <span style="background-color: #2a9055; color: white; padding: 5px">Alta</span>
                    @else
                        <span style="background-color: #D32F2F; color: white; padding: 5px">Baja</span>
                    @endif
                </td>
                <td>

                    <div class='btn-group'>
                        {!! Form::open(['route' => ['clientes.destroy', $cliente->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('clientes.edit', [$cliente->id]) }}" class='btn btn-default btn-xs'><i
                                    class="glyphicon glyphicon-edit"></i></a>

                            @if(\App\Patrones\Permiso::esComercial())
                                <a href="{{ route('puntos-cliente', ['id' => $cliente->id]) }}" class='btn btn-primary btn-xs'><i
                                    class="glyphicon glyphicon-transfer" title="Canjear"></i></a>
                            @endif
                            @if($cliente->alta AND $cliente->puede_dar_baja AND \App\Patrones\Permiso::esComercial())
                                <a href="{{ route('clientes.cambiarEstado', ['id' => $cliente->id, 'estado' => '0']) }}" class='btn btn-danger btn-xs'><i
                                        class="glyphicon glyphicon-arrow-down" title="Dar baja"></i></a>
                            @endif

                            @if(!$cliente->alta AND \App\Patrones\Permiso::esComercial())
                                <a href="{{ route('clientes.cambiarEstado', ['id' => $cliente->id, 'estado' => '1']) }}" class='btn btn-success btn-xs'><i
                                        class="glyphicon glyphicon-arrow-up" title="Dar Alta"></i></a>
                            @endif

                            @if($cliente->puede_eliminarse AND \App\Patrones\Permiso::esComercial())
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'title' => 'Eliminar', 'onclick' => "return confirm('¿Estás seguro de eliminar?')"]) !!}
                            @endif


                        </div>
                        {!! Form::close() !!}

                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
