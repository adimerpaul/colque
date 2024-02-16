@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Ingresos/Egresos</h1>
        <h1 class="pull-right">

        </h1>
        <br>
    </section>
    <div class="content" id="appFormularioIndex">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'movimientos.lista-pagos', 'method'=>'get']) !!}
                        <div class="form-group col-sm-5">
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ?$_GET['txtBuscar']: null, ['class' => 'form-control', 'placeholder'=>'Comprobante, glosa, monto']) !!}
                        </div>

                        <div class="form-group col-sm-3" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                @include('movimientos.table_anular')

            </div>
        </div>
        <div class="text-center">
            {{ $pagos->appends($_GET)->links()  }}
        </div>
    </div>



@endsection

