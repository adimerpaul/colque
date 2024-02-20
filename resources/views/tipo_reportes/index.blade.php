@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Tipo de reporte</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('tipoReportes.create') }}">Agregar nuevo</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                    </div>
                </div>
                    @include('tipo_reportes.table')
            </div>
        </div>
        <div class="text-center">
            {{ $tipoReportes->appends($_GET)->links()  }}
        </div>
    </div>
@endsection

