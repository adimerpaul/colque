@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tabla de Precios de Estaño: # {{ $ta->id }}
        </h1>
        <h1 class="pull-right">
            <a href="{{ route('tablaAcopiadoras.index') }}" style="margin-top: -50px;" class="btn btn-default">Volver a
                la lista</a>
        </h1>
    </section>
    <div class="content">
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 10px">
                    <div class="col-sm-12">
                        @include('tabla_acopiadoras.show_fields')
                        @include('tabla_acopiadora_detalles.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style type="text/css">
        thead tr th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #ffffff;
        }

        .table-responsive {
            height:500px;
            overflow:scroll;
        }
    </style>
@endsection

