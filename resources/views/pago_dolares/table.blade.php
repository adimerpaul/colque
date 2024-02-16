<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="anticipos-table" name="anticipos-table">
        <thead>
        <tr>
            <th colspan="9" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>REPORTE DE PAGOS/COBROS CUENTA EN DOLARES
                <br>
                @if($fechaInicial)<b id="fechas"></b>@endif
                <br>
            </th>
        </tr>
        <tr>
            <th rowspan="2" style=" border: 1px solid black;">#</th>
            <th rowspan="2" style=" border: 1px solid black;">Comprobante</th>
            <th rowspan="2" style=" border: 1px solid black;">Fecha</th>
            <th rowspan="2" style=" border: 1px solid black;">Proveedor</th>
            <th rowspan="2" style=" border: 1px solid black;">Glosa</th>
            <th colspan="3" style=" border: 1px solid black; text-align: center">Cuenta BNB</th>
            <th rowspan="2" style=" border: 1px solid black;"></th>
        </tr>
        <tr>

            <th style=" border: 1px solid black;">Ingreso $us</th>
            <th style=" border: 1px solid black;">Egreso $us</th>
            <th style=" border: 1px solid black;">Saldo $us</th>


        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($pagos->currentPage() - 1) * $pagos->perPage();
        $row = 1;
        ?>
        @foreach($pagos as $pago)
            <tr>
                <td style=" border: 1px solid black;">{{ $page + ($row++) }}</td>

                <td style=" border: 1px solid black;"><strong>{{ $pago->codigo }}</strong></td>
                <td style=" border: 1px solid black;">{{ date('d/m/y', strtotime($pago->created_at)) }}</td>
                <td style=" border: 1px solid black;">
                    {!! $pago->cliente  !!}

                </td>

                <td style=" border: 1px solid black;">{{ $pago->glosa }}</td>
                <td style=" border: 1px solid black;">@if($pago->tipo=='Ingreso' and $pago->monto){{number_format($pago->monto, 2)}}@endif</td>
                <td style=" border: 1px solid black;">@if($pago->tipo=='Egreso' and $pago->monto) {{number_format($pago->monto, 2)}}@endif</td>
                <td style=" border: 1px solid black;">{{ number_format($pago->saldo_pago, 2) }}</td>

                <td style="width: 145px; border: 1px solid black;">


                    <a class='btn btn-info' href="{{ route('pagos-dolares.recibo', [$pago->id]) }}"
                       target="_blank">
                        <i class="glyphicon glyphicon-print"></i> Recibo
                    </a>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

