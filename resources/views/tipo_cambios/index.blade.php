@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Tipos de cambio</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('tipoCambios.create') }}">Agregar nuevo</a>
        </h1>
        <br>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'tipoCambios.index', 'method'=>'get']) !!}

                        <div class="form-group col-sm-6">
                            {!! Form::label('Fecha', 'Fecha:') !!}
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
                    @include('tipo_cambios.table')
            </div>
        </div>
        <div class="text-center">
            {{ $tipoCambios->appends($_GET)->links() }}
        </div>
    </div>
@endsection

