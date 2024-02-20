@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tipo Cambio
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
                    {!! Form::open(['route' => 'tipoCambios.store']) !!}

                    <!-- Fecha Field -->
                        <div class="form-group col-sm-4">
                            {!! Form::label('fecha', 'Fecha: *') !!}
                            {!! Form::text('fecha', date("d/m/Y"), ['class' => 'form-control datepicker', 'autocomplete' => 'off']) !!}
                        </div>


                        @include('tipo_cambios.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
