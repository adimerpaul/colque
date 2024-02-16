@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Cuentas por cobrar</h1>
        <h1 class="pull-right">
            <a class="btn btn-info pull-right" style="margin-top: -10px; margin-left: 2px"
               href="{{ route('cuentas-cobrar-total') }}">
                Historial de cuentas</a>
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-left: 2px"
               href="{{ route('prestamos-emitidos') }}">Reporte préstamos emitidos</a>

            @if(\App\Patrones\Permiso::esAdmin())
                <a class="btn btn-success pull-right" style="margin-top: -10px;margin-bottom: 5px"
                   href="{{ route('agregar-cuenta-antigua') }}">Agregar</a>
            @endif
        </h1>
        <br>
    </section>
    <div class="content" id="appFormularioIndex">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'pagos.cuentas', 'method'=>'get']) !!}
                        <div class="form-group col-sm-4">
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ?$_GET['txtBuscar']: null, ['class' => 'form-control', 'placeholder'=>'Cliente, nit, productor, comprobante']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Ini.:') !!}
                            {!! Form::date('fecha_inicial', isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial'] : date('Y-m-d', strtotime(date('Y-m-d'). ' - 3 months')), ['class' => 'form-control', 'id' => 'fecha_inicial']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Fin.:') !!}
                            {!! Form::date('fecha_final', isset($_GET['fecha_final']) ? $_GET['fecha_final'] : date('Y-m-d'), ['class' => 'form-control','id' => 'fecha_final']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('txtEstado', 'Estado:') !!}
                            {!! Form::select('txtEstado', \App\Patrones\Fachada::getEstadosCajaAnticipos(),isset($_GET['txtEstado']) ? $_GET['txtEstado'] : null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-2" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>

                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
                @include('cuentas_cobrar.table_cuentas')
            </div>
        </div>
        <div class="text-center">
            {{ $cuentas->appends($_GET)->links()  }}
        </div>
    </div>
@endsection
