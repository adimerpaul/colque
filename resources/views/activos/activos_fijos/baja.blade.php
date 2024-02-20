@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Baja Activo - Cantidad Actual: <b>{{$activoFijo->cantidad_unidad}}</b> Código:<b>{{$activoFijo->codigo}}</b>        </h1>
   </section>
   <div class="content">
   @include('flash::message')
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">

                        @include('activos.activos_fijos.bajas_fields')
               </div>

           </div>

       </div>
   </div>
@endsection
