<div class="table-responsive">
    <table class="table table-striped" id="muestras-table" >
        <thead>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Fecha</th>
            <th style=" border: 1px solid black;">Código</th>
            <th style=" border: 1px solid black;">Descripción</th>
            <th style=" border: 1px solid black;">Cliente</th>
            <th style=" border: 1px solid black;">Nit</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($pedidos->currentPage() - 1) * $pedidos->perPage();
        $row = 1;
        ?>
        @foreach($pedidos as $pedido)
            <tr>
                <td style=" border: 1px solid black;" class="text-muted">{{ $page + ($row++) }}</td>
                <td style=" border: 1px solid black;">{{ date('d/m/y H:i', strtotime($pedido->fecha_rechazo)) }}</td>
                <td style=" border: 1px solid black;">{{ $pedido->codigo_pedido }}</td>
                <td style=" border: 1px solid black;">{{ $pedido->descripcion }}</td>
                <td style=" border: 1px solid black;">{{ $pedido->cliente->nombre }}</td>
                <td style=" border: 1px solid black;">{{ $pedido->cliente->nit }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
