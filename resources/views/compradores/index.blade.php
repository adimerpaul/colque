@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Compradores</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('compradores.create') }}">Agregar nuevo</a>
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
                        {!! Form::open(['route' => 'compradores.index', 'method'=>'get']) !!}

                        <div class="form-group col-sm-6">
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ? $_GET['txtBuscar'] : null, ['class' => 'form-control', 'placeholder'=>'Razón social, Nit']) !!}
                        </div>

                        <div class="form-group col-sm-2" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                    @include('compradores.table')
            </div>
        </div>
        <div class="text-center">
            {{ $compradores->appends($_GET)->links()  }}
        </div>
    </div>
@endsection

