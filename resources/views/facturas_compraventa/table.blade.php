<div class="table-responsive">
    <table class="table"  style="width: 100px;" id="compraventa-table">
        <thead>
        <tr>
            <th>#</th>
{{--            <th>Mes</th>--}}
{{--            <th>Gestion</th>--}}
            <th>Nro Fac.</th>
            <th>Cuf</th>
            <th>Cufd</th>
            <th>Fecha</th>
            <th>Monto total</th>
            <th>Anulado?</th>
            <th>Tipo Factura</th>
            <th>Opciones</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($facturas->currentPage() - 1) * $facturas->perPage();
        $row = 1;
        ?>
        @foreach($facturas as $venta)
            <tr>
                <td class="text-muted">{{ $page + ($row++) }}</td>
{{--                <td>{{ $venta->mes }}</td>--}}
{{--                <td>{{ $venta->gestion }}</td>--}}
                <td>{{ $venta->nroFactura}}</td>
                <td>{{ $venta->cuf}}</td>
                <td>{{ $venta->cufd}}</td>
                <td>{{ date('d/m/y', strtotime($venta->fechaEmision)) }}</td>
                <td>{{ $venta->montoTotal}}</td>
                <td>{{ $venta->es_anulado}}</td>
                <td>{{ $venta->tipo_factura}}</td>


                <td style="width: 145px">

                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                Opciones <i style="margin-top: 3px" class="fa fa-angle-down pull-right"></i>
                            </button>
                            <div class="dropdown-menu">
                                <div class="dropdown-divider"></div>
                                <a class='dropdown-item'
                                   href="{{ url('resvertirCompraVenta', $venta->cuf) }}"
                                   >
                                    <i class="glyphicon glyphicon-file"></i>
                                    Revertir Anulacion
                                </a>
                                <div class="dropdown-divider"></div>
                                    <?php
                                    $gestion = date('Y', strtotime($venta->fechaEmision));
                                    $mes = date('m', strtotime($venta->fechaEmision));
                                    $dia = date('d', strtotime($venta->fechaEmision));
                                    ?>
                                <a class='dropdown-item'
                                   href="{{ url("http://192.168.1.105:8003/api/downloadFile?filename=colquechaca/$gestion$mes$dia/$venta->nroFactura.pdf") }}"
                                   target="_blank">
                                    <i class="fa fa-file-text-o"></i>
                                    Reimprimir
                                </a>


                                <div class="dropdown-divider"></div>
                                <a class='dropdown-item' href="#"
                                   data-txtid="{{$venta->cuf}}"
                                   data-toggle="modal" data-target="#modalFactura">
                                    <i class="glyphicon glyphicon-usd"></i>
                                    Anular factura
                                </a>
                            </div>

                        </div>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@push('scripts')
    <script>

        $('#modalFactura').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')

            var modal = $(this)
            modal.find('.modal-body #idVenta').val(id);

        })


    </script>
@endpush
