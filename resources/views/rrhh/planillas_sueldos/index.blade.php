@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Planilla de Sueldos y salarios</h1>
        
        <h1 class="pull-right">
             <a class="btn btn-primary"
                style="margin-top: -10px; margin-bottom: 5px"
                data-target="#modalPlanilla"
                data-toggle="modal">Generar Planilla</a >
        </h1>
    </section>
    <section class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="card">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            
                            <div>
                                {!! Form::label('txtBuscar', 'Buscar por:') !!}
                                {!! Form::open(['route' => 'planillas-sueldos.index', 'method' => 'get']) !!}

                                <div class="form-group col-sm-3">
                                    {!! Form::label('mes', 'Mes:') !!}
                                    {!! Form::month('mes', isset($_GET['mes']) ? $_GET['mes'] : date('Y-m'), ['class' => 'form-control', 'required']) !!}
                                </div>
                                <div class="form-group col-sm-3">
                                    {!! Form::label('tipo_contrato', 'Contrato:') !!}
                                    {!! Form::select('tipo_contrato', ['contrato' => 'Contrato','eventual' => 'Eventual'], isset($_GET['tipo_contrato']) ? $_GET['tipo_contrato'] : null, ['class' => 'form-control', 'required']) !!}
                                </div>
                                <div class="form-group col-sm-1" style="margin-top: 24px">
                                    <button type="submit" class="btn btn-default glyphicon glyphicon-search" title="Buscar Datos"></button>
                                </div>
                                {!! Form::close() !!}
                                <div class="form-group col-sm" style="margin-top: 27px">
                                    <a class="btn btn-success fa fa-file"
                                        href="#"
                                        title="Exportar documento"> Exportar</a>
                                </div>
                            </div>
                        
                          @if($mensaje = Session::get('success'))
                                <div class="alert alert-success" role="alert">
                                    {{$mensaje}}
                                </div>
                            @endif
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12"></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        @include('rrhh.planillas_sueldos.item_table')
                    </div>
                </div>
                <div class="text-center">
                    
                </div>
            </div>
        </div>
        @include("rrhh.planillas_sueldos.modal_planilla")  
    </section>

@endsection

