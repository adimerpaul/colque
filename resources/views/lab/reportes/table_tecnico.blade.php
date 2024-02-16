<div class="table-responsive">
    <table class="table table-striped" id="muestras-table">
        <thead>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Fecha</th>
            <th style=" border: 1px solid black;">IDE</th>
            <th style=" border: 1px solid black;">Lote</th>
            @if($elemento_id==1)
                <th style=" border: 1px solid black;">Factor</th>
                <th style=" border: 1px solid black;">Peso de muestra</th>
                <th style=" border: 1px solid black;">Volumen gastado</th>
            @elseif($elemento_id==2)
                <th style=" border: 1px solid black;">Peso Húmedo</th>
                <th style=" border: 1px solid black;">Peso Seco</th>
                <th style=" border: 1px solid black;">Peso Tara</th>
            @endif
            <th style=" border: 1px solid black;">Ley reportada</th>
            <th style=" border: 1px solid black;">Usuario</th>
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
                <td style=" border: 1px solid black;">{{ date('d/m/y H:i', strtotime($ensayo->fecha_finalizacion)) }}</td>
                <td style=" border: 1px solid black;">{{ $ensayo->recepcion->codigo }}</td>
                <td style=" border: 1px solid black;">{{ $ensayo->lote }}</td>
                @if($elemento_id==1)

                    <td style=" border: 1px solid black;">{{ $ensayo->factor_volumetrico }}</td>
                    <td style=" border: 1px solid black;">{{ $ensayo->peso_muestra }}</td>
                    <td style=" border: 1px solid black;">{{ $ensayo->mililitros_gastados }}</td>
                @elseif($elemento_id==2)
                    <td style=" border: 1px solid black;">{{ $ensayo->peso_humedo }}</td>
                    <td style=" border: 1px solid black;">{{ $ensayo->peso_seco }}</td>
                    <td style=" border: 1px solid black;">{{ $ensayo->peso_tara }}</td>
                @endif
                <td style=" border: 1px solid black;">{{ round( $ensayo->resultado, 2) }}%</td>
                <td style=" border: 1px solid black;"></td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
