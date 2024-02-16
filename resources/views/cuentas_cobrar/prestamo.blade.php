@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Registrar préstamo
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'cuentas.store-prestamo']) !!}

                    <div class="form-group col-sm-12">
                        {!! Form::label('cliente_id', 'Cliente o productor: *') !!}
                        {!! Form::select('origen_id', \App\Patrones\Fachada::listarCLientes(), null, ['class' => 'form-control select2']) !!}

                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('monto', 'Monto BOB: *') !!}
                        {!! Form::number('monto', null, ['class' => 'form-control', 'required', 'min'=>0, 'step'=>'0.01']) !!}
                    </div>


                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('materials.index') }}" class="btn btn-default">Cancelar</a>
                    </div>


                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
