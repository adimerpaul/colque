<div class="table-responsive">
    <table class="table table-striped" id="ventas-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Número <br> de Lote</th>
            <th>Fecha de <br> Creación</th>
            <th>Cliente <br>Comprador</th>
            <th>Lote <br>Comprador</th>
            <th>Producto</th>
            <th>Estado</th>
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
                <td>
                    @if(\App\Patrones\Permiso::esComercial())
                        <a href="{{ route('ventas.edit', [$venta->id]) }}">
                            <strong>{{ $venta->lote }}</strong>
                        </a>
                    @elseif(\App\Patrones\Permiso::esOperaciones())
                            <strong>{{ $venta->lote }}</strong>
                    @else
                        <a href="{{ route('get-composito', [$venta->id]) }}">
                            <strong>{{ $venta->lote }}</strong>
                        </a>
                    @endif
                </td>
                <td>{{ date('d/m/y', strtotime($venta->created_at)) }}</td>
                <td>{{ $venta->comprador? $venta->comprador->razon_social: ''}}</td>
                <td>{{ $venta->lote_comprador}}</td>
                <td>{{ $venta->producto}}</td>
                <td>{!!  \App\Patrones\Fachada::estado($venta->estado) !!}
                    <br>
                    {!!  \App\Patrones\Fachada::esCancelado($venta->es_cancelado) !!}
                    <br>
                    {!!  \App\Patrones\Fachada::enviadoOperaciones($venta->a_operaciones) !!}
                    @if(\App\Patrones\Permiso::esRrhh())
                    {!!  \App\Patrones\Fachada::verificadoDespacho($venta->verificado_despacho) !!}
                        @endif
                </td>

                <td style="width: 145px">

                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                Docs/Otros <i style="margin-top: 3px" class="fa fa-angle-down pull-right"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class='dropdown-item' href="{{ url('mostrar-documento-venta', $venta->id) }}"
                                   target="_blank">
                                    <i class="fa fa-files-o"></i>
                                    Docs. Ajuntos
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class='dropdown-item'
                                   href="{{ url('ventas/ordenDespacho', $venta->id) }}"
                                   target="_blank">
                                    <i class="glyphicon glyphicon-file"></i>
                                    Orden Despacho
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class='dropdown-item'
                                   href="{{ url('ventas/ordenVenta', $venta->id) }}"
                                   target="_blank">
                                    <i class="fa fa-file-text-o"></i>
                                    Orden Venta
                                </a>
                                    <div class="dropdown-divider"></div>
                                    <a class='dropdown-item' href="#"
                                       data-txtid="{{$venta->id}}"
                                       data-toggle="modal" data-target="#modalFactura">
                                        <i class="glyphicon glyphicon-usd"></i>
                                        Generar factura
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
