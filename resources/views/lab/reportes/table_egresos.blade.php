<div class="table-responsive">
    <table class="table table-striped" id="egresos-table" >
        <thead>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Glosa</th>
            <th style=" border: 1px solid black;">Método</th>
            <th style=" border: 1px solid black;">Monto BOB</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($pagos->currentPage() - 1) * $pagos->perPage();
        $row = 1;
        ?>
        @foreach($pagos as $pago)
            <tr>
                <td style=" border: 1px solid black;" class="text-muted">{{ $page + ($row++) }}</td>
                <td style=" border: 1px solid black;">{{ $pago->glosa }}</td>
                <td style=" border: 1px solid black;">{{ $pago->metodo }}</td>
                <td style=" border: 1px solid black;">{{ number_format($pago->sumatoria, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
