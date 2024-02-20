<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="cuentas-table">
        <thead>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Comprobante</th>
            <th style=" border: 1px solid black;">Fecha</th>
            <th style=" border: 1px solid black;">Cliente</th>
            <th style=" border: 1px solid black;">Productor</th>
            <th style=" border: 1px solid black;">Monto BOB</th>
            <th style=" border: 1px solid black;">Registrado por</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($prestamos->currentPage() - 1) * $prestamos->perPage();
        $row = 1;
        ?>
        @foreach($prestamos as $prestamo)
            <tr>
                <td style=" border: 1px solid black;" class="text-muted">{{ $page + ($row++) }}</td>
                <td style=" border: 1px solid black;"><strong>{{ $prestamo->codigo_caja }}</strong></td>
                <td style=" border: 1px solid black;">{{ date('d/m/y', strtotime($prestamo->updated_at)) }}</td>
                <td style=" border: 1px solid black;">{!! $prestamo->cliente->nombre !!}<br><small
                        class='text-muted'>{!! $prestamo->cliente->nit !!}</small></td>
                <td style=" border: 1px solid black;">{!! $prestamo->cliente->cooperativa->razon_social !!}</td>
                <td style=" border: 1px solid black;">{{ number_format($prestamo->monto, 2) }}</td>
                <td style=" border: 1px solid black;">
                    @if($prestamo->registrado)
                        {{ $prestamo->registrado->personal->nombre_completo }}
                    @endif
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>

</div>
