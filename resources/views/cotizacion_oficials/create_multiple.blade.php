@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cotizaciones Oficiales
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="clearfix"></div>

        <div class="box box-primary">
            <div class="box-body">
                @include('cotizacion_oficials.fields_multiple')
            </div>
        </div>
    </div>
@endsection
