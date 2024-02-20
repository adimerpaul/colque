@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Descuentos/Bonificaciones</h1>
        <div>&nbsp;&nbsp; ({{ $cooperativa->info }})</div>
        <h1 class="pull-right">
            <a class="btn btn-default pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-left: 5px" href="{{ route('cooperativas.index') }}">Volver</a>
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('descuentosBonificaciones.register', ['id' => $cooperativa->id]) }}">Agregar nuevo</a>
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
{{--                        {!! Form::model($descuento, ['route' => ['descuentosBonificaciones.update', $descuento->id], 'method' => 'patch']) !!}--}}

                        {!! Form::open(['route' =>['descuentosBonificaciones.lista', $cooperativa->id], 'method'=>'get']) !!}

                        <div class="form-group col-sm-4">
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ? $_GET['txtBuscar'] : null, ['class' => 'form-control', 'placeholder'=>'Nombre']) !!}
                        </div>
                        <div class="form-group col-sm-3">
                            {!! Form::label('tipo', 'Tipo:') !!}
                            {!! Form::select('tipo', \App\Patrones\Fachada::tiposPagos(), isset($_GET['tipo']) ? $_GET['tipo'] : "%" , ['class' => 'form-control', 'id' =>'tipo']) !!}

                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('alta', 'Estado:') !!}
                            {!! Form::select('alta', \App\Patrones\Fachada::estadosAltas(), isset($_GET['alta']) ? $_GET['alta'] : "%" , ['class' => 'form-control', 'id' =>'alta']) !!}

                        </div>

                        <div class="form-group col-sm-2" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                    @include('descuentos.table')
            </div>
        </div>
        <div class="text-center">
            {{ $descuentos->appends($_GET)->links()  }}
        </div>
    </div>
@endsection

