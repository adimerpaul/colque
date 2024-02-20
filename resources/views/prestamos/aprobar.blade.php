@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Préstamo pendiente
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('monto', 'Monto BOB:') !!}
                            <p>{{ number_format($prestamo->monto,2) }}</p>
                        </div>

                        <!-- porcentaje_arsenico Field -->
                        <div class="form-group">
                            {!! Form::label('cliente', 'Cliente:') !!}
                            <p>{{ $prestamo->cliente->nombre }}</p>
                        </div>

                        <!-- porcentaje_antimonio Field -->
                        <div class="form-group">
                            {!! Form::label('producto', 'Productor:') !!}
                            <p>{{ $prestamo->cliente->cooperativa->razon_social }}</p>
                        </div>

                        <!-- porcentaje_bismuto Field -->
                        <div class="form-group">
                            {!! Form::label('registrado', 'Registrado por:') !!}
                            <p>{{ $prestamo->registrado->personal->nombre_completo }}</p>
                        </div>

                        <!-- porcentaje_estanio Field -->
                        <div class="form-group">
                            {!! Form::label('fecha', 'Fecha:') !!}
                            <p>{{ date('d/m/Y H:i', strtotime($prestamo->created_at)) }}</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('motivo', 'Motivo:') !!}
                            <p>{{ $prestamo->motivo }}</p>
                        </div>

                        <div class="form-group col-sm-12">

                        {!! Form::open(['route' => 'aprobar-prestamo']) !!}

                        {!! Form::hidden('id', $prestamo->id, ['class' => 'form-control']) !!}

                        <!-- Submit Field -->
                            {!! Form::submit('Aprobar', ['class' => 'btn btn-primary']) !!}

                            {!! Form::close() !!}

                            {!! Form::open(['route' => ['rechazar-prestamo', $prestamo->id], 'method' => 'delete']) !!}
                                {!! Form::button('Rechazar', ['type' => 'submit', 'class' => 'btn btn-danger btn-lm', 'onclick' => "return confirm('¿Estás seguro de rechazar?')"]) !!}
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
