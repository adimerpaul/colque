<div class="table-responsive">
    <table class="table table-striped" id="retenciones-tabla">
        <thead>
        <tr>
            <th></th>
            <th>#</th>
            <th>Fecha</th>
            <th>Productor</th>
            <th>Nit</th>
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
            <tr id="{{'retencion'. $retencion->id}}">
                <td>
                    @if($retencion->fecha_fin < date('Y-m-d'))
                        <input type="checkbox" id="{{$loop->iteration}}"
                               onchange='seleccionar("{{$loop->iteration}}", "{{$retencion->id}}")'>
                    @endif
                </td>
                <td class="text-muted">{{ $page + ($row++) }}</td>
                <td>{{ date('d/m/y', strtotime($retencion->created_at)) }}</td>
                <td>{!! $retencion->cooperativa->razon_social !!}</td>
                <td>{!! $retencion->cooperativa->nit !!}</td>
                <td>{{ $retencion->motivo }}</td>
                <td>{{ round( $retencion->monto_final, 2) }}</td>
                <td>
                    <a class='btn btn-info'
                       href="{{ route('retenciones.detalle', [$retencion->id]) }}"
                       target="_blank">
                        <i class="glyphicon glyphicon-list"></i> Detalle
                    </a>
                    @if(\App\Patrones\Permiso::esAdmin())
                        {!! Form::open(['route' => ['eliminar-retencion', $retencion->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Estás seguro de eliminar?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! Form::open(['route' => 'aprobar-retencion', 'id' => 'formularioModal']) !!}
    @include("retenciones_pagos.modal_aprobacion")
    {!! Form::close() !!}
</div>
@push('scripts')
    <script>
        var arraySeleccionados = [];
        var table = document.getElementById("retenciones-tabla");
        var sumaMonto = 0, cantidad = 0, productor = '';
        $("#formularioModal").on("submit", function () {
            $("#botonGuardar").prop("disabled", true);
        });

        $('#modalPago').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)

            var modal = $(this)
            modal.find('.modal-body #monto').val(sumaMonto.toFixed(2));
            modal.find('.modal-body #cooperativa').val(productor);
            modal.find('.modal-body #seleccionados').val(arraySeleccionados);
        })
        $('#btnAprobar').hide();

        function seleccionar(contador, retencionId) {
            if ($("#" + contador).is(':checked')) {
                arraySeleccionados.push(retencionId)
                sumaMonto = parseFloat(sumaMonto) + parseFloat(table.rows[parseInt(contador)].cells[6].innerHTML.replace(/,/g, "."));
                cantidad = parseInt(cantidad) + 1;
                productor = table.rows[parseInt(contador)].cells[3].innerHTML.replace(/,/g, ".");
                document.getElementById("retencion" + retencionId).style.backgroundColor = "#90CAF9";
            } else {
                var index = arraySeleccionados.indexOf(retencionId);
                if (index > -1) {
                    arraySeleccionados.splice(index, 1);
                }
                cantidad = parseInt(cantidad) - 1;
                sumaMonto = parseFloat(sumaMonto) - parseFloat(table.rows[parseInt(contador)].cells[6].innerHTML.replace(/,/g, "."));
                if (parseInt(contador) % 2 == 0)
                    document.getElementById("retencion" + retencionId).style.backgroundColor = "white";
                else
                    document.getElementById("retencion" + retencionId).style.backgroundColor = "#f9f9f9";
            }
            if (cantidad < 1)
                $("#btnAprobar").hide();
            else
                $("#btnAprobar").show();
        }

    </script>

@endpush
