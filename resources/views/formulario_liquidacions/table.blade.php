<div class="table-responsive">
    <table class="table table-striped" id="formularioLiquidacions-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Número <br> de Lote</th>
            <th>Fecha de <br> Recepción</th>
            <th>Fecha de <br> Liquidación</th>
            <th>Cliente <br>Productor</th>
            <th>Producto</th>
            <th>Estado</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $page = ($formularioLiquidacions->currentPage() - 1) * $formularioLiquidacions->perPage();
        $row = 1;
        ?>
        @foreach($formularioLiquidacions as $formularioLiquidacion)
            <tr>
                <td class="text-muted">{{ $page + ($row++) }}</td>
                <td>
                    @if(\App\Patrones\Permiso::esComercial() OR \App\Patrones\Permiso::esOperaciones())
                        <a href="{{ route('formularioLiquidacions.edit', [$formularioLiquidacion->id]) }}">
                            <strong>{{ $formularioLiquidacion->lote }}</strong>
                        </a>

                    @else
                        <strong>{{ $formularioLiquidacion->lote }}</strong>
                    @endif
                    @if($formularioLiquidacion->lote_venta!='')
                        <br><small class='text-muted'>{{ 'Lote venta: '.$formularioLiquidacion->lote_venta }}</small>
                    @endif
                </td>
                <td>{{ date('d/m/y', strtotime($formularioLiquidacion->created_at)) }}</td>
                <td>
                    @if($formularioLiquidacion->fecha_liquidacion)
                        {{ date('d/m/y', strtotime($formularioLiquidacion->fecha_liquidacion)) }}
                    @endif
                </td>
                <td>
                    @if($formularioLiquidacion->cliente_id)
                        {!! $formularioLiquidacion->cliente->infoCliente !!}
                    @endif
                </td>
                <td>{{ $formularioLiquidacion->producto }}</td>
                <td>{!!  \App\Patrones\Fachada::estado($formularioLiquidacion->estado) !!}
                    @if($formularioLiquidacion->es_retirado)
                        <span class='label label-warning'>Retirado</span>
                    @endif
                    <br>
                    <a>
                        @if(\App\Patrones\Permiso::esComercial())
                            {!!  \App\Patrones\Fachada::documentoSubido($formularioLiquidacion->faltan_documentos) !!}
                        @endif
                        @if(\App\Patrones\Permiso::esOperaciones())
                            <a class="dropdown-item"
                               href="{{ $formularioLiquidacion->en_molienda ? url('concluir-molienda', $formularioLiquidacion->id) : '#' }}"
                               onclick="return confirm('¿Estás seguro de finalizar la molienda?')"
                               style="pointer-events: {{ $formularioLiquidacion->en_molienda ?'auto':'none'}}">
                                {!!  \App\Patrones\Fachada::moliendo($formularioLiquidacion->en_molienda) !!}
                            </a>
                        @endif
                    </a>
                </td>
                <td style="width: 145px">
                    <div class='btn-group'>

                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                Docs. / Otros<i style="margin-top: 3px" class="fa fa-angle-down pull-right"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class='dropdown-item' href="{{ url('boleta_pesaje', $formularioLiquidacion->id) }}"
                                   target="_blank">
                                    <i class="fa fa-balance-scale"></i>
                                    Boleta de pesaje
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class='dropdown-item'
                                   href="{{ url('imprimirFormulario', $formularioLiquidacion->id) }}"
                                   target="_blank">
                                    <i class="glyphicon glyphicon-file"></i>
                                    Formulario
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class='dropdown-item'
                                   href="{{ url('mostrar-documento', $formularioLiquidacion->id) }}"
                                   target="_blank">
                                    <i class="fa fa-files-o"></i>
                                    Docs. Adjuntos
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ url('contrato_compra', $formularioLiquidacion->id) }}"
                                   target="_blank">
                                    <i class="fa fa-file-text"></i> Contrato</a>
                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('devoluciones-por-lote', [$formularioLiquidacion->id]) }}"
                                   target="_blank">
                                    <i class="glyphicon glyphicon-usd"></i> Devoluciones</a>

{{--                                @if($formularioLiquidacion->estado==\App\Patrones\Estado::EnProceso and $formularioLiquidacion->cantidad_devoluciones==0 and \App\Patrones\Permiso::esComercial())--}}
{{--                                    <div class="dropdown-divider"></div>--}}

{{--                                    <a class='dropdown-item'--}}
{{--                                       onclick = "return confirm('¿Estás seguro de finalizar el lote '+'{{$formularioLiquidacion->lote}}?')"--}}
{{--                                       href="{{ url('finalizar-compra-afuera', $formularioLiquidacion->id) }}"--}}
{{--                                    >--}}
{{--                                        <i class="fa fa-check"></i>--}}
{{--                                        Finalizar--}}
{{--                                    </a>--}}
{{--                                @endif--}}
                            </div>

{{--                            @if(\App\Patrones\Permiso::esContabilidad())--}}
{{--                                <a class='btn btn-primary btn-sm' target="_blank"--}}
{{--                                   href="{{ route('devoluciones-por-lote', [$formularioLiquidacion->id]) }}"--}}
{{--                                   target="_blank">--}}
{{--                                    <i class="glyphicon glyphicon-usd"></i> Devoluciones--}}
{{--                                </a>--}}
{{--                            @endif--}}
                        </div>

                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br><br><br><br>
</div>
