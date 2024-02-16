@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Laboratorio
        </h1>
    </section>
    <div class="content">
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($laboratorio, ['route' => ['laboratorioQuimicos.update', $laboratorio->id], 'method' => 'patch']) !!}

                    @include('laboratorio_quimicos.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
