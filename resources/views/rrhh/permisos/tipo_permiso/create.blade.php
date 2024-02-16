@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Designación de Permiso
        </h1>
        <h1 class="pull-right">
            
                <a href="#" data-target="#modalDetalle"
                    style="margin-top: -30px;margin-bottom: 5px"
                    class="btn btn-primary pull-right"
                    data-toggle="modal">Añadir nuevos permisos</a>
        </h1> 
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'tipos-permisos-personal.create']) !!}
                    @include('rrhh.permisos.tipo_permiso.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    {!! Form::open(['route' => 'tipos-permisos.create']) !!}
        @include("rrhh.permisos.tipo_permiso.modal_fields")
    {!! Form::close() !!}


@endsection
