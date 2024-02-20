@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Movimiento pendiente
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('monto', 'Monto BOB:') !!}
                            <p>{{ number_format($movimiento->monto,2) }}</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('cliente', 'Proveedor:') !!}
                            <p>{{ $movimiento->proveedor->nombre }}</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('producto', 'Empresa:') !!}
                            <p>{{ $movimiento->proveedor->empresa }}</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('autorizado', 'Autorizado por:') !!}
                            <p>{{ $movimiento->autorizado->personal->nombre_completo }}</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('fecha', 'Fecha:') !!}
                            <p>{{ date('d/m/Y H:i', strtotime($movimiento->created_at)) }}</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('motivo', 'Motivo:') !!}
                            <p>{{ $movimiento->motivo }}</p>
                        </div>

                    {!! Form::open(['route' => 'aprobar-movimiento']) !!}

                    {!! Form::hidden('id', $movimiento->id, ['class' => 'form-control']) !!}

                    <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Aprobar', ['class' => 'btn btn-primary']) !!}

                        {!! Form::close() !!}

                        {!! Form::open(['route' => ['rechazar-movimiento', $movimiento->id], 'method' => 'delete']) !!}
                            {!! Form::button('Rechazar', ['type' => 'submit', 'class' => 'btn btn-danger btn-lm', 'onclick' => "return confirm('¿Estás seguro de rechazar?')"]) !!}
                        {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
