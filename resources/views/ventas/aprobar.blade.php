@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Venta pendiente
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group col-sm-6">
                            {!! Form::label('lote', 'Lote:') !!}
                            <p>{{ $venta->lote }}</p>
                        </div>

                        <div class="form-group col-sm-6">
                            {!! Form::label('monto', 'Monto BOB:') !!}
                            <p>{{ number_format($venta->monto,2) }}</p>
                        </div>

                        <!-- porcentaje_arsenico Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('cliente', 'Cliente:') !!}
                            <p>{{ $venta->comprador->razon_social }}</p>
                        </div>

                        <!-- porcentaje_estanio Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('fecha', 'Fecha:') !!}
                            <p>{{ date('d/m/Y H:i', strtotime($venta->updated_at)) }}</p>
                        </div>

                        {!! Form::open(['route' => 'aprobar-venta']) !!}

                        <div class="form-group col-sm-6">
                            {!! Form::label('tipoDiferencia', 'Tipo Diferencia: *') !!}
                            {!! Form::select('tipo_diferencia', \App\Patrones\Fachada::getDiferenciaMontoVenta(), null, ['class' => 'form-control form-control-lg', 'required']) !!}
                        </div>

                        <div class="form-group col-sm-6">
                            {!! Form::label('diferencia', 'Diferencia: *') !!}
                            {!! Form::number('diferencia', '0', ['class' => 'form-control','step'=>'0.01', 'min' =>'0', 'required']) !!}
                        </div>


                        <div class="form-group col-sm-12">


                        {!! Form::hidden('id', $venta->id, ['class' => 'form-control']) !!}

                        <!-- Submit Field -->
                            {!! Form::submit('Aprobar', ['class' => 'btn btn-primary']) !!}


                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
