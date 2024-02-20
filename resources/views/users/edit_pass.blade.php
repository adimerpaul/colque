@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Perfil de usuario
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @if(!\App\Patrones\Fachada::cambioPass())
                    <div class="alert alert-danger small">
                        <strong>ANTES DE CONTINUAR DEBE CAMBIAR SU PASSWORD OBLIGATORIAMENTE.</strong>
                    </div>
                @endif
                <div class="row" style="padding-left: 20px">

                    {!! Form::open(['route' => 'users.updatePass']) !!}

                    @include('users.fields_pass')
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
