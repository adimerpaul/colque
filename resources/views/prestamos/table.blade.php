<div class="table-responsive">
    <table style=" border: 1px solid black;" class="table table-striped" id="prestamos-table">
        <thead>
        <tr>
            @if($esCancelado)
                <th colspan="11" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                    <br>REPORTE DE PRÉSTAMOS
                    <br>
                </th>
            @endif
        </tr>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            @if($esCancelado)
                <th style=" border: 1px solid black;">Comprobante</th>
            @endif
            <th style=" border: 1px solid black;">Fecha</th>
            <th style=" border: 1px solid black;">Cliente</th>
            <th style=" border: 1px solid black;">Productor</th>

            <th style=" border: 1px solid black;">Registrado por</th>
            <th style=" border: 1px solid black;">Autorizado por</th>
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
        $page = ($prestamos->currentPage() - 1) * $prestamos->perPage();
        $row = 1;
        ?>
        @foreach($prestamos as $prestamo)
            <tr>
                <td style=" border: 1px solid black;" class="text-muted">{{ $page + ($row++) }}</td>
                @if($esCancelado)
                    <td style=" border: 1px solid black;"><strong>{{ $prestamo->codigo_caja }}</strong></td>
                @endif
                <td style=" border: 1px solid black;">{{ date('d/m/y', strtotime($prestamo->updated_at)) }}</td>
                <td style=" border: 1px solid black;">{!! $prestamo->cliente->nombre !!}<br><small
                        class='text-muted'>{!! $prestamo->cliente->nit !!}</small></td>
                <td style=" border: 1px solid black;">{!! $prestamo->cliente->cooperativa->razon_social !!}</td>

                <td style=" border: 1px solid black;">
                    @if($prestamo->registrado)
                        {{ $prestamo->registrado->personal->nombre_completo }}
                    @endif
                </td>
                <td style=" border: 1px solid black;">
                    @if($prestamo->aprobadoPor)
                        {{ $prestamo->aprobadoPor->personal->nombre_completo }}
                    @endif
                </td>
                @if($esCancelado)
                    <td style=" border: 1px solid black;">{{ $prestamo->metodo_pago==\App\Patrones\TipoPago::Efectivo? number_format($prestamo->monto, 2):''}}</td>
                    <td style=" border: 1px solid black;">{{ $prestamo->metodo_pago== 'BNB'? number_format($prestamo->monto, 2):''}}</td>
                    <td style=" border: 1px solid black;">{{ $prestamo->metodo_pago== 'Economico'? number_format($prestamo->monto, 2):''}}</td>
                @else
                    <td style=" border: 1px solid black;">{{ number_format($prestamo->monto, 2) }}</td>
                @endif
                <td style="width: 145px; border: 1px solid black;">
                    @if($esCancelado)
                        <a class='btn btn-info' href="{{ route('prestamos.imprimir', [$prestamo->id]) }}"
                           target="_blank">
                            <i class="glyphicon glyphicon-print"></i> Recibo
                        </a>
                    @else
                        <div class='btn-group'>
                            @if($prestamo->alta)
                                <a class='btn btn-info btn-md' href="#" data-target="#modalPrestamo"
                                   data-txtid="{{$prestamo->id}}" data-txtmonto="{{$prestamo->monto}}"
                                   data-txtcooperativa="{{$prestamo->cliente->cooperativa->razon_social}}"
                                   data-txtcliente="{{$prestamo->cliente->nombre}}"
                                   data-toggle="modal">
                                    <i class="glyphicon glyphicon-usd"></i> Pagar
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
        @if($esCancelado)
            <tfoot>
            <tr>
                <td colspan="7" style=" border: 1px solid black;"><strong>TOTALES</strong></td>
                <td style=" border: 1px solid black; font-weight: bold" id="efectivoTotal"></td>
                <td style=" border: 1px solid black; font-weight: bold" id="bnbTotal"></td>
                <td style=" border: 1px solid black; font-weight: bold" id="economicoTotal"></td>
            </tr>
            </tfoot>

        @endif
    </table>
    {!! Form::open(['route' => 'prestamos.registrar-pago', 'id' => 'formularioModal']) !!}
    @include("prestamos.modal_pago")
    {!! Form::close() !!}
</div>
@push('scripts')

    <script>
        if ("{{$esCancelado}}" == true) {
            var table = document.getElementById("prestamos-table"), sumaEfectivo = 0, sumaBnb = 0, sumaEconomico = 0;
            for (var i = 2; i < (table.rows.length - 1); i++) {
                sumaEfectivo = table.rows[i].cells[7].innerHTML == '' ? parseFloat(sumaEfectivo) : (parseFloat(sumaEfectivo) + parseFloat(table.rows[i].cells[7].innerHTML.replace(/,/g, "")));
                sumaBnb = table.rows[i].cells[8].innerHTML == '' ? parseFloat(sumaBnb) : (parseFloat(sumaBnb) + parseFloat(table.rows[i].cells[8].innerHTML.replace(/,/g, "")));
                sumaEconomico = table.rows[i].cells[9].innerHTML == '' ? parseFloat(sumaEconomico) : (parseFloat(sumaEconomico) + parseFloat(table.rows[i].cells[9].innerHTML.replace(/,/g, "")));
            }
            document.getElementById("efectivoTotal").innerHTML = (sumaEfectivo.toFixed(2));
            document.getElementById("bnbTotal").innerHTML = (sumaBnb.toFixed(2));
            document.getElementById("economicoTotal").innerHTML = (sumaEconomico.toFixed(2));
        }
        $("#formularioModal").on("submit", function () {
            $("#botonGuardar").prop("disabled", true);
        });
        $(document).ready(function () {
            $("#bancoDiv").hide();
            $("#nroRecibo").hide();
            document.getElementById('numero_recibo').removeAttribute('required', '');
        });
        $('#modalPrestamo').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')
            var monto = button.data('txtmonto')
            var cooperativa = button.data('txtcooperativa')
            var cliente = button.data('txtcliente')

            var modal = $(this)
            modal.find('.modal-body #idPrestamo').val(id);
            modal.find('.modal-body #monto').val(monto);
            modal.find('.modal-body #cooperativa').val(cooperativa);
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
