@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cotizaciones Oficiales de <strong>{{date('d/m/Y', strtotime( $cotizaciones[0]->fecha)) }}</strong>
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="clearfix"></div>

        <div class="box box-primary">
            <div class="box-body">

                <div class="row" style="padding: 15px">

                    <div class="col-sm-12">


                        @foreach($cotizaciones as $cotizacion)
                            <hr>
                            <h2 style="text-align: center">{{ $cotizacion->mineral->nombre }}</h2>
                            <!-- Monto Field -->
                            <div class="form-group col-sm-3">
                                Cotización: <h3>{{$cotizacion->monto}}</h3>
                            </div>

                            <div class="form-group col-sm-3">
                                Unidad: <h3>{{$cotizacion->unidad}}</h3>
                            </div>
                            <div class="form-group col-sm-3">
                                Alicuota exportación: <h3>{{$cotizacion->alicuota_exportacion}}</h3>
                            </div>
                            <div class="form-group col-sm-3">
                                Alicuota interna: <h3>{{$cotizacion->alicuota_interna}}</h3>
                            </div>

                            <br>
                            <hr style="height: 3px; background-color: black">
                            <br>

                        @endforeach
                            <hr>

                            <iframe src="{{ asset("documents/oficial_" . $cotizaciones[0]->fecha.'.pdf?='.date('dmYHis')) }}" frameborder="0"
                                    style="height: 800px; width: 100%"></iframe>

                        <!-- Submit Field -->
                        <div class="form-group col-sm-6" style="margin-top: 25px">
                            {!! Form::open(['route' => 'aprobar-cotizacion-oficial']) !!}

                            {!! Form::hidden('fecha', $cotizaciones[0]->fecha ) !!}
                            {!! Form::submit('Aprobar', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>


            </div>
        </div>
    </div>
@endsection
