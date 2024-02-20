<div class="table-responsive">
    <table class="table table-striped" id="pedido-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Código</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Cantidad</th>
            <th>Ensayos Sin Resultados</th>
            <th>Estado</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($pedidos as $pedido)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><a href="{{ route('recepcion-lab.edit', [$pedido->id]) }}">
                        <strong>{{ $pedido->codigo_pedido }}</strong>
                    </a></td>
                <td>{!!  $pedido->cliente->info !!}</td>
                <td>{{ date('d/m/Y H:i', strtotime($pedido->created_at)) }}</td>
                <td>{{ $pedido->cantidad }}</td>
                <td>{{ $pedido->ensayos_sin_finalizar }}</td>
                <td>{!! \App\Patrones\Fachada::estadoLaboratorio($pedido->estado)  !!} </td>
                <td>
                    @if($pedido->estado==\App\Patrones\EstadoLaboratorio::EnProceso)
                        <div class='btn-group'>
                            {!! Form::open(['route' => ['finalizar-ensayos-lab'], 'method' => 'post', 'id' => 'formularioFinalizar']) !!}
                            <div class='btn-group'>
                                {!! Form::hidden('id', $pedido->id) !!}
                                @if($pedido->ensayos_sin_finalizar==0)
                                    {!! Form::button('&nbsp;<i class="glyphicon glyphicon-check"></i>&nbsp;', ['type' => 'submit', 'class' => 'btn btn-success btn-xs', 'id'=>'botonFinalizar', 'title' => 'Finalizar', 'onclick' => "return confirm('¿Estás seguro de finalizar?')"]) !!}
                                @else
                                    {!! Form::button('&nbsp;<i class="glyphicon glyphicon-check"></i>&nbsp;', ['type' => 'submit', 'class' => 'btn btn-success btn-xs', 'id'=>'botonFinalizar', 'title' => 'Finalizar', 'onclick' => "return confirm('¿Estás seguro de finalizar? Existen lotes sin resultados')"]) !!}
                                @endif
                            </div>
                            {!! Form::close() !!}


                            @if($pedido->ensayos_sin_finalizar==$pedido->cantidad)

                                {!! Form::model($pedido, ['route' => ['anular-ensayos-lab', $pedido->id], 'method' => 'put']) !!}

                                        {!! Form::button('&nbsp;<i class="glyphicon glyphicon-remove"></i>&nbsp;', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'title' => 'Anular', 'onclick' => "return confirm('¿Estás seguro de anular? ')"]) !!}
                                {!! Form::close() !!}
                            @endif
                        </div>
                    @endif

                    @if($pedido->estado==\App\Patrones\EstadoLaboratorio::Finalizado )
{{--                            AND $pedido->es_cancelado--}}
                            <a class='btn btn-info btn-xs' title="Informe" href="{{ route('imprimir-informe-ensayo', [$pedido->id]) }}"
                               target="_blank">
                                <i class="glyphicon glyphicon-print"></i>
                            </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@push('scripts')

    <script>
        $("#formularioFinalizar").on("submit", function () {
            $("#botonFinalizar").prop("disabled", true);
        });


    </script>

@endpush
