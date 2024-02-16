<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="movimientos-tabla"
           name="movimientos-tabla">
        <thead>
        <tr>
            <th colspan="15" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>REPORTE DE CAJA
                <br>
                @if($fechaInicial)<b id="fechas"></b>@endif
                <br><br>
            </th>

        </tr>
        <tr>
            <th rowspan="2" style=" border: 1px solid black;">#</th>
            <th rowspan="2" style=" border: 1px solid black;">Fecha</th>
            <th rowspan="2" style=" border: 1px solid black;">Proveedor</th>
            <th rowspan="2" style=" border: 1px solid black;">Glosa</th>
            <th rowspan="2" style=" border: 1px solid black;">Comprobante</th>
            <th rowspan="2" style=" border: 1px solid black;">Empresa</th>
            <th rowspan="2" style=" border: 1px solid black;">Factura</th>
            @if($tipo!='Cuenta Bancaria')
                <th colspan="3" style=" border: 1px solid black; text-align: center">Caja</th>
            @endif
            @if($tipo!='Efectivo')
                <th colspan="3" style=" border: 1px solid black; text-align: center">Cuenta BNB</th>
                <th colspan="3" style=" border: 1px solid black; text-align: center">Cuenta Economico</th>
            @endif

        </tr>
        <tr>
            @if($tipo!='Cuenta Bancaria')
                <th style=" border: 1px solid black;">Ingreso BOB</th>
                <th style=" border: 1px solid black;">Egreso BOB</th>
                <th style=" border: 1px solid black;">Saldo BOB</th>
            @endif
            @if($tipo!='Efectivo')

                <th style=" border: 1px solid black;">Ingreso BOB</th>
                <th style=" border: 1px solid black;">Egreso BOB</th>
                <th style=" border: 1px solid black;">Saldo BOB</th>
                <th style=" border: 1px solid black;">Ingreso BOB</th>
                <th style=" border: 1px solid black;">Egreso BOB</th>
                <th style=" border: 1px solid black;">Saldo BOB</th>
            @endif

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
                <td style=" border: 1px solid black;">{{ date('d/m/y H:i', strtotime($pago->created_at)) }}</td>
                <td style=" border: 1px solid black;">{{ $pago->cliente }}</td>
                @if($pago->origen_type==\App\Models\Movimiento::class)
                    @if($pago->origen->origen_type==\App\Models\Venta::class)
                        <td style=" border: 1px solid black; background-color: #BBDEFB"> {{$pago->glosa}}</td>
                    @else
                        <td style=" border: 1px solid black;"> {{$pago->glosa}}</td>

                    @endif
                @else
                    <td style=" border: 1px solid black;"> {{$pago->glosa}}</td>
                @endif
                <td style=" border: 1px solid black;">{{ $pago->codigo }}</td>
                <td style=" border: 1px solid black;">{{ $pago->empresa }}</td>
                <td style=" border: 1px solid black;">{{ $pago->factura }}</td>
                @if($tipo!='Cuenta Bancaria')

                    <td style=" border: 1px solid black;">@if($pago->origen->tipo=='Ingreso' and $pago->monto_caja) {{number_format($pago->monto_caja, 2)}}@endif</td>
                    <td style=" border: 1px solid black;">@if($pago->origen->tipo=='Egreso' and $pago->monto_caja) {{number_format($pago->monto_caja, 2)}}@endif</td>

                    <td style=" border: 1px solid black; background-color: #ECEFF1">{{ number_format($pago->saldo_caja, 2)}}</td>
                @endif
                @if($tipo!='Efectivo')
                        <td style=" border: 1px solid black;">@if($pago->origen->tipo=='Ingreso' and $pago->monto_bnb){{number_format($pago->monto_bnb, 2)}}@endif</td>
                        <td style=" border: 1px solid black;">@if($pago->origen->tipo=='Egreso' and $pago->monto_bnb) {{number_format($pago->monto_bnb, 2)}}@endif</td>
                        <td style=" border: 1px solid black; background-color: #ECEFF1">{{ number_format($pago->saldo_bnb, 2)}}</td>

                        <td style=" border: 1px solid black;">@if($pago->origen->tipo=='Ingreso' and $pago->monto_economico) {{number_format($pago->monto_economico, 2)}}@endif</td>
                        <td style=" border: 1px solid black;">@if($pago->origen->tipo=='Egreso' and $pago->monto_economico) {{number_format($pago->monto_economico, 2)}}@endif</td>
                        <td style=" border: 1px solid black; background-color: #ECEFF1">{{ number_format($pago->saldo_economico, 2)}}</td>
                @endif

            </tr>
        @endforeach
        <td colspan="16" class="text-center" style=" border: 1px solid black;">
            <b style="text-align: center">
                TOTALES<br>
                SALDO INICIAL CAJA = {{number_format($inicialCaja, 2)}},
                &nbsp&nbsp&nbsp SALDO INICIAL CUENTA BNB = {{number_format($inicialBnb, 2)}}<br>
                &nbsp&nbsp&nbsp SALDO INICIAL CUENTA ECONOMICO = {{number_format($inicialEconomico, 2)}}<br>
                INGRESOS CAJA = {{number_format($totalIngresosCaja, 2)}},
                &nbsp&nbsp&nbsp EGRESOS CAJA = {{number_format($totalEgresosCaja, 2)}},
                &nbsp&nbsp&nbsp SALDO CAJA = {{number_format($totalTodoCaja, 2)}}<br>
                INGRESOS CUENTA BNB = {{number_format($totalIngresosBnb, 2)}},
                &nbsp&nbsp&nbsp EGRESOS CUENTA BNB = {{number_format($totalEgresosBnb, 2)}},
                &nbsp&nbsp&nbsp SALDO CUENTA BNB = {{number_format($totalTodoBnb, 2)}} <br>
                INGRESOS CUENTA ECONOMICO = {{number_format($totalIngresosEconomico, 2)}},
                &nbsp&nbsp&nbsp EGRESOS CUENTA ECONOMICO = {{number_format($totalEgresosEconomico, 2)}},
                &nbsp&nbsp&nbsp SALDO CUENTA ECONOMICO = {{number_format($totalTodoEconomico, 2)}} <br>
                SALDO ACTUAL CAJA = {{number_format($saldoCaja, 2)}}
                &nbsp&nbsp&nbsp SALDO ACTUAL CUENTA BNB = {{number_format($saldoBnb, 2)}}
                &nbsp&nbsp&nbsp SALDO ACTUAL CUENTA ECONOMICO = {{number_format($saldoEconomico, 2)}}
            </b>
        </td>
        </tbody>
    </table>
</div>
<script>
    document.getElementById("fechas").innerHTML = "CORRESPONDIENTE A LAS FECHAS: {{ date('d/m/y', strtotime($fechaInicial)) }} AL {{ date('d/m/y', strtotime($fechaFinal)) }}";
</script>
