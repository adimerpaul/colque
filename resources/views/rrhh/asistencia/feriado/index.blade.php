@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Feriados</h1>
        <h1 class="pull-right">
            @if(\App\Patrones\Permiso::esActivos())
                <a class="btn btn-primary pull-right"
                   style="margin-top: -10px;margin-bottom: 5px"
                   href="{{ route('feriados.create') }}">Agregar Feriado</a>
            @endif
        </h1>
        
    </section>

    <section class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <div class=¨card¨>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                        <div>
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                                {!! Form::open(['route' => 'feriados', 'method' => 'get']) !!}
                                <div class="form-group col-sm-3">
                                    {!! Form::label('fecha_i', 'Fecha Inicial:') !!}
                                    {!! Form::date('fecha_i', old('fecha_i'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group col-sm-3">
                                    {!! Form::label('fecha_f', 'Fecha Final:') !!}
                                    {!! Form::date('fecha_f', old('fecha_f'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group col-sm-1" style="margin-top: 24px">
                                    <button type="submit" class="btn btn-default glyphicon glyphicon-search" title="Buscar Datos"></button>
                                </div>
                            {!! Form::close() !!}
                        </div>
                        <div></div>
                          @if($mensaje = Session::get('success'))
                                <div class="alert alert-success" role="alert">
                                    {{$mensaje}}
                                </div>
                            @endif
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        @include('rrhh.asistencia.feriado.feriado_table')
                    </div>
                </div>
                <div class="text-center">
                    
                    
                </div>
            </div>
        </div>
        <div class="text-center">
                    {{ $feriados->links() }}
                    
        </div>
    </section>
    

@endsection
