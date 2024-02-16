@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Descuento/Bonificación
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
                    {!! Form::open(['route' => 'descuentosBonificaciones.store']) !!}


                        @include('descuentos.fields')

                    {!! Form::close() !!}
                    @include("descuentos.modal_catalogo")

                </div>
            </div>
        </div>
    </div>
@endsection
