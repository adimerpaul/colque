<div class="table-responsive">
    <table class="table table-striped" id="movimientos-tabla"
           name="movimientos-tabla">
        <thead>

        <tr>
            <th>#</th>
            <th>Comprobante</th>
            <th>Fecha</th>
            <th>Proveedor</th>
            <th>Glosa</th>
            <th>Empresa</th>
            <th>Monto BOB</th>
            <th>Tipo</th>
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
                <td>{{ $page + ($row++) }}</td>
                <td>{{ $pago->codigo }}</td>
                <td>{{ date('d/m/y H:i', strtotime($pago->created_at)) }}</td>
                <td>{{ $pago->cliente }}</td>
                <td> {{$pago->glosa}}</td>
                <td>{{ $pago->empresa }}</td>
                <td>{{number_format($pago->monto, 2)}}</td>
                <td> {{$pago->origen->tipo}}</td>
                <td>
                    <div class='btn-group'>
                        <a class='btn btn-danger btn-sm' href="#" data-target="#modalAnulacion"
                           data-txtid="{{$pago->id}}" data-txtmonto="{{$pago->monto}}"
                           data-txtcliente="{{$pago->empresa}}" data-txtcomprobante="{{$pago->codigo}}"
                           data-toggle="modal">
                            <i class="glyphicon glyphicon-remove"></i> Anular pago
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! Form::open(['route' => 'movimientos.anular-pago', 'id' => 'formularioModal']) !!}
    @include("movimientos.modal_anulacion")
    {!! Form::close() !!}
</div>
@push('scripts')

    <script>
        $("#formularioModal").on("submit", function () {
            $("#botonGuardar").prop("disabled", true);
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

    </script>

@endpush
