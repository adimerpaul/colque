<div class="table-responsive">
    <table class="table" id="tipoCambios-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Comercial</th>
            <th>Oficial</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($tipoCambios as $tipoCambio)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ date('d/m/Y', strtotime($tipoCambio->fecha))  }}</td>
                <td>{{ $tipoCambio->dolar_compra }}</td>
                <td>{{ $tipoCambio->dolar_venta }}</td>
                <td>
                    @if(\App\Patrones\Fachada::tieneCotizacion())
                    <div class='btn-group'>
                        <a href="{{ route('tipoCambios.edit', [$tipoCambio->id]) }}" class='btn btn-default btn-xs'><i
                                class="glyphicon glyphicon-edit"></i></a>
                    </div>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
