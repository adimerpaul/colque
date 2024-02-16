<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="anticipos-table" name="anticipos-table">
        <thead>
        <tr>
            <th colspan="7" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>REPORTE DE ANTICIPOS NO DEVUELTOS
                <br>
                @if($fecha_inicial)<b id="fechas"></b>@endif
                <br>
            </th>
        </tr>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Comprobante</th>
            <th style=" border: 1px solid black;">Número <br> de Lote</th>
            <th style=" border: 1px solid black;">Fecha</th>
            <th style=" border: 1px solid black;">Cliente </th>
            <th style=" border: 1px solid black;">Productor</th>
            <th style=" border: 1px solid black;">Producto</th>
            <th style=" border: 1px solid black;">Monto BOB</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($anticipos->currentPage() - 1) * $anticipos->perPage();
        $row = 1;
        ?>
        @foreach($anticipos as $anticipo)

            @if(!$anticipo->formularioLiquidacion->es_retirado)
                <tr>
                    <td style=" border: 1px solid black;">{{ $page + ($row++) }}</td>

                    <td style=" border: 1px solid black;"><strong>{{ $anticipo->codigo_caja }}</strong></td>
                    <td style=" border: 1px solid black;">{{ $anticipo->formularioLiquidacion->lote }}</td>
                    <td style=" border: 1px solid black;">{{ date('d/m/y', strtotime($anticipo->created_at)) }}</td>
                    <td style=" border: 1px solid black;">{!! $anticipo->formularioLiquidacion->cliente->nit .' | '. $anticipo->formularioLiquidacion->cliente->nombre  !!}

                    </td>
                    <td style=" border: 1px solid black;">{{  $anticipo->formularioLiquidacion->cliente->cooperativa->razon_social}}
                    </td>
                    <td style=" border: 1px solid black;">{{ $anticipo->formularioLiquidacion->producto }}</td>
                    <td style=" border: 1px solid black;">{{ number_format($anticipo->monto, 2) }}</td>
                </tr>
            @endif


        @endforeach
        </tbody>
    </table>

</div>

