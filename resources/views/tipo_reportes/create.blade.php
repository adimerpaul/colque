@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tipo de reporte
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
                    {!! Form::open(['route' => 'tipoReportes.store']) !!}


                        @include('tipo_reportes.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
