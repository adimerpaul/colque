@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Retenciones Pendientes</h1>
        <h1 class="pull-right">
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
                        {!! Form::open(['route' => ['retenciones.lista', $productorId], 'method'=>'get']) !!}

                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Ini.:') !!}
                            {!! Form::date('fecha_inicial', isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial'] : date('Y-m-d', strtotime(date('Y-m-d'). ' - 12 months')), ['class' => 'form-control', 'id' => 'fecha_inicial']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('Fecha', 'Fecha Fin.:') !!}
                            {!! Form::date('fecha_final', isset($_GET['fecha_final']) ? $_GET['fecha_final'] : date('Y-m-d'), ['class' => 'form-control','id' => 'fecha_final']) !!}
                        </div>

                        <div class="form-group col-sm-4" style="margin-top: 25px" >
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                            <a  class='btn btn-primary btn-md' id="btnAprobar" href="#" data-target="#modalPago"
                                data-toggle="modal">
                                <i class="glyphicon glyphicon-usd"></i> Aprobar
                            </a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                @include('retenciones_pagos.table_calculos')
            </div>
        </div>
        <div class="text-center">
            {{ $retenciones->appends($_GET)->links()  }}
        </div>
    </div>
@endsection
