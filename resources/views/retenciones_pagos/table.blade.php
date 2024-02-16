<div class="table-responsive">
    <table class="table table-striped" id="retenciones-table">
        <thead>
        <tr>
            <th>#</th>
            @if($esCancelado)
                <th>Comprobante</th>
            @endif
            <th>Fecha</th>
            <th>Productor</th>
            <th>Glosa</th>
            <th>Monto BOB</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($retenciones->currentPage() - 1) * $retenciones->perPage();
        $row = 1;
        ?>
        @foreach($retenciones as $retencion)
            <tr>
                <td class="text-muted">{{ $page + ($row++) }}</td>
                @if($esCancelado)
                    <td><strong> {{ $retencion->codigo }}</strong></td>
                    <td>{{ date('d/m/y', strtotime($retencion->created_at)) }}</td>
                    <td>{!! $retencion->origen->cooperativa !!}</td>
                    <td>{!! $retencion->glosa !!}</td>
                    <td>{{ number_format($retencion->monto, 2) }}</td>
                @else
                    <td>{{ date('d/m/y', strtotime($retencion->created_at)) }}</td>
                    <td>{!! $retencion->cooperativa !!}</td>
                    <td>{!! $retencion->motivo !!}</td>
                    <td>{{ number_format($retencion->monto, 2) }}</td>
                @endif

                <td style="width: 145px">
                    <div class='btn-group'>
                        @if(!$esCancelado)
                            @if($retencion->alta)
                                @if(\App\Patrones\Permiso::esCaja())
                                    <a class='btn btn-info btn-md' href="#" data-target="#modalPago"
                                       data-txtid="{{$retencion->id}}" data-txtmonto="{{$retencion->monto}}"
                                       data-txtcooperativa="{{$retencion->cooperativa}}" data-toggle="modal">
                                        <i class="glyphicon glyphicon-usd"></i> Pagar
                                    </a>
                                @endif
                            @else
                                <span class='label label-danger'>Anulado</span>
                            @endif


                            <a class='btn btn-primary btn-md'
                               href="{{ route('retenciones-incluidas', [$esCancelado? $retencion->origen_id : $retencion->id]) }}"
                               target="_blank">
                                <i class="glyphicon glyphicon-list"></i> Retenciones
                            </a>

                        @elseif($esCancelado)
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                Acciones <i style="margin-top: 3px" class="fa fa-angle-down pull-right"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class='dropdown-item'
                                   href="{{ route('retencion-pago.reporte', [$retencion->origen_id]) }}"
                                   target="_blank">
                                    <i class="fa fa-print"></i>
                                    Recibo
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class='dropdown-item'
                                   href="{{ route('retenciones-detalle-pdf', [$retencion->origen_id]) }}"
                                   target="_blank">
                                    <i class="fa fa-file-text"></i>
                                    Detalle
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class='dropdown-item'
                                   href="{{ route('retenciones-incluidas', [$retencion->origen_id]) }}"
                                   target="_blank">
                                    <i class="fa fa-list"></i>
                                    Retenciones
                                </a>
                            </div>

                        @endif

                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! Form::open(['route' => 'registrar-pago-retencion', 'id' => 'formularioModal']) !!}
    @include("retenciones_pagos.modal_pago")
    {!! Form::close() !!}
</div>
@push('scripts')

    <script>
        $("#formularioModal").on("submit", function () {
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
            var cooperativa = button.data('txtcooperativa')

            var modal = $(this)
            modal.find('.modal-body #idRetencion').val(id);
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
