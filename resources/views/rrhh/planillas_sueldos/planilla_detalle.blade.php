@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Detalle planilla
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <!-- Email Field -->
                    <div class="form-group">
                        <hr>
                        {!! Form::label('email', 'Email:') !!}
                        <p>{{ $user->email }}</p>
                    </div>

                    <!-- Rol Field -->
                    <div class="form-group">
                        {!! Form::label('rol', 'Rol:') !!}
                        <p>{{ $user->rol }}</p>
                    </div>

                    <div class="form-group">
                        {!! Form::label('nombre', 'Nombre Completo:') !!}
                        <p>{{ $user->personal->nombre_completo }}</p>
                    </div>

                    <div class="form-group">
                        {!! Form::label('ci', 'Documento de identidad:') !!}
                        <p>{{ $user->personal->ci .' '. $user->personal->expedido}}</p>
                    </div>

                    <div class="form-group">
                        {!! Form::label('celular', 'Celular:') !!}
                        <p>{{ $user->personal->celular }}</p>
                    </div>

                    <!-- Ultimo Cambio Password Field -->
                    <div class="form-group">
                        {!! Form::label('ultimo_cambio_password', 'Último Cambio de Password:') !!}
                        <p>{{ date('d/m/Y', strtotime($user->ultimo_cambio_password))}}</p>
                    </div>

                    <a href="{{ route('users.editPass') }}" class="btn btn-primary">Cambiar Password</a>

                </div>
            </div>
        </div>
    </div>
@endsection