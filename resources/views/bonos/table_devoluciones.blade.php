<div class="table-responsive">
    <table class="table table-striped" id="anticipos-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Número <br> de Lote</th>
            @if($esCancelado)
                <th>Comprobante</th>
            @endif
            <th>Fecha</th>
            <th>Cliente <br>Productor</th>
            <th>Producto</th>
            <th>Monto <br>Individual BOB</th>
            <th>Monto Total <br>Lote BOB</th>

            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($devoluciones->currentPage() - 1) * $devoluciones->perPage();
        $row = 1;
        ?>
        @foreach($devoluciones as $devolucion)
            <tr>
                <td class="text-muted">{{ $page + ($row++) }}</td>

                @if($esCancelado)
                    <td>{{ $devolucion->lote }}</td>

                    <td><strong>{{ $devolucion->codigo}}</strong></td>
                    <td>{{ date('d/m/y', strtotime($devolucion->created_at)) }}</td>
                    <td>{!! $devolucion->nit .' | '. $devolucion->nombre  !!}<br><small class='text-muted'>{{ 'Productor: '. $devolucion->razon_social}}</small></td>
                    <td>{{ $devolucion->producto }}</td>
                    <td><strong> {{ number_format($devolucion->monto, 2) }}</strong></td>
                    <td>@if($devolucion->total_devolucion) {{ number_format($devolucion->total_devolucion, 2) }} @endif</td>
                @else
                    <td>{{ $devolucion->formularioLiquidacion->lote }}</td>
                    <td>{{ date('d/m/y', strtotime($devolucion->fecha)) }}</td>
                    <td>
                        @if($devolucion->formularioLiquidacion->cliente_id)
                            {!! $devolucion->formularioLiquidacion->cliente->infoCliente !!}
                        @endif
                    </td>
                    <td>{{ $devolucion->formularioLiquidacion->producto }}</td>
                    <td><strong> {{ number_format($devolucion->monto, 2) }}</strong></td>
                    <td>{{ number_format($devolucion->total, 2) }}</td>
                @endif
                <td style="width: 145px">
                    @if(!$esCancelado)
                        @if(\App\Patrones\Permiso::esCaja() )

                        <div class='btn-group'>
                            <a class='btn btn-info btn-md' href="#" data-target="#modalDevolucion"
                               data-txtid="{{$devolucion->id}}" data-txtmonto="{{$devolucion->monto}}"
                               data-txtcooperativa="{{$devolucion->formularioLiquidacion->cliente->cooperativa->razon_social}}"
                               data-toggle="modal">
                                <i class="glyphicon glyphicon-usd"></i> Pagar
                            </a>
                        </div>
                        @endif
                    @else
                        @if($devolucion->alta)
                            <a class='btn btn-info' href="{{ route('imprimir_bono', [$devolucion->id]) }}"  target="_blank">
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
    </table>
    {!! Form::open(['route' => 'movimientos.registrar-devolucion', 'id' => 'formularioModal']) !!}
    @include("bonos.modal_pago")
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
        $('#modalDevolucion').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var monto = button.data('txtmonto')
            var cooperativa = button.data('txtcooperativa')

            var modal = $(this)
            modal.find('.modal-body #idBono').val(id);
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
