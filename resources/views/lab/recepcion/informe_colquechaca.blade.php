@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Muestras</h1>

    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'get-informes-colquechaca-lab', 'method'=>'get']) !!}

                        <div class="form-group col-sm-4">
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ? $_GET['txtBuscar'] : null, ['class' => 'form-control', 'placeholder'=>'Código']) !!}
                        </div>

                        <div class="form-group col-sm-3">
                            {!! Form::label('Fecha', 'Fecha Inicio:') !!}
                            {!! Form::date('fecha_inicial', isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial'] : date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 months')), ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-3">
                            {!! Form::label('Fecha', 'Fecha Fin:') !!}
                            {!! Form::date('fecha_final', isset($_GET['fecha_final']) ? $_GET['fecha_final'] : date('Y-m-d'), ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group col-sm-2" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="pedido-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Lotes</th>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pedidos as $pedido)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                        <strong>{{ $pedido->codigo_pedido }}</strong>
                                    </td>
                                <td>{{  $pedido->lotes }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($pedido->created_at)) }}</td>
                                <td>{{ $pedido->cantidad }}</td>
                                <td>{!! \App\Patrones\Fachada::estadoLaboratorio($pedido->estado)  !!} </td>
                                <td>


                                    @if($pedido->estado==\App\Patrones\EstadoLaboratorio::Finalizado )
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
            </div>
        </div>
        <div class="text-center">
            {{ $pedidos->appends($_GET)->links()  }}
        </div>
    </div>
@endsection

