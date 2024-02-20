<div class="table-responsive">
    <table class="table table-striped" id="ensayos-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Código</th>
            <th>F. Recepción</th>
            <th>F. Finalización</th>
            <th>Lote</th>
            <th>Cliente</th>
            <th>Elementos</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($ensayos->currentPage() - 1) * $ensayos->perPage();
        $row = 1;
        ?>
        @foreach($ensayos as $ensayo)
            <tr>
                <td class="text-muted">{{ $page + ($row++) }}</td>
                <td>{{$ensayo->codigo_lab }}</td>
                <td>{{$ensayo->fecha_recepcion }}</td>
                <td>{{ date('d/m/y H:i', strtotime($ensayo->fecha_finalizacion)) }}</td>
                <td>{{$ensayo->lote }}</td>
                <td>{{$ensayo->cliente }}</td>
                <td>{{$ensayo->elementos }}</td>
                <td>
                    <a class='btn btn-info' href="{{ route('informe-ensayo', [$ensayo->id]) }}"
                       target="_blank">
                        <i class="glyphicon glyphicon-print"></i> Reporte
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
