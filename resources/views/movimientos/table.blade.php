<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="movimientos-table">
        <thead>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            @if($esCancelado)
                <th style=" border: 1px solid black;">Comprobante</th>
                <th style=" border: 1px solid black;">Factura</th>
                <th style=" border: 1px solid black;">Fecha <br> de Pago</th>
            @else
                <th style=" border: 1px solid black;">Fecha <br> de Solicitud</th>
            @endif
            <th style=" border: 1px solid black;">Proveedor</th>
            <th style=" border: 1px solid black;">Glosa</th>
            <th style=" border: 1px solid black;">Tipo</th>
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
        $page = ($pagos->currentPage() - 1) * $pagos->perPage();
        $row = 1;
        ?>
        @foreach($pagos as $pago)
            <tr>
                <td style=" border: 1px solid black;" class="text-muted">{{ $page + ($row++) }}</td>
                @if($pago->es_cancelado)
                    <td style=" border: 1px solid black;"><strong>{{ $pago->codigo }}</strong></td>
                    <td style=" border: 1px solid black;"> {{$pago->factura}}</td>

                    <td style=" border: 1px solid black;">{{ date('d/m/y H:i', strtotime($pago->updated_at)) }}</td>
                    <td style=" border: 1px solid black;">
                        {!! $pago->nit . ' | '. $pago->nombre !!} <br><small
                            class='text-muted'>Empresa: {!! $pago->empresa !!}</small>
                    </td>
                    <td style=" border: 1px solid black;"> {{$pago->glosa}}</td>
                    <td style=" border: 1px solid black;"> {{$pago->tipo}}</td>
                    @if($esCancelado)
                        <td style=" border: 1px solid black;">{{ $pago->metodo==\App\Patrones\TipoPago::Efectivo? number_format($pago->monto, 2):''}}</td>
                        <td style=" border: 1px solid black;">{{ $pago->banco== 'BNB'? number_format($pago->monto, 2):''}}</td>
                        <td style=" border: 1px solid black;">{{ $pago->banco== 'Economico'? number_format($pago->monto, 2):''}}</td>
                    @else
                        <td style=" border: 1px solid black;"> {{number_format($pago->monto, 2)}}</td>
                    @endif
                    <td style=" border: 1px solid black; width: 145px">
                        <div class='btn-group'>
                            @if($pago->es_cancelado)

                                <a class='btn btn-info btn-xs' href="{{ route('movimientos.recibo', [$pago->id]) }}"
                                   target="_blank">
                                    <i class="glyphicon glyphicon-print"></i> Recibo
                                </a>
                                @if(\App\Patrones\Permiso::esCaja() )
                                    <a class='btn btn-default btn-xs' href="#" data-target="#modalFactura"
                                       data-txtid="{{$pago->origen_id}}" data-txtfactura="{{$pago->factura}}"
                                       data-toggle="modal">
                                        <i class="glyphicon glyphicon-list-alt"></i> Registrar factura
                                    </a>
                                @endif

                            @endif

                        </div>
                    </td>
                @else
                    <td style=" border: 1px solid black;">{{ date('d/m/y H:i', strtotime($pago->updated_at)) }}</td>
                    <td style=" border: 1px solid black;">
                        {!! $pago->proveedor->info_proveedor !!}
                    </td>
                    <td style=" border: 1px solid black;"> {{$pago->motivo}}</td>
                    <td style=" border: 1px solid black;"> {{$pago->tipo}}</td>
                    <td style=" border: 1px solid black;"> {{number_format($pago->monto, 2)}}</td>
                    <td style=" border: 1px solid black; width: 145px">
                        <div class='btn-group'>

                            @if($pago->alta)
                                <div class='btn-group'>
                                    <a class='btn btn-info btn-md' href="#" data-target="#modalMovimiento"
                                       data-txtid="{{$pago->id}}" data-txtmonto="{{$pago->monto}}"
                                       data-txtempresa="{{$pago->proveedor->empresa}}"
                                       data-txtproveedor="{{$pago->proveedor->nombre}}"
                                       data-txttipo="{{$pago->tipo}}"
                                       data-toggle="modal">
                                        <i class="glyphicon glyphicon-usd"></i> Pagar
                                    </a>
                                </div>
                            @else
                                <span class='label label-danger'>Anulado</span>
                            @endif

                        </div>
                    </td>
                @endif


            </tr>
        @endforeach
        </tbody>
        @if($esCancelado)
            <tfoot>
                <tr>
                    <td colspan="7" style=" border: 1px solid black;"><strong>TOTALES</strong></td>
                    <td style=" border: 1px solid black;"><strong> {{number_format($pagos->where('metodo', \App\Patrones\TipoPago::Efectivo)->sum('monto'),2)}}</strong></td>
                    <td style=" border: 1px solid black;"><strong> {{number_format($pagos->where('banco', \App\Patrones\Banco::BNB)->sum('monto'),2)}}</strong></td>
                    <td style=" border: 1px solid black;"><strong> {{number_format($pagos->where('banco', \App\Patrones\Banco::Economico)->sum('monto'),2)}}</strong></td>
                </tr>
            </tfoot>

        @endif
    </table>
    {!! Form::open(['route' => 'registrar-factura']) !!}
    @include("movimientos.modal_factura")
    {!! Form::close() !!}

    {!! Form::open(['route' => 'registrar-pago-terceros', 'id' => 'formularioModal']) !!}
    @include("movimientos.modal_pago")
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

        $('#modalFactura').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var factura = button.data('txtfactura')

            var modal = $(this)
            modal.find('.modal-body #idMovimiento').val(id);
            modal.find('.modal-body #factura').val(factura);
        })
        $('#modalMovimiento').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var monto = button.data('txtmonto')
            var tipo = button.data('txttipo')
            var empresa = button.data('txtempresa')
            var proveedor = button.data('txtproveedor')

            var modal = $(this)
            modal.find('.modal-body #idMovimiento').val(id);
            modal.find('.modal-body #monto').val(monto);
            modal.find('.modal-body #tipo').val(tipo);
            modal.find('.modal-body #empresa').val(empresa);
            modal.find('.modal-body #proveedor').val(proveedor);

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
