@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Generar Planilla de Sueldos y Salarios
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
                        @include('rrhh.planillas_sueldos.fields')

                </div>
            </div>
        </div>
    </div>
@endsection

