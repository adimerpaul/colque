@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Bonificacion/Bonificación
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($descuento, ['route' => ['descuentosBonificaciones.update', $descuento->id], 'method' => 'patch']) !!}

                    @include('descuentos.fields')

                    {!! Form::close() !!}
                    @include("descuentos.modal_catalogo")

                </div>
            </div>
        </div>
    </div>
@endsection
