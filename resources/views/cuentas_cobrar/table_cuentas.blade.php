<div class="table-responsive">
    <table class="table table-striped" id="cuentas-table">
        <thead>
        <tr>
            <th>#</th>
            @if($esCancelado)
                <th>Comprobante</th>
            @endif
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Productor</th>
            <th>Glosa</th>
            <th>Monto BOB</th>
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
                @if($esCancelado)
                    <td><strong>{{ $cuenta->codigo }}</strong></td>
                @endif
                <td>
                    @if($esCancelado)
                        {{ date('d/m/y', strtotime($cuenta->created_at)) }}
                    @else
                        {{ date('d/m/y', strtotime($cuenta->updated_at)) }}
                    @endif
                </td>
                <td>{!! $cuenta->nombre !!}<br><small class='text-muted'>{!! $cuenta->nit !!}</small></td>
                <td>{!! $cuenta->razon_social !!}</td>
                <td>{!! $cuenta->motivo !!}</td>
                <td>{{ number_format($cuenta->monto, 2) }}</td>
                <td style="width: 145px">

                    @if(!$esCancelado)
                        @if(\App\Patrones\Permiso::esCaja() )

                            <div class='btn-group'>
                                <a class='btn btn-info btn-md' href="#" data-target="#modalCuenta"
                                   data-txtid="{{$cuenta->id}}" data-txtmonto="{{$cuenta->monto}}"
                                   data-txtcliente="{{$cuenta->nombre}}"
                                   data-toggle="modal">
                                    <i class="glyphicon glyphicon-usd"></i> Pagar
                                </a>
                            </div>
                        @endif
                    @else
                        @if($cuenta->alta)
                            <a class='btn btn-info' href="{{ route('cuentas.reporte', [$cuenta->id]) }}" target="_blank">
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
    {!! Form::open(['route' => 'registrar-pago-cuenta', 'id' => 'formularioModal']) !!}
    @include("cuentas_cobrar.modal_pago")
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
        $('#modalCuenta').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var monto = button.data('txtmonto')
            var cliente = button.data('txtcliente')

            var modal = $(this)
            modal.find('.modal-body #idCuenta').val(id);
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
