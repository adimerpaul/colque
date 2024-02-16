@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">
            Mis Horas Extra</b>
        </h1>
        <h1 class="pull-right">
                <a class="btn btn-primary pull-right"
                   style="margin-top: -10px;margin-bottom: 5px"
                   href="{{ route('mis-horas-extra-solicitud') }}" title="Solicitar Nueva Hora Extra">Crear nuevo</a>
        </h1>
        <br>
    </section>
    <div class="content">
    @include('flash::message')
       @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                    {!! Form::open(['route' => 'mis-horas-extra','method'=>'get'])!!}
                        <div class="form-group col-sm-2">
                            {!! Form::label('inicio', 'Fecha Inicial:') !!}
                            {!! Form::date('inicio', old('inicio', isset($_GET['inicio']) ? $_GET['inicio'] : date('Y-m-d', strtotime('-1 month', strtotime(isset($_GET['fin']) ? $_GET['fin'] : date('Y-m-d'))))), ['class' => 'form-control','required']) !!}
                        </div>
                        <div class="form-group col-sm-2">
                            {!! Form::label('fin', 'Fecha Final:') !!}
                            {!! Form::date('fin', old('fin', isset($_GET['fin']) ? $_GET['fin'] : date('Y-m-d')), ['class' => 'form-control','required']) !!}
                        </div>
                        <div class="form-group col-sm-1" style="margin-top: 24px">
                            <button type="submit" class="btn btn-default glyphicon glyphicon-search" title="Buscar datos"></button>
                        </div>
                    {!! Form::close() !!}
                    </div>
                </div>
                   <!-- inicio -->
                   <table class="table table-striped">
                            <thead >
                            <tr>
                                    <th>#</th>
                                    <th>Fecha Inicial</th>
                                    <th>Fecha Final</th> 
                                    <th>Descripción</th>
                                    <th>Tiempo</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($horasExtra as $item)
                            <tr>
                                <td>{{  $loop->iteration}}</td>
                                <td>{{ \Carbon\Carbon::parse($item->inicio)->format('d/m/Y H:i:s') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->fin)->format('d/m/Y H:i:s') }}</td>
                                <td>{{  $item->descripcion}}</td>
                        @endforeach
                        @if($Extrahour!=0)
                            @foreach ($Extrahour as $tiempo)
                                    <td>{{ $tiempo}}</td>
                            @endforeach
                        @endif    
                        <tr>
                            <td>
                                <b style="text-align: center">
                                    TOTALES
                                </b>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b><?php print($tiempos->sum("sumatoria_horas_extras") . " min" )?></b></td>
                        </tr>
                        </tbody>
                    </table>
                   <!-- Fin -->
            </div>
        </div>
    </div>
@endsection
