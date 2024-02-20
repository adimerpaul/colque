@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Cotizaciones diarias de <b>{{$mineral->info}}</b></h1>
        <h1 class="pull-right">
            <a class="btn btn-default pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-left: 10px" href="{{ route('materials.index') }}">Volver</a>
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('cotizacions.register', [$mineral->id]) }}">Agregar nuevo</a>
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
                        {!! Form::open(['route' => ['cotizacions.lista', $mineral->id], 'method'=>'get']) !!}

                        <div class="form-group col-sm-6">
                            {!! Form::label('txtBuscar', 'Fecha:') !!}
                            {!! Form::date('fecha', isset($_GET['fecha']) ? $_GET['fecha'] : null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group col-sm-2" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                    @include('cotizacions.table')
            </div>
        </div>
        <div class="text-center">
            {{ $cotizacions->appends($_GET)->links() }}
        </div>
    </div>
@endsection

