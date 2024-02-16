@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Actualizar Tabla de Precios de Estaño: # {{ $tablaAcopiadora->id }}
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       @include('flash::message')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tablaAcopiadora, ['route' => ['tablaAcopiadoras.update', $tablaAcopiadora->id], 'method' => 'patch']) !!}

                        @include('tabla_acopiadoras.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
