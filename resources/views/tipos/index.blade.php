@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Tipo</h1>
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" data-toggle="modal" data-target="#modalTipo">Agregar nuevo</a>
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
                        {!! Form::open(['route' => 'tipos.index', 'method'=>'get']) !!}
                        <div class="form-group col-sm-3">
                            {!! Form::label('tabla', 'Tipo:') !!}
                            {!! Form::select('tabla',\App\Patrones\Fachada::tiposTablas() , isset($_GET['tabla']) ? $_GET['tabla'] : "%" , ['class' => 'form-control', 'id' =>'tabla']) !!}
                        </div>

                        <div class="form-group col-sm-2" style="margin-top: 25px">

                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                @include('tipos.table')
            </div>
        </div>
        <div class="text-center">
            {{ $tipos->appends($_GET)->links()  }}
        </div>
    </div>
    <div id="appTipo">
        @include("tipos.modal")
    </div>
@endsection

