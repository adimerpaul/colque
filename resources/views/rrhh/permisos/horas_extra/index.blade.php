@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">
            Horas Extra Personal</b>
        </h1>
        <h1 class="pull-right">
                <a class="btn btn-primary pull-right"
                   style="margin-top: -10px;margin-bottom: 5px"
                   href="{{ route('horas-extras.create') }}" title="Solicitar Nueva Hora Extra">Crear nuevo</a>
        </h1>
        <br>
    </section>
    <div class="content">
    @include('flash::message')
       @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                    {!! Form::open(['route' => 'horas-extras.index','method'=>'get'])!!}
                        <div class="form-group col-sm-4">
                            {!! Form::label('personal_id', 'Personal:') !!}
                            {!! Form::select('personal_id', ['%' => 'Todos']+\App\Patrones\Fachada::getPersonal(),isset($_GET['personal_id']) ? $_GET['personal_id'] : null, ['class' => 'form-control', 'required']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('inicio', 'Fecha Inicial:') !!}
                            {!! Form::date('inicio', old('inicio', isset($_GET['inicio']) ? $_GET['inicio'] : date('Y-m-d', strtotime('-1 month', strtotime(isset($_GET['fin']) ? $_GET['fin'] : date('Y-m-d'))))), ['class' => 'form-control','required']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('fin', 'Fecha Final:') !!}
                            {!! Form::date('fin', old('fin', isset($_GET['fin']) ? $_GET['fin'] : date('Y-m-d')), ['class' => 'form-control','required']) !!}
                        </div>
                        <div class="form-group col-sm-1" style="margin-top: 24px">
                            <button type="submit" class="btn btn-default glyphicon glyphicon-search" title="Buscar datos"></button>
                        </div>
                    {!! Form::close() !!}
                    </div>
                </div>
                @include('rrhh.permisos.horas_extra.table')   
            </div>
        </div>
    </div>
@endsection
