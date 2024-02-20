<table class="table table-striped" id="insumos-table">
    <thead>
    <tr>
        <th style="border: 1px solid black;">#</th>
        <th style="border: 1px solid black;">Fecha</th>
        <th style="border: 1px solid black;">Insumo</th>
        <th style="border: 1px solid black;">Unidad</th>
        <th style="border: 1px solid black;">Cantidad Mínima</th>
        <th style="border: 1px solid black;">Stock</th>
        <th style="border: 1px solid black;"></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $page = ($insumos->currentPage() - 1) * $insumos->perPage();
    $row = 1;
    ?>
    @foreach($insumos as $insumo)
        <tr>
            <td style="border: 1px solid black;">{{ $page + ($row++) }}</td>
            <td style="border: 1px solid black;">{{ $insumo->fecha }}</td>
            <td style="border: 1px solid black;">{{ $insumo->nombre }}</td>
            <td style="border: 1px solid black;">{{ $insumo->unidad }}</td>
            <td style="border: 1px solid black;">
                {{ number_format($insumo->cantidad_minima, 2) }}
            </td>
            <td style="border: 1px solid black;">
                @if($insumo->stock < $insumo->cantidad_minima)
                    <span style="background-color: #E53935; color: white; padding-left: 10px; padding-right: 10px; padding-top: 3px">{{ number_format($insumo->stock, 2) }}</span>
                @else
                    <span>{{ number_format( $insumo->stock, 2) }}</span>
                @endif
            </td>

            <td style="border: 1px solid black;">

                <div class='btn-group'>
                    <div class='btn-group'>
                        <a href="#" data-target="#modalEdicion"
                           data-txtid="{{$insumo->id}}"
                           data-txtnombre="{{$insumo->nombre}}"
                           data-txtunidad="{{$insumo->unidad}}"
                           data-txtcantidad="{{$insumo->cantidad_minima}}"
                           data-toggle="modal"
                           class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>

                        <a href="#" data-target="#modalIngreso"
                           data-txtid="{{$insumo->id}}"
                           data-txtnombre="{{$insumo->nombre}}"
                           data-toggle="modal"
                           title="Aumentar"
                           class='btn btn-success btn-xs'><i
                                class="glyphicon glyphicon-plus"></i></a>
                        <a href="#" data-target="#modalCantidadActual"
                           data-txtid="{{$insumo->id}}"
                           data-txtnombre="{{$insumo->nombre}}"
                           data-toggle="modal"
                           title="Disminuir"
                           class='btn btn-danger btn-xs'><i
                                class="glyphicon glyphicon-minus"></i></a>

                        <a href="{{ route('insumos.show', [$insumo->id]) }}"
                           title="Ingresos/Egresos"
                           class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-list"></i></a>

                    </div>

                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
