<h3>REPORTE HISTÓRICO</h3>
<div class="table-responsive">
    <table class="table table-striped"style=" border: 1px solid black;" >
        <thead>
        <tr>
            <th style=" border: 1px solid black; text-align: center">#</th>
            <th style=" border: 1px solid black; text-align: center">Mes</th>
            <th style=" border: 1px solid black; text-align: center">Peso Neto Seco (Kg)</th>
            <th style=" border: 1px solid black; text-align: center">Valor Neto Venta (BOB)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reporteHistoricoVenta as $reporte)
            <tr>
                <td style=" border: 1px solid black;">{{$loop->iteration}}</td>
                <td style=" border: 1px solid black;">{{ $reporte->fecha }}</td>
                <td style=" border: 1px solid black;">{{ number_format($reporte->peso, 2) }}</td>
                <td style=" border: 1px solid black;">{{ number_format($reporte->neto_venta, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<h3>REPORTE HISTÓRICO POR PRODUCTO</h3>
<div class="table-responsive">
    <table class="table table-striped"style=" border: 1px solid black;" id="historicoProducto">
        <thead>
        <tr>
            <th style=" border: 1px solid black; text-align: center">#</th>
            <th style=" border: 1px solid black; text-align: center">Mes</th>
            <th style=" border: 1px solid black; text-align: center">Producto</th>
            <th style=" border: 1px solid black; text-align: center">Peso Neto Seco (Kg)</th>
            <th style=" border: 1px solid black; text-align: center">Valor Neto Venta (BOB)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reporteHistoricoProductoVenta as $reporte)
            <tr>
                <td style=" border: 1px solid black;">{{$loop->iteration}}</td>
                <td style=" border: 1px solid black;">{{ $reporte->fecha }}</td>
                <td style=" border: 1px solid black;">{{ $reporte->letra }}</td>
                <td style=" border: 1px solid black;">{{ number_format($reporte->peso, 2) }}</td>
                <td style=" border: 1px solid black;">{{ number_format($reporte->neto_venta, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
