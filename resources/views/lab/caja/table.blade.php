<div class="table-responsive">
    <table class="table table-striped" >
        <thead>
        <tr>
            <th>#</th>
            @if($esCancelado)
                <th>Comprobante</th>
            @endif
            <th>Fecha</th>
            <th>Glosa</th>
            <th>Cliente</th>
            <th>Código</th>
            <th>Monto BOB</th>

            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($pagos->currentPage() - 1) * $pagos->perPage();
        $row = 1;
        ?>
        @foreach($pagos as $pago)
            <tr>
                <td class="text-muted">{{ $page + ($row++) }}</td>
                @if($esCancelado)
                    <td><strong>{{ $pago->codigo }}</strong></td>
                @endif
                <td>{{ date('d/m/y', strtotime($pago->updated_at)) }}</td>
                <td>{!! $pago->glosa !!}</td>
                <td>{!! $pago->cliente_info !!}</td>
                <td>{{ $pago->codigo_recepcion }}</td>
                <td>{{ number_format($pago->monto, 2) }}</td>
                <td style="width: 145px">
                    @if($esCancelado)
                        <a class='btn btn-info' href="{{ route('imprimir-comprobante-lab', [$pago->id]) }}"
                           target="_blank">
                            <i class="glyphicon glyphicon-print"></i> Recibo
                        </a>
                        <a class='btn btn-danger ' href="#" data-target="#modalAnulacion"
                           data-txtid="{{$pago->id}}" data-txtmonto="{{$pago->monto}}"
                           data-txtcliente="{{$pago->cliente}}" data-txtcomprobante="{{$pago->codigo}}"
                           data-toggle="modal">
                            <i class="glyphicon glyphicon-remove"></i> Anular pago
                        </a>
                    @else
                        <div class='btn-group'>
                            @if($pago->alta)
                                <a class='btn btn-info btn-md' href="#" data-target="#modalPago"
                                   data-txtid="{{$pago->id}}" data-txtmonto="{{$pago->monto}}"
                                   data-txtglosa="{{$pago->glosa}}"
                                   data-txtnombre="{{$pago->origen->cliente->nombre}}"
                                   data-txtnit="{{$pago->origen->cliente->nit}}"
                                   data-toggle="modal">
                                    <i class="glyphicon glyphicon-usd"></i> Cobrar
                                </a>
                            @else
                                <span class='label label-danger'>Anulado</span>
                            @endif
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! Form::open(['route' => 'registrar-pago-lab', 'id' => 'formularioModal']) !!}
    @include("lab.caja.modal_pago")
    {!! Form::close() !!}

    {!! Form::open(['route' => 'anular-pago-lab', 'id' => 'formularioModalAnular']) !!}
    @include("lab.caja.modal_anulacion")
    {!! Form::close() !!}
</div>
@push('scripts')

    <script>
        $("#formularioModal").on("submit", function () {
            $("#botonGuardar").prop("disabled", true);
        });
        $("#formularioModalAnular").on("submit", function () {
            $("#botonGuardarAnular").prop("disabled", true);
        });
        $(document).ready(function () {
            $("#bancoDiv").hide();
            $("#nroRecibo").hide();
            document.getElementById('numero_recibo').removeAttribute('required', '');
        });
        $('#modalAnulacion').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var monto = button.data('txtmonto')
            var cliente = button.data('txtcliente')
            var comprobante = button.data('txtcomprobante')

            var modal = $(this)
            modal.find('.modal-body #idPago').val(id);
            modal.find('.modal-body #monto').val(monto);
            modal.find('.modal-body #cliente').val(cliente);
            modal.find('.modal-body #comprobante').val(comprobante);
        })

        $('#modalPago').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var monto = button.data('txtmonto')
            var glosa = button.data('txtglosa')
            var nombre = button.data('txtnombre')
            var nit = button.data('txtnit')

            var modal = $(this)
            modal.find('.modal-body #idPago').val(id);
            modal.find('.modal-body #monto').val(monto);
            modal.find('.modal-body #glosa').val(glosa);
            modal.find('.modal-body #nombre').val(nombre);
            modal.find('.modal-body #nit').val(nit);

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
