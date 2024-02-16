@extends('layouts.app')

@section('content')
<section class="content-header">
        <h1 class="pull-left">Movimiento de Catalogos</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('movimientos-catalogos.create') }}">Agregar nuevo</a>
        </h1>
</section>

<section class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  <div class="clearfix"></div>
  <div class=¨card¨>
    <div class="box box-primary">
        <div class="box-body">
          <div class="row">
                  <div class="col-sm-12">
                    {!! Form::open(['route' => 'movimientos-catalogos.index', 'method' => 'get']) !!}

                        <div class="form-group col-sm-6">
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ? $_GET['txtBuscar'] : null, ['class' => 'form-control', 'placeholder'=>'Descripción']) !!}
                        </div>
                        

                        <div class="form-group col-sm-2" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>

                    {!! Form::close() !!}
                    @if($mensaje = Session::get('success'))
                      <div class="alert alert-success" role="alert">
                        {{$mensaje}}
                      </div>
                    @endif
                  </div>
              <hr>
              <div class="row">
                <div class="col-sm-12">

                </div>
              </div>
          </div>
          @include('movimiento_catalogo.table')
        </div>
        <div class="text-center">
        <td {{ $movimientoCatalogo->links() }}</td>
        </div>
    </div>
  </div>
</section>

@endsection
