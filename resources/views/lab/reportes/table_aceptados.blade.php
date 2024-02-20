<div class="table-responsive">
    <table class="table table-striped" id="aceptados-table" >
        <thead>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Fecha</th>
            <th style=" border: 1px solid black;">Código</th>
            <th style=" border: 1px solid black;">Cliente</th>
            <th style=" border: 1px solid black;">Nit</th>
            <th style=" border: 1px solid black;">Lote</th>

        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($ensayos->currentPage() - 1) * $ensayos->perPage();
        $row = 1;
        ?>
        @foreach($ensayos as $ensayo)
            <tr>
                <td style=" border: 1px solid black;" class="text-muted">{{ $page + ($row++) }}</td>
                <td style=" border: 1px solid black;">{{ date('d/m/y H:i', strtotime($ensayo->recepcion->fecha_aceptacion)) }}</td>
                <td style=" border: 1px solid black;">{{ $ensayo->recepcion->codigo_pedido }}</td>
                <td style=" border: 1px solid black;">{{ $ensayo->recepcion->cliente->nombre }}</td>
                <td style=" border: 1px solid black;">{{ $ensayo->recepcion->cliente->nit }}</td>
                <td style=" border: 1px solid black;">{{ $ensayo->lote }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
