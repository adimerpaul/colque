@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cotización
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="clearfix"></div>

        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'cotizacions.store']) !!}
                        @include('cotizacions.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
