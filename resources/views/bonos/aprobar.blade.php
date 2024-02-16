@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Devolución pendiente
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('monto', 'Monto BOB:') !!}
                            <p>{{ number_format($devolucion->monto,2) }}</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('cliente', 'Cliente:') !!}
                            <p>{{ $devolucion->formularioLiquidacion->cliente->nombre }}</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('producto', 'Productor:') !!}
                            <p>{{ $devolucion->formularioLiquidacion->cliente->cooperativa->razon_social }}</p>
                        </div>

{{--                        <div class="form-group">--}}
{{--                            {!! Form::label('autorizado', 'Autorizado por:') !!}--}}
{{--                            <p>{{ $devolucion->autorizado->personal->nombre_completo }}</p>--}}
{{--                        </div>--}}

                        <div class="form-group">
                            {!! Form::label('fecha', 'Fecha:') !!}
                            <p>{{ date('d/m/Y H:i', strtotime($devolucion->created_at)) }}</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('motivo', 'Motivo:') !!}
                            <p>{{ $devolucion->motivo }}</p>
                        </div>

                    {!! Form::open(['route' => 'aprobar-devolucion']) !!}

                    {!! Form::hidden('id', $devolucion->id, ['class' => 'form-control']) !!}

                        <div class="form-group col-sm-12">
                            {!! Form::submit('Aprobar', ['class' => 'btn btn-primary']) !!}
                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
