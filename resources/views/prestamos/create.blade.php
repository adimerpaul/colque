@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Registrar préstamo
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'prestamos.store', 'id' => 'formularioModal']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::label('cliente_id', 'Cliente o productor: *') !!}
                        {!! Form::select('cliente_id', \App\Patrones\Fachada::listarCLientes(), null, ['class' => 'form-control select2', 'required']) !!}

                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('monto', 'Monto BOB: *') !!}
                        {!! Form::number('monto', null, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('motivo', 'Motivo: ') !!}
                        {!! Form::text('motivo', null, ['class' => 'form-control', 'maxlength' => '250', 'required']) !!}
                    </div>


                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary', 'id'=>'botonGuardar']) !!}
                        <a href="{{ route('prestamos.index') }}" class="btn btn-default">Cancelar</a>
                    </div>


                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $("#formularioModal").on("submit", function() {
            $("#botonGuardar").prop("disabled", true);
        });
    </script>
@endpush
