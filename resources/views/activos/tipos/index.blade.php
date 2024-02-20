@extends('layouts.app')

@section('content')
<section class="content-header">
        <h1 class="pull-left">Tipos de Activos</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('tipos-activos.create') }}">Agregar nuevo</a>
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
                    @if($mensaje = Session::get('success'))
                      <div class="alert alert-success" role="alert">
                        {{$mensaje}}
                      </div>
                    @endif
                  </div>
              <hr>

          </div>
            <div class="table-responsive">
          @include('activos.tipos.table')
        </div>
    </div>
  </div>
</section>

@endsection
