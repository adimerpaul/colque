<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="anticipos-table" name="anticipos-table">
        <thead>
        <tr>
            @if($esCancelado)
                <th colspan="10" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                    <br>REPORTE DE PAGOS DE ANTICIPOS
                    <br>
                    @if($fechaInicial)<b id="fechas"></b>@endif
                    <br>
                </th>
            @endif
        </tr>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            @if($esCancelado)
                <th style=" border: 1px solid black;">Comprobante</th>
            @endif
            <th style=" border: 1px solid black;">Número <br> de Lote</th>
            <th style=" border: 1px solid black;">Fecha</th>
            <th style=" border: 1px solid black;">Cliente <br>Productor</th>
            <th style=" border: 1px solid black;">Producto</th>
            @if(!$esCancelado)
                <th style=" border: 1px solid black;">Monto BOB</th>
            @else
                <th style=" border: 1px solid black;">Caja BOB</th>
                <th style=" border: 1px solid black;">BNB BOB</th>
                <th style=" border: 1px solid black;">Banco Económico BOB</th>
            @endif
            <th style=" border: 1px solid black;"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($anticipos->currentPage() - 1) * $anticipos->perPage();
        $row = 1;
        ?>
        @foreach($anticipos as $anticipo)
            <tr>
                <td style=" border: 1px solid black;">{{ $page + ($row++) }}</td>

                @if($esCancelado)
                    <td style=" border: 1px solid black;"><strong>{{ $anticipo->codigo }}</strong></td>
                    <td style=" border: 1px solid black;">{{ $anticipo->lote }}</td>
                    <td style=" border: 1px solid black;">{{ date('d/m/y', strtotime($anticipo->created_at)) }}</td>
                    <td style=" border: 1px solid black;">{!! $anticipo->nit .' | '. $anticipo->nombre  !!}<br><small
                            class='text-muted'>{{ 'Productor: '. $anticipo->razon_social}}</small></td>
                    <td style=" border: 1px solid black;">{{ $anticipo->producto }}</td>
                    <td style=" border: 1px solid black;">{{ $anticipo->metodo==\App\Patrones\TipoPago::Efectivo? number_format($anticipo->monto, 2):''}}</td>
                    <td style=" border: 1px solid black;">{{ $anticipo->banco== 'BNB'? number_format($anticipo->monto, 2):''}}</td>
                    <td style=" border: 1px solid black;">{{ $anticipo->banco== 'Economico'? number_format($anticipo->monto, 2):''}}</td>
                @else
                    <td style=" border: 1px solid black;">{{ $anticipo->formularioLiquidacion->lote }}</td>
                    <td style=" border: 1px solid black;">{{ date('d/m/y', strtotime($anticipo->fecha)) }}</td>
                    <td style=" border: 1px solid black;">
                        @if($anticipo->formularioLiquidacion->cliente_id)
                            {!! $anticipo->formularioLiquidacion->cliente->infoCliente !!}
                        @endif
                    </td>
                    <td style=" border: 1px solid black;">{{ $anticipo->formularioLiquidacion->producto }}</td>
                    <td style=" border: 1px solid black;">{{ number_format($anticipo->monto, 2) }}</td>
                @endif

                <td style="width: 145px; border: 1px solid black;">

                    @if(!$esCancelado)
                        @if(\App\Patrones\Permiso::esCaja() )
                            <div class='btn-group'>
                                <a class='btn btn-info btn-md' href="#" data-target="#modalAnticipo"
                                   data-txtid="{{$anticipo->id}}" data-txtmonto="{{$anticipo->monto}}"
                                   data-txtcooperativa="{{$anticipo->formularioLiquidacion->cliente->cooperativa->razon_social}}"
                                   data-toggle="modal">
                                    <i class="glyphicon glyphicon-usd"></i> Pagar
                                </a>
                            </div>
                        @endif
                    @else
                        @if($anticipo->alta)
                            <a class='btn btn-info' href="{{ route('imprimir_anticipo', [$anticipo->origen_id]) }}"
                               target="_blank">
                                <i class="glyphicon glyphicon-print"></i> Recibo
                            </a>
                        @else
                            <span class='label label-danger'>Anulado</span>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
        @if($esCancelado)
            <tfoot>

            <tr>
                <td colspan="6" style=" border: 1px solid black;"><strong>TOTALES</strong></td>
                <td style=" border: 1px solid black;"><strong> {{number_format($anticipos->where('metodo', \App\Patrones\TipoPago::Efectivo)->sum('monto'),2)}}</strong></td>
                <td style=" border: 1px solid black;"><strong> {{number_format($anticipos->where('banco', \App\Patrones\Banco::BNB)->sum('monto'),2)}}</strong></td>
                <td style=" border: 1px solid black;"><strong> {{number_format($anticipos->where('banco', \App\Patrones\Banco::Economico)->sum('monto'),2)}}</strong></td>
            </tr>
            </tfoot>

        @endif
    </table>
    {!! Form::open(['route' => 'movimientos.registrar-anticipo', 'id' => 'formularioModal']) !!}
    @include("anticipos.modal_pago")
    {!! Form::close() !!}
</div>
@push('scripts')

    <script>
        $("#formularioModal").on("submit", function() {
            $("#botonGuardar").prop("disabled", true);
        });
        $(document).ready(function () {
            $("#bancoDiv").hide();
            $("#nroRecibo").hide();
            document.getElementById('numero_recibo').removeAttribute('required', '');
        });
        $('#modalAnticipo').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var monto = button.data('txtmonto')
            var cooperativa = button.data('txtcooperativa')

            var modal = $(this)
            modal.find('.modal-body #idAnticipo').val(id);
            modal.find('.modal-body #monto').val(monto);
            modal.find('.modal-body #cooperativa').val(cooperativa);

        })

        function cambiarMetodo() {
            const input = document.getElementById('numero_recibo');
            if (document.getElementById("metodo").value == 'Cuenta Bancaria') {
                $("#bancoDiv").show();
                $("#nroRecibo").show();
                input.setAttribute('required', '');
            } else {
                $("#bancoDiv").hide();
                $("#nroRecibo").hide();
                input.removeAttribute('required', '');
            }
        }
    </script>

@endpush
