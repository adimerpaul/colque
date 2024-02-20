@extends('lab.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Muestras</h1>
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('recepcion-lab.create') }}">Agregar nuevo</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'recepcion-lab.index', 'method'=>'get']) !!}

                        <div class="form-group col-sm-4">
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ? $_GET['txtBuscar'] : null, ['class' => 'form-control', 'placeholder'=>'Código, Cliente']) !!}
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
                @include('lab.recepcion.table')
            </div>
        </div>
        <div class="text-center">
            {{ $pedidos->appends($_GET)->links()  }}
        </div>
    </div>
@endsection

