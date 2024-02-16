@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Empresa: {{ $empresa->razon_social }} | Nuevo usuario
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'personals.store', 'files' => true]) !!}

                        @include('personals.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
