@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Añadir mi hora extra
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
                    {!! Form::open(['route' => 'horas-extras.store','method'=>'POST']) !!}

                    <!--Fecha Inicial-->
                    <div class="form-group col-sm-6">
                        {!! Form::label('fecha_inicio', 'Fecha Inicio: *') !!}
                        {!! Form::date('fecha_inicio', date("Y-m-d"), ['class' => 'form-control', 'required']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('hora_inicio', 'Hora Inicio: *') !!}
                        {!! Form::time('hora_inicio', "08:00", ['class' => 'form-control', 'required']) !!}
                    </div>
                    <!--Fecha Final-->
                    <div class="form-group col-sm-6">
                        {!! Form::label('fecha_fin', 'Fecha Fin: *') !!}
                        {!! Form::date('fecha_fin', date("Y-m-d"), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('hora_fin', 'Hora Fin: *') !!}
                        {!! Form::time('hora_fin', "16:30", ['class' => 'form-control', 'required']) !!}
                    </div>
                    <!-- ID personal-->
                    <div class="form-group col-sm-4">
                        {!! Form::label('personal_id', 'Personal :*') !!}
                        {!! Form::text('personal_id', \App\Patrones\Fachada::getpersonalUser(auth()->user()->personal->id)[auth()->user()->personal->id], ['class' => 'form-control', 'readonly' => 'readonly', 'required']) !!}
                    </div>
                    <!-- Descripcion-->
                    <div class="form-group col-sm-8">
                        {!! Form::label('descripcion', 'Descripción:*') !!}
                        {!! Form::text('descripcion', null, ['class' => 'form-control','maxlength' => '300','required']) !!}
                    </div>
                    
                    <div class="form-group col-sm-12">
                        <br>
                        {!! Form::submit('Solicitar', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('mis-horas-extra') }}" class="btn btn-default">Cancelar</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

