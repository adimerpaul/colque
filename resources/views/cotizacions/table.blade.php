<div class="table-responsive">
    <table class="table" id="cotizacions-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Cotización</th>
            <th>Unidad</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($cotizacions as $cotizacion)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ date('d/m/Y', strtotime($cotizacion->fecha)) }}</td>
                <td>{{ $cotizacion->monto }}</td>
                <td>{{ $cotizacion->unidad }}</td>
                <td>
                    {{-- @if(\App\Patrones\Fachada::tieneCotizacion()) --}}
                    <div class='btn-group'>
                        <a href="{{ route('cotizacions.edit', [$cotizacion->id]) }}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                    </div>
                    {{-- @endif --}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
