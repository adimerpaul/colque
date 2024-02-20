@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Registrar Tabla de Precios de Estaño
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'tablaAcopiadoras.store']) !!}

                        @include('tabla_acopiadoras.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
