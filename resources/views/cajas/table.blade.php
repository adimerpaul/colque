<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="formularioLiquidacions-table">
        <thead>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Número <br> de Lote</th>
            @if($esCancelado)
                <th style=" border: 1px solid black;">Comprobante</th>
                <th style=" border: 1px solid black;">Fecha de <br> Liquidación</th>
                <th style=" border: 1px solid black;">Fecha de <br> Cancelación</th>
            @else
                <th style=" border: 1px solid black;">Fecha de <br> Liquidación</th>
            @endif
            <th style=" border: 1px solid black;">Cliente <br>Productor</th>
            <th style=" border: 1px solid black;">Producto</th>
            <th style=" border: 1px solid black;">Monto BOB</th>
            <th style=" border: 1px solid black;"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($formularioLiquidacions->currentPage() - 1) * $formularioLiquidacions->perPage();
        $row = 1;
        ?>
        @foreach($formularioLiquidacions as $formularioLiquidacion)
            <tr>
                <td style=" border: 1px solid black;" class="text-muted">{{ $page + ($row++) }}</td>
                <td style=" border: 1px solid black;">
                    <strong>{{ $formularioLiquidacion->lote }}</strong>
                </td>
                @if($esCancelado)
                    <td style=" border: 1px solid black;"><strong>{{ $formularioLiquidacion->codigo_caja }}</strong></td>
                    <td style=" border: 1px solid black;">{{ date('d/m/y', strtotime($formularioLiquidacion->fecha_liquidacion)) }}</td>
                    <td style=" border: 1px solid black;">{{ date('d/m/y', strtotime($formularioLiquidacion->fecha_cancelacion)) }}</td>
                @else

                    <td style=" border: 1px solid black;">
                        @if($formularioLiquidacion->fecha_liquidacion)
                            {{ date('d/m/y', strtotime($formularioLiquidacion->fecha_liquidacion)) }}
                        @endif
                    </td>
                @endif

                <td style=" border: 1px solid black;">
                    @if($formularioLiquidacion->cliente_id)
                        {!! $formularioLiquidacion->cliente->infoCliente !!}
                    @endif
                </td>
                <td style=" border: 1px solid black;">{{ $formularioLiquidacion->producto }}</td>
                <td style=" border: 1px solid black;">{{ number_format($formularioLiquidacion->saldo_favor, 2) }}</td>
                <td style=" border: 1px solid black; width: 145px">
                    @if(!$esCancelado)
                        @if(\App\Patrones\Permiso::esCaja() )
                            <div class='btn-group'>
                                <a class='btn btn-info btn-md' href="#" data-target="#modalPago"
                                   data-txtid="{{$formularioLiquidacion->id}}"
                                   data-txtmonto="{{$formularioLiquidacion->saldo_favor}}"
                                   data-txtcliente="{{$formularioLiquidacion->cliente->nombre}}" data-toggle="modal">
                                    <i class="glyphicon glyphicon-usd"></i> Pagar
                                </a>
                            </div>
                        @endif
                    @else
                        <a class='btn btn-info' href="{{ route('imprimirFormulario', [$formularioLiquidacion->id]) }}"
                           target="_blank">
                            <i class="glyphicon glyphicon-print"></i> Recibo
                        </a>
                    @endif
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
    {!! Form::open(['route' => 'cajas.store', 'id' => 'formularioModal']) !!}
    @include("cajas.modal_pago")
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
        $('#modalPago').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var monto = button.data('txtmonto')
            var cliente = button.data('txtcliente')

            var modal = $(this)
            modal.find('.modal-body #idFormulario').val(id);
            modal.find('.modal-body #monto').val(monto);
            modal.find('.modal-body #cliente').val(cliente);

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
