@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Leyes de <b>{{$material->nombre}}</b></h1>
        <h1 class="pull-right">
            <a class="btn btn-default pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('materials.index') }}">Volver</a>
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('leys.register', [$material->id]) }}">Agregar nuevo</a>

        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('leys.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

