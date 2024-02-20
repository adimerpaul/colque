<div class="table-responsive">
    <table class="table" id="activos-tabla" style=" border: 1px solid black;">
        <thead class="table-dark">
        <tr>
            <th colspan="9" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>ACTIVOS FIJOS<br>
            </th>

        </tr>
        <tr>
            <th style="border: 1px solid black;">#</th>
            <th style="border: 1px solid black;">Código</th>
            <th style="border: 1px solid black;">N° Factura</th>
            <th style="border: 1px solid black;">Fecha Incorp.</th>
            <th style="border: 1px solid black;">Cantidad</th>
            <th style="border: 1px solid black;">Estado</th>
            <th style="border: 1px solid black;">Descripción</th>
            <th style="border: 1px solid black;">Valor Unitario (BOB)</th>
            <th style="border: 1px solid black;">Valor Total (BOB)</th>
            <th style="border: 1px solid black;">Tipo</th>
            <th style="border: 1px solid black;">Responsable</th>
            <th style="border: 1px solid black; width: 100px"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($activosFijos->currentPage() - 1) * $activosFijos->perPage();
        $row = 1;
        ?>
        @foreach ($activosFijos as $item)

            <tr>
                <td style=" border: 1px solid black;">{{ $page + ($row++) }}</td>
                <td style=" border: 1px solid black;">{{ $item->codigo}}</td>
                <td style=" border: 1px solid black;">{{ $item->factura}}</td>
                <td style=" border: 1px solid black;">{{ $item->fecha_adquisicion}}</td>
                <td style=" border: 1px solid black;">{{ $item->cantidad_unidad }}</td>
                <td style=" border: 1px solid black;">{{ $item->estado}}</td>
                <td style=" border: 1px solid black;">{{ $item->descripcion}}</td>
                <td style=" border: 1px solid black;">{{ $item->valor_unitario}}</td>           
                <td style=" border: 1px solid black;">{{ $item->precio_total}}</td>
                <td style=" border: 1px solid black;">{{ $item->tipo->nombre}}</td>
                <td style=" border: 1px solid black;"> @if($item->personal) {{ $item->personal->nombre_completo}}@endif </td>


                @if(\App\Patrones\Permiso::esActivos())
                    <td style="border: 1px solid black; width: 145px">
                        <div class='btn-group'>

                            <div class="btn-group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    Opciones<i style="margin-top: 3px" class="fa fa-angle-down pull-right"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class='dropdown-item' href="{{ route('activos-fijos.edit', $item->id) }}"
                                    >
                                        <i class="glyphicon glyphicon-edit"></i>
                                        Editar
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class='dropdown-item'
                                       href="{{ route('imprimir-activo-fijo', ['id' => $item->id]) }}"
                                       target="_blank">
                                        <i class="glyphicon glyphicon-qrcode"></i>
                                        Imprimir
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class='dropdown-item'
                                       href="{{ route('baja-activo', ['id' => $item->id]) }}"
                                    >
                                        <i class="glyphicon glyphicon-arrow-down"></i>
                                        Dar de Baja
                                    </a>

                                    <div class="dropdown-divider"></div>
                                    <a class='dropdown-item'
                                       href="{{ route('nueva-factura', ['id' => $item->id]) }}"
                                    >
                                    <i class="glyphicon glyphicon-plus"></i> Añadir</a>

                                    <div class="dropdown-divider"></div>

                                </div>
                            </div>
                        </div>
                    </td>
                @else
                    <td style=" border: 1px solid black;">
                        <a href="{{ route('activos-fijos.edit', $item->id) }}" class='btn btn-default btn-xs'
                           title='Editar'>
                            <i class="glyphicon glyphicon-edit"></i>
                        </a>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    <br>


</div>
