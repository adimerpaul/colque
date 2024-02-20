@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Permiso
        </h1>
        <h1 class="pull-right">

                <a href="#" data-target="#modalDetalle"
                    style="margin-top: -30px;"
                    class="btn btn-primary pull-right"
                    data-toggle="modal">Detalle permisos</a>
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
                    {!! Form::open(['route' => 'permisos.store']) !!}
                    @include('rrhh.permisos.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @include("rrhh.permisos.modal_table")
@endsection
