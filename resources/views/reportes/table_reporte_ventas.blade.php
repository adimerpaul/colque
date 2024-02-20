<h3>REPORTE DETALLES DE VENTAS</h3>
<div class="table-responsive">
    <table class="table table-striped"style=" border: 1px solid black;" >
        <thead>
        <tr>
            <th style=" border: 1px solid black; text-align: center">#</th>
            <th style=" border: 1px solid black; text-align: center">Lote</th>
            <th style=" border: 1px solid black; text-align: center">Valor Neto Venta</th>
            <th style=" border: 1px solid black; text-align: center">Utilidad</th>
            <th style=" border: 1px solid black; text-align: center">Margen</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reporteDetalleVenta as $reporte)
            <tr>
                <td style=" border: 1px solid black;">{{$loop->iteration}}</td>
                <td style=" border: 1px solid black;">{{ $reporte->lote }}</td>
                <td style=" border: 1px solid black;">{{ number_format($reporte->valor_neto_venta , 2)}}</td>
                <td style=" border: 1px solid black;">{{ ($reporte->utilidad)}}</td>
                <td style=" border: 1px solid black;">{{ number_format($reporte->margen, 2)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
