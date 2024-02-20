@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Lista de Minerales</h1>
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px;" href="{{ route('cotizacionOficials.createMultiple') }}">Cotización oficial</a>
            <a class="btn btn-info pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-right:5px" href="{{ route('cotizacions.createMultiple') }}">Cotización diaria</a>
            @if(\App\Patrones\Permiso::esAdmin())
            <a class="btn btn-success pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-right:5px" href="{{ route('materials.create') }}">Agregar nuevo</a>
            @endif
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('materials.table')
            </div>
        </div>
        <div class="text-center">
            {{ $materials->appends($_GET)->links()  }}
        </div>
    </div>
@endsection

