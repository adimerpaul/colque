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
                   {!! Form::model($tipo, ['route' => ['tipos-activos.update', $tipo->id], 'method' => 'patch']) !!}


                       @include('activos.tipos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
