@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Modificar Activo Fijo
        </h1>
   </section>
   <div class="content">
   @include('flash::message')
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($movimientoCatalogo, ['route' => ['movimientos-catalogos.update', $movimientoCatalogo->id], 'method' => 'patch']) !!}
                    

                       @include('movimiento_catalogo.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection