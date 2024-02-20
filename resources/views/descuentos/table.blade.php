<div class="table-responsive">
    <table class="table" id="descuentos-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Valor</th>
            <th>Unidad</th>
            <th>Funcion</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($descuentos as $descuento)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $descuento->nombre }}</td>
                <td>{{ $descuento->tipo_denominacion }}</td>
                <td>{{ $descuento->valor }}</td>
                <td>{{ $descuento->unidad }}</td>
                <td>{{ $descuento->en_funcion }} </td>
                <td>
                    <div class='btn-group'>
                        @if(!$descuento->ya_se_utilizo)
                            @if($descuento->clase==\App\Patrones\ClaseDescuento::EnLiquidacion)
                            <a href="{{ route('descuentosBonificaciones.edit', [$descuento->id]) }}"
                               class='btn btn-default btn-xs'><i
                                    class="glyphicon glyphicon-edit"></i></a>
                            @endif
                        @endif
                        @if($descuento->alta)
                            @if(!$descuento->ya_se_utilizo  and $descuento->clase==\App\Patrones\ClaseDescuento::EnLiquidacion)
                                <a href="{{ route('descuentosBonificaciones.cambiarEstado', ['id' => $descuento->id, 'estado' => '0']) }}"
                                   class='btn btn-danger btn-xs' title="Dar de baja"><i
                                        class="glyphicon glyphicon-arrow-down"></i>&nbsp;</a>
                            @endif

                        @else
                            <a href="{{ route('descuentosBonificaciones.cambiarEstado', ['id' => $descuento->id, 'estado' => '1']) }}"
                               class='btn btn-success btn-xs' title="Dar de alta"><i
                                    class="glyphicon glyphicon-arrow-up"></i>&nbsp;</a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
