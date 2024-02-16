<div class="table-responsive">
    <table class="table" id="cotizacionOficials-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Fechas (Desde - Hasta)</th>
            <th>Unidad</th>
            <th>Cotización oficial</th>
            <th>Alicuota <br>exportación</th>
            <th>Alicuota <br>ventas internas</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($cotizacionOficials as $cotizacion)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>
                    {{ date('d/m/Y', strtotime($cotizacion->fecha)) }}
                    -
                    {{ $cotizacion->fechaFinal }}
                </td>
                <td>{{ $cotizacion->unidad }}</td>
                <td>{{ $cotizacion->monto }}</td>
                <td>{{ $cotizacion->alicuota_exportacion }}</td>
                <td>{{ $cotizacion->alicuota_interna }}</td>
                <td
                    {{-- @if(\App\Patrones\Fachada::tieneCotizacion()) --}}
                    <div class='btn-group'>
                        <a href="{{ route('cotizacionOficials.edit', [$cotizacion->id]) }}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                    </div>
                    {{-- @endif --}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
