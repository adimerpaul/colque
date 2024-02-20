@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Lista de Minerales</h1>

    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('empresa_minerales.fields')
            </div>
            <hr style="height: 5px">
            <div class="box-body">
                @include('empresa_minerales.table')
            </div>
        </div>
        <div class="text-center">
            {{ $minerales->appends($_GET)->links() }}
        </div>
    </div>
@endsection

