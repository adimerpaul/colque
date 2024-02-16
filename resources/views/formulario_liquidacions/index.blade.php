@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Compras</h1>
        <h1 class="pull-right">
            @if(\App\Patrones\Permiso::esAdmin())
                <a class="btn btn-info pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-left: 5px"
                   data-toggle="modal" data-target="#modalIntercambio">Intercambiar lotes</a>
            @endif
            @if(\App\Patrones\Permiso::esPesaje() || \App\Patrones\Permiso::esComercial()|| \App\Patrones\Permiso::esOperaciones())

                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
                   href="{{ route('formularioLiquidacions.create') }}">Nuevo Lote</a>

            @endif
                <a class="btn btn-success pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-right: 5px"
                   href="#" data-toggle="modal" data-target="#modalAnalisisAnimas">Reporte análisis</a>
        </h1>
        <br>
    </section>
    <div class="content" id="appFormularioIndex">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'formularioLiquidacions.index', 'method'=>'get']) !!}
                        <div class="form-group col-sm-4">
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ?$_GET['txtBuscar']: null, ['class' => 'form-control', 'placeholder'=>'Nro de lote, cliente, empresa, producto']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Liq. Ini.:') !!}
                            {!! Form::date('fecha_inicial', isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial'] : date('Y-m-d', strtotime(date('Y-m-d'). ' - 6 months')), ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Liq. Fin.:') !!}
                            {!! Form::date('fecha_final', isset($_GET['fecha_final']) ? $_GET['fecha_final'] : date('Y-m-d'), ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('txtEstado', 'Estado:') !!}
                            {!! Form::select('txtEstado', ['%' => 'Todos'] + \App\Patrones\Fachada::getEstados(),isset($_GET['txtEstado']) ? $_GET['txtEstado'] : null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-2" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                @include('formulario_liquidacions.table')
            </div>
        </div>
        <div class="text-center">
            {{ $formularioLiquidacions->appends($_GET)->links()  }}
        </div>
    </div>
    @include("formulario_liquidacions.modalIntercambio")
    @include("lab.ensayos.modal_reporte")
@endsection

