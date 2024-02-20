@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Comprobante Caja
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
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'cajas.imprimir-comprobante', 'method'=>'get', 'target'=>'_blank']) !!}

                        <div class="form-group col-sm-4">
                            {!! Form::label('Fecha', 'Fecha de Cancelación:') !!}
                            {!! Form::date('fecha', date('Y-m-d'), ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group col-sm-4" style="margin-top: 25px">
                            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i>
                                Exportar
                            </button>
                            <a href="{{ route('cajas.index') }}" class="btn btn-default"><i class="glyphicon glyphicon-circle-arrow-left"></i> Volver</a>

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
