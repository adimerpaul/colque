@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Contrato</h1>
        @if(\App\Patrones\Permiso::esAdmin())

        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('contratos.create') }}">Agregar nuevo</a>
        </h1>
        @endif
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'contratos.index', 'method'=>'get']) !!}


                        {!! Form::close() !!}
                    </div>
                </div>
                @include('contratos.table')
            </div>
        </div>
        <div class="text-center">
            {{ $contratos->appends($_GET)->links()  }}
        </div>
    </div>
@endsection

