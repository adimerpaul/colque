@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Bienvenido al sistema</h1>
        <h1 class="pull-right">
            @if(\App\Patrones\Permiso::esSuperAdmin())
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
                   href="{{ route('liquidacion-automatica') }}">Liquidar Lotes Antiguos</a>
            @endif
        </h1>
        <br>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            {!! Form::open(['route' => 'home', 'method'=>'get']) !!}
            <div class="box-body">
                <div class="text-center">

                    @if(!\App\Patrones\Fachada::tieneCotizacion())

                        <br>
                        <div class="alert alert-danger small">
                            <h4>REGISTRE LA COTIZACIÓN DIARIA Y OFICIAL DE TODOS LOS MINERALES</h4>

                            <strong>Cuidado!</strong> No se ha encontrado cotizaciones
                            para
                            la fecha {{date("d/m/Y")}}, comuníquese con el administrador
                        </div>
                    @endif
                </div>

                @include('dashboard')
                <div class="row" style="padding-left: 6px; padding-right: 6px; ">
                    <hr style="height: 6px; background-color: #1976D2">

                    <div class="col-sm-12" style="text-align: center; margin-top: -20px ">
                        <h1 style="color: #1565C0">1. COMPRAS</h1>

                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group col-sm-3">
                            {!! Form::label('Fecha', 'Mes inicio:') !!}
                            {!! Form::month('fecha_inicio', isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m', strtotime(date('Y-m'). ' - 1 years')), ['class' => 'form-control', 'required']) !!}
                        </div>
                        <div class="form-group col-sm-3">
                            {!! Form::label('Fecha', 'Mes fin:') !!}
                            {!! Form::month('fecha_fin', isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date("Y-m"), ['class' => 'form-control', 'required']) !!}
                        </div>

                        <div class="form-group col-sm-3" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>

                    </div>
                </div>
                <div class="row align-items-center" style="text-align: center">
                    <div class="form-group col-sm-2"></div>
                    <div class="form-group col-sm-8">
                        @include('reportes.table_historico')
                    </div>
                    <div class="form-group col-sm-2"></div>
                </div>
                <br>



                <div class="row">
                    <div class="col-sm-6">
                        <div id="chartPesoNetoSeco"></div>
                        {!! $chartPesoNetoSeco !!}
                    </div>

                    <div class="col-sm-6">
                        <div id="chartValorNetoVenta"></div>
                        {!! $chartValorNetoVenta !!}
                    </div>
                    <br>
                </div>


                <hr style="height: 2px; background-color: black">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group col-sm-3">
                            {!! Form::label('Fecha', 'Fecha inicio:') !!}
                            {!! Form::date('fecha_inicio_productor', isset($_GET['fecha_inicio_productor']) ? $_GET['fecha_inicio_productor'] : date('Y-m-d', strtotime(date('Y-m-d'). ' - 7 days')), ['class' => 'form-control', 'required']) !!}
                        </div>
                        <div class="form-group col-sm-3">
                            {!! Form::label('Fecha', 'Fecha fin:') !!}
                            {!! Form::date('fecha_fin_productor', isset($_GET['fecha_fin_productor']) ? $_GET['fecha_fin_productor'] : date("Y-m-d"), ['class' => 'form-control', 'required']) !!}
                        </div>

                        <div class="form-group col-sm-3" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>

                    </div>
                </div>
                <div class="row align-items-center" style="text-align: center">
                    <div class="form-group col-sm-2"></div>
                    <div class="form-group col-sm-8">
                        @include('reportes.cooperativas_liquidadas')
                    </div>
                    <div class="form-group col-sm-2"></div>
                </div>
                <br>
                <hr style="height: 2px; background-color: black">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="form-group col-sm-3">
                            {!! Form::label('Mes', 'Mes:') !!}
                            {!! Form::month('mes', isset($_GET['mes']) ? $_GET['mes'] : date('Y-m'), ['class' => 'form-control', 'required']) !!}
                        </div>
                        <div class="form-group col-sm-3" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>

                    </div>
                </div>
                <div id="chartComprasMes"></div>
                {!! $chartComprasMes !!}
                <br>
{{--                --}}
                <div class="row" style="padding-left: 6px; padding-right: 6px; ">
                    <hr style="height: 6px; background-color: #1976D2">

                    <div class="col-sm-12" style="text-align: center; margin-top: -20px ">
                        <h1 style="color: #1565C0">2. VENTAS</h1>

                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group col-sm-3">
                            {!! Form::label('Fecha', 'Mes inicio:') !!}
                            {!! Form::month('fecha_inicio_venta', isset($_GET['fecha_inicio_venta']) ? $_GET['fecha_inicio_venta'] : date('Y-m', strtotime(date('2023-05'))), ['class' => 'form-control', 'required', 'min' =>'2023-05']) !!}
                        </div>
                        <div class="form-group col-sm-3">
                            {!! Form::label('Fecha', 'Mes fin:') !!}
                            {!! Form::month('fecha_fin_venta', isset($_GET['fecha_fin_venta']) ? $_GET['fecha_fin_venta'] : date("Y-m"), ['class' => 'form-control', 'required']) !!}
                        </div>

                        <div class="form-group col-sm-3" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>

                    </div>
                </div>
                <div class="row align-items-center" style="text-align: center">
                    <div class="form-group col-sm-2"></div>
                    <div class="form-group col-sm-8">
                        @include('reportes.table_historico_ventas')
                    </div>
                    <div class="form-group col-sm-2"></div>
                </div>
                <br>

                <div class="row">
                    <div class="col-sm-6">
                        <div id="chartPesoNetoSecoVenta"></div>
                        {!! $chartPesoNetoSecoVenta !!}
                    </div>

                    <div class="col-sm-6">
                        <div id="chartValorNetoVentaVenta"></div>
                        {!! $chartValorNetoVentaVenta !!}
                    </div>
                    <br>
                </div>

                <hr style="height: 2px; background-color: black">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Inicio:') !!}
                            {!! Form::date('fecha_inicial_venta', isset($_GET['fecha_inicial_venta']) ? $_GET['fecha_inicial_venta'] : date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 months')), ['class' => 'form-control', 'id' => 'fecha_inicial_venta']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Fin:') !!}
                            {!! Form::date('fecha_final_venta', isset($_GET['fecha_final_venta']) ? $_GET['fecha_final_venta'] : date('Y-m-d'), ['class' => 'form-control','id' => 'fecha_final_venta']) !!}
                        </div>
                        <div class="form-group col-sm-3" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>
                    </div>


                </div>
                <div class="row " style="text-align: center">
                    <div class="form-group col-sm-2"></div>
                    <div class="form-group col-sm-8">
                        @include('reportes.table_reporte_ventas')
                    </div>
                    <div class="form-group col-sm-2"></div>
                </div>

{{--                --}}
                <div class="row" style="padding-left: 6px; padding-right: 6px; ">
                    <hr style="height: 6px; background-color: #1976D2">

                    <div class="col-sm-12" style="text-align: center; margin-top: -20px ">
                        <h1 style="color: #1565C0">3. STOCK</h1>
                        <a class="btn btn-primary pull-right" target="_blank" style="margin-top: -50px;"
                           href="{{ route('materiales-stock') }}">STOCK ACTUAL</a>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group col-sm-3">
                            {!! Form::label('Fecha', 'Mes inicio:') !!}
                            {!! Form::month('fecha_inicio_stock', isset($_GET['fecha_inicio_stock']) ? $_GET['fecha_inicio_stock'] : date('Y-m', strtotime(date('Y-m'). ' - 1 years')), ['class' => 'form-control', 'required']) !!}
                        </div>
                        <div class="form-group col-sm-3">
                            {!! Form::label('Fecha', 'Mes fin:') !!}
                            {!! Form::month('fecha_fin_stock', isset($_GET['fecha_fin_stock']) ? $_GET['fecha_fin_stock'] : date("Y-m"), ['class' => 'form-control', 'required']) !!}
                        </div>

                        <div class="form-group col-sm-3" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>

                    </div>
                </div>
                <div class="row align-items-center" style="text-align: center">
                    <div class="form-group col-sm-2"></div>
                    <div class="form-group col-sm-8">
                        @include('reportes.table_historico_stock')
                    </div>
                    <div class="form-group col-sm-2"></div>
                </div>
                <br>

                <div class="row" style="padding-left: 6px; padding-right: 6px; ">
                    <hr style="height: 6px; background-color: #1976D2">

                    <div class="col-sm-12" style="text-align: center; margin-top: -20px ">
                        <h1 style="color: #1565C0">4. SATISFACCIÓN</h1>

                    </div>
                </div>
                <br>
                <div id="chartSatisfaccionClientes"></div>
                {!! $chartSatisfaccionClientes !!}
                <br>
                <hr style="height: 2px; background-color: black">


                <br>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <script src="{{ asset('js/jquery.table.merge.js') }}"></script>
    <script>
        $('#historicoProducto').margetable({
            type: 2,
            colindex: [1]
        });
        $('#historicoProductoStock').margetable({
            type: 2,
            colindex: [1]
        });
        $('#tablaProductores').margetable({
            type: 2,
            colindex: [1]
        });
    </script>
@endsection
