<div class="table-responsive">
    <table class="table table-striped" id="cuentas-table">
        <thead>
        <tr>
            <th>#</th>

            <th>Fecha</th>
            <th>Cliente</th>
            <th>Productor</th>
            <th>Glosa</th>
            <th>Monto BOB</th>
            @if($esCancelado)
                <th>Pagado en</th>
            @endif
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($cuentas->currentPage() - 1) * $cuentas->perPage();
        $row = 1;
        ?>
        @foreach($cuentas as $cuenta)
            <tr>
                <td class="text-muted">{{ $page + ($row++) }}</td>
                <td>
                    {{ date('d/m/y', strtotime($cuenta->updated_at)) }}
                </td>
                @if($cuenta->origen_type=='App\Models\Cliente')
                    <td>
                        {!! $cuenta->origen->nombre !!}<br><small
                            class='text-muted'>{!! $cuenta->origen->nit !!}</small>
                    </td>
                    <td>{!! $cuenta->origen->cooperativa->razon_social !!}</td>
                @else
                    <td>
                        {!! $cuenta->origen->cliente->nombre !!}<br><small
                            class='text-muted'>{!! $cuenta->origen->cliente->nit !!}</small>
                    </td>
                    <td>{!! $cuenta->origen->cliente->cooperativa->razon_social !!}</td>
                @endif

                <td>{!! $cuenta->motivo!!}</td>
                <td>{{ number_format($cuenta->monto, 2) }}</td>
                @if($esCancelado)
                    @if($cuenta->origen_type=='App\Models\FormularioLiquidacion')
                        <td>{!! $cuenta->origen->lote !!}</td>
                    @else
                        <td>Cliente</td>
                    @endif
                @endif
                <td style="width: 145px">

                    <a class='btn btn-info' href="{{ route('cuentas-cobrar-historial', [$cuenta->id]) }}"
                       target="_blank">
                        <i class="glyphicon glyphicon-calendar"></i> Historial
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

