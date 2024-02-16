<div class="table-responsive">
    <table class="table table-striped" id="ventas-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Número <br> de Lote</th>
            @if($esCancelado)
                <th>Comprobante</th>
            @endif
            <th>Fecha</th>
            <th>Cliente <br>Comprador</th>
            <th>Producto</th>
{{--            <th>Monto <br>Individual BOB</th>--}}
            <th>Monto Total BOB</th>

        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($ventas->currentPage() - 1) * $ventas->perPage();
        $row = 1;
        ?>
        @foreach($ventas as $venta)
            <tr>
                <td class="text-muted">{{ $page + ($row++) }}</td>

                @if($esCancelado)
                    <td>{{ $venta->lote }}</td>

                    <td><strong>{{ $venta->codigo}}</strong></td>
                    <td>{{ date('d/m/y', strtotime($venta->created_at)) }}</td>
                    <td>{!! $venta->nit .' | '. $venta->razon_social  !!}<br><small class='text-muted'></small></td>
                    <td>{{ $venta->producto }}</td>
                    <td><strong>
                            {{ number_format($venta->monto, 2) }}
                        </strong></td>
{{--                    <td>@if($venta->total_devolucion) {{ number_format($venta->total_devolucion, 2) }} @endif</td>--}}
                @else
                    <td>{{ $venta->lote }}</td>
                    <td>{{ date('d/m/y', strtotime($venta->fecha_venta)) }}</td>
                    <td>
                        @if($venta->comprador_id)
                            {!! $venta->comprador->info !!}
                        @endif
                    </td>
                    <td>{{ $venta->producto }}</td>
                    <td> {{ number_format($venta->monto_final, 2) }}</td>
                @endif
                <td style="width: 145px">
                    @if(!$esCancelado)
                        @if(\App\Patrones\Permiso::esCaja() )
                            <div class='btn-group'>
                                <a class='btn btn-info btn-md' href="#" data-target="#modalVenta"
                                   data-txtid="{{$venta->id}}" data-txtmonto="{{$venta->monto_final}}"
                                   data-txtcomprador="{{$venta->comprador->razon_social}}"
                                   data-toggle="modal">
                                    <i class="glyphicon glyphicon-usd"></i> Pagar
                                </a>
                            </div>
                        @endif
                    @else
                        @if($venta->alta)
                            <a class='btn btn-info' href="{{ route('ventas.recibo', [$venta->id]) }}"  target="_blank">
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
    {!! Form::open(['route' => 'ventas.registrar-pago', 'id' => 'formularioModal']) !!}
    @include("ventas.modal_pago")
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
            $("#tipoCambio").hide();
            document.getElementById('numero_recibo').removeAttribute('required', '');
            document.getElementById('tipo_cambio').removeAttribute('required', '');
        });
        $('#modalVenta').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var monto = button.data('txtmonto')
            var comprador = button.data('txtcomprador')

            var modal = $(this)
            modal.find('.modal-body #idVenta').val(id);
            modal.find('.modal-body #monto').val(monto);
            modal.find('.modal-body #comprador').val(comprador);

        })

        function cambiarMetodo() {
            const input = document.getElementById('numero_recibo');
            const cambio = document.getElementById('tipo_cambio');
            if (document.getElementById("metodo").value == 'Cuenta Bancaria') {
                $("#bancoDiv").show();
                $("#nroRecibo").show();
                $("#tipoCambio").hide();
                input.setAttribute('required', '');
                cambio.removeAttribute('required', '');
            }
            else if (document.getElementById("metodo").value == 'Cuenta Bancaria Dolares') {
                $("#bancoDiv").hide();
                $("#nroRecibo").show();
                $("#tipoCambio").show();
                input.setAttribute('required', '');
                cambio.setAttribute('required', '');
            }
            else {
                $("#bancoDiv").hide();
                $("#nroRecibo").hide();
                $("#tipoCambio").hide();
                input.removeAttribute('required', '');
                cambio.removeAttribute('required', '');
            }
        }
    </script>
@endpush
