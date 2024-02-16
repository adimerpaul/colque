@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Agregar cuenta por cobrar
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
                    {!! Form::open(['route' => 'store-cuenta-antigua']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::label('cliente_id', 'Cliente o productor: *') !!}
                        {!! Form::select('origen_id', \App\Patrones\Fachada::listarCLientes(), null, ['class' => 'form-control select2', 'required']) !!}

                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('monto', 'Monto BOB: *') !!}
                        {!! Form::number('monto', null, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('motivo', 'Motivo: ') !!}
                        {!! Form::select('clase', \App\Patrones\Fachada::getClasesCuentasSinRetiro(), null, ['class' => 'form-control', 'required']) !!}
                    </div>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id'=>'botonGuardar']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
